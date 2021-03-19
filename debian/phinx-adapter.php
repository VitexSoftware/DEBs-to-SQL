<?php

/**
 * Debian Repository SQL indexer - Phinx database adapter.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 * @copyright  2021 Vitex Software
 */


include_once '/usr/share/php/EaseCore/Atom.php';
include_once '/usr/share/php/EaseCore/Shared.php';
include_once '/usr/share/php/EaseCore/Functions.php';

$shared = \Ease\Shared::instanced();
$shared->loadConfig('/etc/debs2sql/.env', true);

$prefix = "/usr/lib/debs2sql/db/";

$sqlOptions = [];

if (strstr(\Ease\Functions::cfg('DB_CONNECTION'), 'sqlite')) {
 $sqlOptions["database"] = "/var/lib/dbconfig-common/sqlite3/debs2sql/".basename(\Ease\Functions::cfg("DB_DATABASE"));
}

$engine = new \Ease\SQL\Engine(null, $sqlOptions);
$cfg = [
    'paths' => [
        'migrations' => [$prefix . 'migrations'],
        'seeds' => [$prefix . 'seeds']
    ],
    'environments' =>
    [
        'default_database' => 'development',
        'development' => [
            'adapter' => \Ease\Functions::cfg('DB_CONNECTION'),
            'name' => $engine->database,
            'connection' => $engine->getPdo($sqlOptions)
        ],
        'default_database' => 'production',
        'production' => [
            'adapter' => \Ease\Functions::cfg('DB_CONNECTION'),
            'name' => $engine->database,
            'connection' => $engine->getPdo($sqlOptions)
        ],
    ]
];

return $cfg;
