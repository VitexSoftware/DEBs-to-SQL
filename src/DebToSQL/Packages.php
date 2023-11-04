<?php

/**
 * VitexSoftware Homepage - News Handler
 *
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 * @copyright  2015-2023 Vitex Software
 */

namespace DebToSQL;

/**
 * Description of News
 *
 * @author vitex
 */
class Packages extends \Ease\SQL\Engine
{
    public $myKeyColumn = 'id';
    public $myTable     = 'packages';
    /**
     * Where to look for record's name
     * @var string
     */
    public $nameColumn  = 'Name';

    /**
     * Sloupeček obsahující datum vložení záznamu do shopu
     * @var string
     */
    public $myCreateColumn = 'created';

    /**
     * Slopecek obsahujici datum poslení modifikace záznamu do shopu
     * @var string
     */
    public $myLastModifiedColumn = 'updated';
}
