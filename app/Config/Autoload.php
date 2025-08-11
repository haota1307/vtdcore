<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

/**
 * --------------------------------------------------------------------------
 * AUTO-LOADER
 * --------------------------------------------------------------------------
 * This file defines the namespaces and class maps so the Autoloader can find
 * the files as needed.
 *
 * NOTE: If you use an identical key in $psr4 or $classmap, then
 * the values in this file will overwrite the framework's values.
 */
class Autoload extends AutoloadConfig
{
    /**
     * --------------------------------------------------------------------------
     * Namespaces
     * --------------------------------------------------------------------------
     * This maps the locations of any namespaces in your application to
     * their location on the file system. These are used by the autoloader
     * to locate files the first time they have been instantiated.
     *
     * The '/app' and '/system' directories are already mapped for you.
     * you may change the name of the 'App' namespace if you wish,
     * but this should be done prior to creating any namespaced classes,
     * else you will need to modify all of those classes for this to work.
     *
     * Prototype:
     *   $psr4 = [
     *       'App'         => APPPATH,
     *       'MyApp'      => APPPATH . 'MyApp',
     *   ];
     *
     * @var array<string, array<int, string>|string>
     */
    public $psr4 = [
        APP_NAMESPACE => APPPATH,                    // To change the namespace, change the constant APP_NAMESPACE in app/Config/Constants.php
        'Config'      => APPPATH . 'Config',         // To change the namespace, change the constant APP_NAMESPACE in app/Config/Constants.php
        'App\\Modules\\' => APPPATH . 'Modules/',
        'App\\Core\\' => APPPATH . 'Core/',
    ];

    /**
     * --------------------------------------------------------------------------
     * Class Map
     * --------------------------------------------------------------------------
     * The class map provides a map of class names and their exact
     * location on the drive. Classes loaded in this manner will have
     * slightly faster performance because they will already have been
     * declared and mapped.
     *
     * Prototype:
     *   $classmap = [
     *       'MyClass'   => '/path/to/class/file.php',
     *   ];
     *
     * @var array<string, string>
     */
    public $classmap = [];

    /**
     * --------------------------------------------------------------------------
     * Files
     * --------------------------------------------------------------------------
     * The files array provides a list of paths to __non-class__ files
     * that will be autoloaded. This can be useful for bootstrap operations
     * or for loading functions.
     *
     * Prototype:
     *   $files = [
     *       '/path/to/my/file.php',
     *   ];
     *
     * @var array<int, string>
     */
    public $files = [];

    /**
     * --------------------------------------------------------------------------
     * Helpers
     * --------------------------------------------------------------------------
     * Prototype:
     *   $helpers = [
     *       'form',
     *   ];
     *
     * @var array<int, string>
     */
    public $helpers = [
        'breadcrumb',
    ];
}
