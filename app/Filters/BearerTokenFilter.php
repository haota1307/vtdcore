<?php
namespace App\Filters;

use App\Models\UserModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class BearerTokenFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $header = $request->getHeaderLine('Authorization');
        if (preg_match('/^Bearer\s+(\S+)/i', $header, $m)) {
            $token = $m[1];
            $record = service('tokens')->findValid($token);
            if ($record) {
                $user = (new UserModel())->find($record['user_id']);
                if ($user) {
                    // Attach user + token abilities to request attributes
                    $request->user = $user; // dynamic property
                    $request->tokenAbilities = $record['abilities'];
                    // If abilities required
                    if (!empty($arguments)) {
                        $required = [];
                        foreach ($arguments as $arg) {
                            foreach (explode(',', $arg) as $piece) {
                                $piece = trim($piece);
                                if ($piece !== '') $required[] = $piece;
                            }
                        }
                        if ($required) {
                            $abilities = $record['abilities'] ?? [];
                            $ok = false;
                            if (in_array('*', $abilities, true)) {
                                $ok = true;
                            } else {
                                foreach ($required as $r) {
                                    if (in_array($r, $abilities, true)) { $ok = true; break; }
                                }
                            }
                            if (!$ok) {
                                return service('response')->setStatusCode(403)->setJSON(['error'=>'Forbidden (abilities)']);
                            }
                        }
                    }
                    return; // allow
                }
            }
        }
        return service('response')->setStatusCode(401)->setJSON(['error'=>'Unauthorized token']);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
