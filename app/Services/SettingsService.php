<?php
namespace App\Services;

use CodeIgniter\Database\ConnectionInterface;
use Config\Database;

class SettingsService
{
    private ConnectionInterface $db;
    private array $cache = [
        'system'=>[],
        'module'=>[],
        'user'=>[],
    ];
    private bool $preloaded = false;

    public function __construct(?ConnectionInterface $db = null)
    {
        $this->db = $db ?? Database::connect();
    }

    public function clearCache(): void
    {
        $this->cache = ['system'=>[],'module'=>[],'user'=>[]];
        $this->preloaded = false;
    }

    public function preloadAll(): void
    {
        if ($this->preloaded) return;
        $rows = $this->db->table('settings')->get()->getResultArray();
        foreach ($rows as $r) {
            $val = $this->castFromStored($r['value'],$r['type']);
            if ($r['scope_type']==='system') {
                $this->cache['system'][$r['key']] = $val;
            } elseif ($r['scope_type']==='module') {
                $this->cache['module'][$r['module']][$r['key']] = $val;
            } elseif ($r['scope_type']==='user') {
                $mKey = $r['module'] ?: '_';
                $this->cache['user'][$r['scope_id']][$mKey][$r['key']] = $val;
            }
        }
        $this->preloaded = true;
    }

    public function get(string $key, array $context = []): mixed
    {
        // Precedence: user > module > system
        if (isset($context['user_id'])) {
            $val = $this->getUser($context['user_id'], $key, $context['module'] ?? null);
            if ($val !== null) return $val;
        }
        if (isset($context['module'])) {
            $val = $this->getModule($context['module'], $key);
            if ($val !== null) return $val;
        }
        return $this->getSystem($key);
    }

    public function setSystem(string $key, mixed $value, ?string $type=null): void
    {
        $this->upsert('system', null, null, $key, $value, $type);
    }

    public function setModule(string $module, string $key, mixed $value, ?string $type=null): void
    {
        $this->upsert('module', null, $module, $key, $value, $type);
    }

    public function setUser(int $userId, string $key, mixed $value, ?string $type=null, ?string $module=null): void
    {
        $this->upsert('user', $userId, $module, $key, $value, $type);
    }

    private function getSystem(string $key): mixed
    {
        if (! isset($this->cache['system'][$key])) {
            $this->cache['system'][$key] = $this->fetchValue('system', null, null, $key);
        }
        return $this->cache['system'][$key];
    }

    private function getModule(string $module, string $key): mixed
    {
        $bucket =& $this->cache['module'][$module][$key];
        if (! isset($bucket)) {
            $bucket = $this->fetchValue('module', null, $module, $key);
        }
        return $bucket;
    }

    private function getUser(int $userId, string $key, ?string $module=null): mixed
    {
        $mKey = $module ?: '_';
        $bucket =& $this->cache['user'][$userId][$mKey][$key];
        if (! isset($bucket)) {
            $bucket = $this->fetchValue('user', $userId, $module, $key);
        }
        return $bucket;
    }

    private function fetchValue(string $scopeType, ?int $scopeId, ?string $module, string $key): mixed
    {
        $builder = $this->db->table('settings')->where([
            'scope_type'=>$scopeType,
            'key'=>$key,
        ]);
        if ($scopeType === 'user') { $builder->where('scope_id', $scopeId); } else { $builder->where('scope_id', null); }
        if ($scopeType === 'module' || $module) { $builder->where('module', $module); } else { $builder->where('module', null); }
        $row = $builder->get()->getRowArray();
        if (! $row) return null;
        return $this->castFromStored($row['value'], $row['type']);
    }

    private function upsert(string $scopeType, ?int $scopeId, ?string $module, string $key, mixed $value, ?string $type): void
    {
        $stored = $this->prepareStore($value, $type);
        $data = [
            'scope_type'=>$scopeType,
            'scope_id'=>$scopeId,
            'module'=>$module,
            'key'=>$key,
            'value'=>$stored['value'],
            'type'=>$stored['type'],
            'updated_at'=>date('Y-m-d H:i:s'),
        ];
        // Try update first
        $builder = $this->db->table('settings');
        $builder->where('scope_type',$scopeType)->where('key',$key);
        if ($scopeType === 'user') { $builder->where('scope_id',$scopeId); } else { $builder->where('scope_id', null); }
        if ($scopeType === 'module' || $module) { $builder->where('module',$module); } else { $builder->where('module', null); }
        $exists = $builder->get()->getRowArray();
        if ($exists) {
            $this->db->table('settings')->where('id',$exists['id'])->update($data);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->db->table('settings')->insert($data);
        }
        $this->clearCache();
    }

    private function prepareStore(mixed $value, ?string $type): array
    {
        $detectType = $type ?? gettype($value);
        $storeType = match($detectType) {
            'integer','double' => 'int',
            'boolean' => 'bool',
            'array','object' => 'json',
            default => 'string'
        };
        $storeValue = match($storeType) {
            'int' => (string)$value,
            'bool' => $value ? '1':'0',
            'json' => json_encode($value),
            default => (string)$value,
        };
        return ['value'=>$storeValue,'type'=>$storeType];
    }

    private function castFromStored(?string $value, ?string $type): mixed
    {
        if ($value === null) return null;
        return match($type) {
            'int' => (int)$value,
            'bool' => $value === '1',
            'json' => json_decode($value, true),
            default => $value,
        };
    }
}
