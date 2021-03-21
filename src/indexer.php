<?php

/**
 * Debian paggage and its contents SQL indexer
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 * @copyright  2021 Vitex Software
 */
require_once '../vendor/autoload.php';

$shared = \Ease\Shared::instanced();
if (file_exists('../.env')) {
    $shared->loadConfig('../.env', true);
}

define('APP_NAME', 'debs2sql');

$repositor = new \DebToSQL\Repository();

$repositor->parseDists();

$repositor->saveAllToSQL();

$repositor->updatePresenceStatus();
