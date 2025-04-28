<?php

declare(strict_types=1);

/**
 * This file is part of the DEBs-to-SQL package
 *
 * https://github.com/VitexSoftware/DEBs-to-SQL
 *
 * (c) Vítězslav Dvořák <http://vitexsoftware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DebToSQL\Ui;

use League\CommonMark\CommonMarkConverter;

/**
 * Description of HtmlMarkdownReadme.
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */
class HtmlMarkdownReadme extends \Ease\Html\DivTag
{
    public function __construct($homepage, $version)
    {
        $cached = sys_get_temp_dir().'/'.md5($homepage.$version).'.md';

        if (!file_exists($cached)) {
            $raw = str_replace('github', 'raw.githubusercontent', $homepage).'/master/README.md';
            file_put_contents($cached, file_get_contents($raw));
        }

        if (file_exists($cached)) {
            $converter = new CommonMarkConverter();
            $readme = $converter->convertToHtml(file_get_contents($cached));

            $this->addItem($readme->__toString(), ['class' => 'jumbotron']);
        }
    }
}
