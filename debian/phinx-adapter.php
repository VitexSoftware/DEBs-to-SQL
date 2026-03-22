<?php

/**
 * Multi AbraFlexi Setup - Phinx database adapter.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 * @copyright  2021-2023 Vitex Software
 */


include_once '/usr/share/php/Ease/Atom.php';
include_once '/usr/share/php/Ease/Shared.php';
include_once '/usr/share/php/Ease/Molecule.php';
include_once '/usr/share/php/Ease/Logger/Logging.php';
include_once '/usr/share/php/Ease/Sand.php';
include_once '/usr/share/php/Ease/Functions.php';
include_once '/usr/share/php/Ease/Logger/Message.php';
include_once '/usr/share/php/Ease/Logger/Loggingable.php';
include_once '/usr/share/php/Ease/Logger/Loggingable.php';
include_once '/usr/share/php/Ease/Logger/ToMemory.php';
include_once '/usr/share/php/Ease/recordkey.php';
include_once '/usr/share/php/Ease/Brick.php';
include_once '/usr/share/php/Ease/Person.php';
include_once '/usr/share/php/Ease/Anonym.php';
include_once '/usr/share/php/Ease/User.php';
include_once '/usr/share/php/Ease/Logger/ToStd.php';
include_once '/usr/share/php/Ease/Logger/ToSyslog.php';
include_once '/usr/share/php/Ease/Logger/ToConsole.php';
include_once '/usr/share/php/Ease/Logger/Regent.php';
include_once '/usr/share/php/Ease/Logger/ToMemory.php';
include_once '/usr/share/php/Ease/Exception.php';
include_once '/usr/share/php/EaseFluentPDO/Orm.php';
include_once '/usr/share/php/EaseFluentPDO/Engine.php';


if (file_exists('/etc/debs2sql/.env')) {
    \Ease\Shared::instanced()->loadConfig('/etc/debs2sql/.env', true);
}

$prefix = "/usr/lib/debs2sql/db/";

$sqlOptions = [];

if (strstr(\Ease\Shared::cfg('DB_CONNECTION'), 'sqlite')) {
    $sqlOptions["database"] = "/var/lib/dbconfig-common/sqlite3/debs2sql/" . basename(\Ease\Shared::cfg("DB_DATABASE"));
}
$engine = new \Ease\SQL\Engine(null, $sqlOptions);
$cfg = [
    'paths' => [
        'migrations' => [$prefix . 'migrations'],
        'seeds' => [$prefix . 'seeds']
    ],
    'environments' =>
    [
        'default_environment' => 'development',
        'development' => [
            'adapter' => \Ease\Shared::cfg('DB_CONNECTION'),
            'name' => $engine->database,
            'connection' => $engine->getPdo($sqlOptions)
        ],
        'production' => [
            'adapter' => \Ease\Shared::cfg('DB_CONNECTION'),
            'name' => $engine->database,
            'connection' => $engine->getPdo($sqlOptions)
        ],
    ]
];

return $cfg;
