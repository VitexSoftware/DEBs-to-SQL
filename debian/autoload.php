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
