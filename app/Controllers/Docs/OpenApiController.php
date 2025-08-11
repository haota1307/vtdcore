<?php
namespace App\Controllers\Docs;

use CodeIgniter\Controller;

class OpenApiController extends Controller
{
    public function index()
    {
    $spec = [
                'openapi' => '3.0.3',
                'info' => [
                    'title' => 'Platform API',
                    'version' => '1.1.0'
                ],
            'components'=>[
                'securitySchemes'=>[
                    'sessionCookie'=>['type'=>'apiKey','in'=>'cookie','name'=>session_name()],
                    'bearerAuth'=>['type'=>'http','scheme'=>'bearer']
                ],
                'schemas'=>[
                    'User'=>['type'=>'object','properties'=>['id'=>['type'=>'integer'],'email'=>['type'=>'string'],'username'=>['type'=>'string']]],
                    'MediaItem'=>['type'=>'object','properties'=>['id'=>['type'=>'integer'],'url'=>['type'=>'string'],'mime'=>['type'=>'string'],'variants'=>['type'=>'object'],'owner_id'=>['type'=>'integer']]],
                    'Token'=>['type'=>'object','properties'=>['id'=>['type'=>'integer'],'token'=>['type'=>'string'],'abilities'=>['type'=>'array','items'=>['type'=>'string']],'expires_at'=>['type'=>'string','format'=>'date-time']]],
                    'Error'=>['type'=>'object','properties'=>['error'=>['type'=>'string']]],
                ]
            ],
            'paths'=>[
                '/auth/login'=>['post'=>[
                    'summary'=>'Login', 'responses'=>[
                        '200'=>['description'=>'Success','content'=>['application/json'=>['schema'=>['$ref'=>'#/components/schemas/User']]]],
                        '401'=>['description'=>'Invalid credentials','content'=>['application/json'=>['schema'=>['$ref'=>'#/components/schemas/Error']]]]
                    ]
                ]],
                '/auth/register'=>['post'=>['summary'=>'Register','responses'=>['201'=>['description'=>'Created']]]],
                '/auth/me'=>['get'=>['summary'=>'Current user','security'=>[['sessionCookie'=>[]]],'responses'=>['200'=>['description'=>'OK']]]],
                '/auth/logout'=>['post'=>['summary'=>'Logout','security'=>[['sessionCookie'=>[]]],'responses'=>['200'=>['description'=>'OK']]]],
                '/auth/tokens'=>['post'=>['summary'=>'Issue token','security'=>[['sessionCookie'=>[]]],'responses'=>['200'=>['description'=>'Issued','content'=>['application/json'=>['schema'=>['$ref'=>'#/components/schemas/Token']]]]]]],
                '/auth/refresh/issue'=>['post'=>['summary'=>'Issue refresh token','responses'=>['201'=>['description'=>'Created']]]],
                '/auth/refresh/rotate'=>['post'=>['summary'=>'Rotate refresh token','responses'=>['200'=>['description'=>'Rotated']]]],
                '/auth/refresh/revoke'=>['post'=>['summary'=>'Revoke refresh token','responses'=>['200'=>['description'=>'Revoked']]]],
                '/auth/2fa/enable'=>['post'=>['summary'=>'Enable 2FA','responses'=>['200'=>['description'=>'Secret issued']]]],
                '/auth/2fa/verify'=>['post'=>['summary'=>'Verify 2FA code','responses'=>['200'=>['description'=>'Verified'],'401'=>['description'=>'Invalid code']]]],
                '/auth/2fa/disable'=>['post'=>['summary'=>'Disable 2FA','responses'=>['200'=>['description'=>'Disabled']]]],
                '/auth/2fa/backup-codes'=>['post'=>['summary'=>'Generate 2FA backup codes','responses'=>['200'=>['description'=>'Codes generated']]]],
                '/auth/2fa/backup-verify'=>['post'=>['summary'=>'Verify backup 2FA code','responses'=>['200'=>['description'=>'Verified'],'401'=>['description'=>'Invalid']]]],
                '/auth/roles'=>['post'=>['summary'=>'Create role','responses'=>['201'=>['description'=>'Created']]]],
                '/auth/roles/{id}/permissions/add'=>['post'=>['summary'=>'Add permission to role','responses'=>['200'=>['description'=>'Added']]]],
                '/auth/roles/{id}/permissions/remove'=>['post'=>['summary'=>'Remove permission from role','responses'=>['200'=>['description'=>'Removed']]]],
                '/media/upload'=>['post'=>['summary'=>'Upload media','security'=>[['sessionCookie'=>[]]],'responses'=>['201'=>['description'=>'Created']]]],
                '/media/item/{id}'=>['get'=>['summary'=>'Show media item','security'=>[['sessionCookie'=>[]]],'parameters'=>[['name'=>'id','in'=>'path','required'=>true,'schema'=>['type'=>'integer']]],'responses'=>['200'=>['description'=>'OK'],'404'=>['description'=>'Not found']]]],
                '/media/item/{id}/thumb'=>['get'=>['summary'=>'Media thumbnail','parameters'=>[['name'=>'id','in'=>'path','required'=>true,'schema'=>['type'=>'integer']]],'responses'=>['200'=>['description'=>'JPEG'],'404'=>['description'=>'No thumb']]]],
                '/media/list'=>['get'=>['summary'=>'List media (bearer)','security'=>[['bearerAuth'=>[]]],'parameters'=>[['name'=>'page','in'=>'query','schema'=>['type'=>'integer']],['name'=>'limit','in'=>'query','schema'=>['type'=>'integer']]],'responses'=>['200'=>['description'=>'OK']]]],
                '/audit/logs'=>['get'=>['summary'=>'Audit logs','security'=>[['sessionCookie'=>[]]],'parameters'=>[['name'=>'page','in'=>'query','schema'=>['type'=>'integer']],['name'=>'limit','in'=>'query','schema'=>['type'=>'integer']]],'responses'=>['200'=>['description'=>'OK'],'403'=>['description'=>'Forbidden']]]],
                '/auth/password/forgot'=>['post'=>['summary'=>'Request password reset','responses'=>['200'=>['description'=>'Always 200 (mask)']]]],
                '/auth/password/reset'=>['post'=>['summary'=>'Consume password reset','responses'=>['200'=>['description'=>'Updated'],'401'=>['description'=>'Invalid token']]]],
            ]
        ];
        $spec['x-changelog'] = [
            ['version'=>'1.1.0','changes'=>[
                'Added refresh token endpoints',
                '2FA management + backup codes',
                'Role permission management endpoints'
            ]],
            ['version'=>'1.0.0','changes'=>['Initial core release']]
        ];
        $encoded = json_encode($spec);
        $hash = substr(sha1($encoded),0,16);
        $cacheDir = WRITEPATH . 'cache' . DIRECTORY_SEPARATOR;
        $cacheFile = $cacheDir . 'openapi_'.$hash.'.json';
        $refresh = $this->request->getGet('refresh');
        if (!$refresh && is_file($cacheFile)) {
            return $this->response->setContentType('application/json')->setBody(file_get_contents($cacheFile));
        }
        // Clean old spec caches
        foreach (glob($cacheDir.'openapi_*.json') as $old) {
            if (strpos($old, $hash) === false) @unlink($old);
        }
        file_put_contents($cacheFile, $encoded);
        return $this->response->setContentType('application/json')->setBody($encoded);
    }
}
