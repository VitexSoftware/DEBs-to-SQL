<?php
/**
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 * @copyright (c) 2023-2024, Vítězslav Dvořák
 */

// Debian autoloader for debs2sql
// Load dependency autoloaders
require_once '/usr/share/php/Ease/autoload.php';
require_once '/usr/share/php/EaseFluentPDO/autoload.php';
require_once '/usr/share/php/EaseTWB5/autoload.php';
require_once '/usr/share/php/League/CommonMark/autoload.php';

/**
 * Autoloader for DebToSQL namespace
 */
spl_autoload_register(function ($class) {
    if (strpos($class, 'DebToSQL\\') === 0) {
        $file = '/usr/lib/debs2sql/' . str_replace('\\', '/', $class) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

require_once '/usr/share/php/Composer/InstalledVersions.php';

(function (): void {
    $versions = [];
    foreach (\Composer\InstalledVersions::getAllRawData() as $d) {
        $versions = array_merge($versions, $d['versions'] ?? []);
    }
    $name    = 'unknown';
    $version = '0.0.0';
    $versions[$name] = ['pretty_version' => $version, 'version' => $version,
        'reference' => null, 'type' => 'library', 'install_path' => __DIR__,
        'aliases' => [], 'dev_requirement' => false];
    \Composer\InstalledVersions::reload([
        'root' => ['name' => $name, 'pretty_version' => $version, 'version' => $version,
            'reference' => null, 'type' => 'project', 'install_path' => __DIR__,
            'aliases' => [], 'dev' => false],
        'versions' => $versions,
    ]);
})();

