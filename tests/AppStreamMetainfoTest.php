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

namespace Test;

use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
class AppStreamMetainfoTest extends \PHPUnit\Framework\TestCase
{
    private string $rootDir;

    protected function setUp(): void
    {
        $this->rootDir = \dirname(__DIR__);
    }

    public function testSvgIconExists(): void
    {
        $this->assertFileExists($this->rootDir.'/debs2sql.svg');
    }

    public function testMetainfoExists(): void
    {
        $this->assertFileExists($this->rootDir.'/io.github.vitexsoftware.debs2sql.metainfo.xml');
    }

    public function testMetainfoHasStockIcon(): void
    {
        $xml = simplexml_load_file($this->rootDir.'/io.github.vitexsoftware.debs2sql.metainfo.xml');
        $this->assertNotFalse($xml);
        $icons = $xml->xpath('//icon[@type="stock"]');
        $this->assertNotEmpty($icons, 'metainfo.xml must declare a stock icon');
        $this->assertSame('debs2sql', (string) $icons[0]);
    }

    public function testDebianMetainfoHasStockIcon(): void
    {
        $xml = simplexml_load_file($this->rootDir.'/debian/io.github.vitexsoftware.debs2sql.metainfo.xml');
        $this->assertNotFalse($xml);
        $icons = $xml->xpath('//icon[@type="stock"]');
        $this->assertNotEmpty($icons, 'debian metainfo.xml must declare a stock icon');
        $this->assertSame('debs2sql', (string) $icons[0]);
    }

    public function testIconNameMatchesSvgFilename(): void
    {
        $xml = simplexml_load_file($this->rootDir.'/io.github.vitexsoftware.debs2sql.metainfo.xml');
        $this->assertNotFalse($xml);
        $icons = $xml->xpath('//icon[@type="stock"]');
        $iconName = (string) $icons[0];
        $this->assertFileExists($this->rootDir.'/'.$iconName.'.svg');
    }
}
