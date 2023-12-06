<?php

/**
 * Debian paggage and its contents SQL indexer
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 * @copyright  2021-2023 Vitex Software
 */

require_once '../vendor/autoload.php';

define('APP_NAME', 'debs2sql');
\Ease\Shared::init(['DB_CONNECTION',
'DB_HOST',
'DB_PORT',
'DB_DATABASE',
'DB_USERNAME',
'DB_PASSWORD',
'REPO_DIR'], '../.env');

$repositor = new \DebToSQL\Repository();

$repositor->parseDists();

$repositor->saveAllToSQL();

$repositor->updatePresenceStatus();
