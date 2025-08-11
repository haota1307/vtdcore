<?php
namespace App\Commands\Docs;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class OpenApiExport extends BaseCommand
{
    protected $group = 'Docs';
    protected $name = 'openapi:export';
    protected $description = 'Export dynamic OpenAPI spec and simple Postman collection';
    protected $usage = 'openapi:export out/openapi.json out/postman.json';

    public function run(array $params)
    {
        $outSpec = $params[0] ?? 'openapi.json';
        $outPostman = $params[1] ?? 'postman.json';
        // Build spec using controller logic
        $controller = new \App\Controllers\Docs\OpenApiController();
        $response = $controller->index();
        $spec = json_decode($response->getBody(), true);
        file_put_contents($outSpec, json_encode($spec, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        CLI::write('Exported OpenAPI to '.$outSpec,'green');
        // Build minimal Postman collection
        $items = [];
        foreach ($spec['paths'] as $path => $ops) {
            foreach ($ops as $method => $meta) {
                $items[] = [
                    'name'=> ($meta['summary'] ?? $path.' '.$method),
                    'request'=>[
                        'method'=>strtoupper($method),
                        'header'=>[],
                        'url'=>['raw'=>'{{baseUrl}}'.$path,'host'=>['{{baseUrl}}'],'path'=>explode('/', trim($path,'/'))]
                    ]
                ];
            }
        }
        $postman = [
            'info'=>['name'=>'Core API','schema'=>'https://schema.getpostman.com/json/collection/v2.1.0/collection.json'],
            'item'=>$items,
            'variable'=>[['key'=>'baseUrl','value'=>'http://localhost:8080']]
        ];
        file_put_contents($outPostman, json_encode($postman, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        CLI::write('Exported Postman collection to '.$outPostman,'green');
    }
}
