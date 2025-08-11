<?php
namespace App\Controllers\Admin;

class SettingsController extends AdminBaseController
{
    public function index()
    {
        return $this->render('settings/index', [ 'title' => 'Settings' ]);
    }
}
