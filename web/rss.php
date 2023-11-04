<?php

namespace DebToSQL;

/**
 * VitexSoftware - DEBs to SQL web interface
 *
 * @author     Vitex <vitex@hippy.cz>
 * @copyright  2023 Vitex@hippy.cz (G)
 */
require_once '../vendor/autoload.php';
\Ease\Shared::init(['DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'], '../.env');

use SimpleXMLElement;

$packages = new Ui\NewPackages(\Ease\Shared::cfg('RSS_ITEMS',20)); // [['title'=>'n/a','description'=>'','icon'=>'','date'=>'']];
header("Content-Type: application/xml; charset=UTF-8");
#header("Content-Type: application/rss+xml; charset=UTF-8");

$xml = new SimpleXMLElement('<rss/>');
$xml->addAttribute("version", "2.0");
$channel = $xml->addChild("channel");
$channel->addChild("title", _('VitexSoftware Package feed'));
$channel->addChild("link", \Ease\Shared::cfg('REPO_URL'));
$channel->addChild("description", "Fresh packages in our repository");
$channel->addChild("language", "en-us");

foreach ($packages->getRssData(\Ease\WebPage::getRequestValue('search')) as $entry) {
    $item = $channel->addChild("item");
    $item->addChild("title", $entry['title']);
    $item->addChild("link", \Ease\Shared::cfg('REPO_URL') . $entry['link']);
    $item->addChild("description", $entry['description']);
    $item->addChild("pubDate", $entry['date']);
    $enclosure = $item->addChild('enclosure');
    $enclosure->addAttribute('url', $entry['icon']);
    $enclosure->addAttribute('type', image_type_to_mime_type(intval(constant('IMAGETYPE_' . strtoupper(pathinfo($entry['icon'], PATHINFO_EXTENSION))))));
}

echo $xml->asXML();
