<?php
namespace App\Filters;

use App\Config\AuthSecurity;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RateLimitFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $cfg = config(AuthSecurity::class);
        $method = strtoupper($request->getMethod());
        $path = '/'.trim($request->getUri()->getPath(),'/');
        $keyBase = $method.' '.$path;
        $override = $cfg->endpointLimits[$keyBase] ?? null;
        $limit = $override[0] ?? $cfg->genericLimit;
        $window = $override[1] ?? $cfg->genericWindowSeconds;
        $ip = $request->getIPAddress();
        $idUser = session('user_id') ?: 'guest';
        $cacheKey = 'rl:'.sha1($keyBase.'|'.$ip.'|'.$idUser.'|'.$window);
        $cache = cache();
        $data = $cache->get($cacheKey) ?? ['count'=>0,'start'=>time()];
        if (time() - $data['start'] >= $window) {
            $data = ['count'=>0,'start'=>time()];
        }
        if ($data['count'] >= $limit) {
            $retry = $window - (time() - $data['start']);
            return service('response')
                ->setHeader('Retry-After', (string)$retry)
                ->setHeader('X-RateLimit-Limit', (string)$limit)
                ->setHeader('X-RateLimit-Remaining', '0')
                ->setStatusCode(429)
                ->setJSON([
                    'error'=>'rate_limited','retry_after'=>$retry
                ]);
        }
        $data['count']++;
        $cache->save($cacheKey,$data,$window);
        // attach provisional headers via a temp store (use response service directly in after())
        service('response')->setHeader('X-RateLimit-Limit', (string)$limit)
            ->setHeader('X-RateLimit-Remaining', (string)max(0,$limit-$data['count']))
            ->setHeader('X-RateLimit-Reset', (string)($data['start']+$window));
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
