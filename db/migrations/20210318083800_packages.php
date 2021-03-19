<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Packages extends AbstractMigration {

    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void {
        // create the table
        $table = $this->table('packages');
        $table->addColumn('Name', 'string', ['null' => false])
                ->addColumn('Package', 'string', ['null' => true])
                ->addColumn('Appname', 'string', ['null' => true])
                ->addColumn('Essential', 'string', ['null' => true])
                ->addColumn('Vendor', 'string', ['null' => true])
                ->addColumn('License', 'string', ['null' => true])
                ->addColumn('Distribution', 'string', ['null' => false])
                ->addColumn('Suite', 'string', ['null' => false])
                ->addColumn('Source', 'string', ['null' => true])
                ->addColumn('Version', 'string', ['null' => false])
                ->addColumn('Architecture', 'string', ['null' => true])
                ->addColumn('MultiArch', 'string', ['null' => true])
                ->addColumn('Maintainer', 'string', ['null' => true])
                ->addColumn('InstalledSize', 'string', ['null' => true])
                ->addColumn('Depends', 'string', ['null' => true,'length'=>2000])
                ->addColumn('PreDepends', 'string', ['null' => true])
                ->addColumn('Breaks', 'string', ['null' => true])
                ->addColumn('Replaces', 'string', ['null' => true])
                ->addColumn('Section', 'string', ['null' => true])
                ->addColumn('Priority', 'string', ['null' => true])
                ->addColumn('Description', 'text', ['null' => false,'default'=>''])
                ->addColumn('LongDescription', 'text', ['null' => true])
                ->addColumn('AutoBuiltPackage', 'text', ['null' => true])
                ->addColumn('Filename', 'string', ['null' => false])
                ->addColumn('MD5sum', 'string', ['null' => true])
                ->addColumn('SHA1', 'string', ['null' => true])
                ->addColumn('SHA256', 'string', ['null' => true])
                ->addColumn('SHA512', 'string', ['null' => true])
                ->addColumn('Size', 'string', ['null' => true])
                ->addColumn('Auto-Built-Package', 'string', ['null' => true])
                ->addColumn('Conflicts', 'string', ['null' => true])
                ->addColumn('Homepage', 'string', ['null' => true])
                ->addColumn('Provides', 'string', ['null' => true])
                ->addColumn('Recommends', 'string', ['null' => true])
                ->addColumn('Suggests', 'string', ['null' => true,'length'=>1000])
                ->addColumn('Exists', 'boolean', ['default' => 1])
                ->addColumn('fileMtime', 'timestamp')
                ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                ->addIndex(['Name'])
                ->addIndex(['Architecture'])
                ->addIndex(['Distribution'])
                ->addIndex(['Suite'])
                ->addIndex(['Section'])
                ->addIndex(['Depends'])
                ->addIndex(['Conflicts'])
                ->addIndex(['Version'])
                ->addIndex(['Depends'])
                ->addIndex(['Description'], ['type' => 'fulltext'])
                ->addIndex(['LongDescription'], ['type' => 'fulltext'])
                ->addIndex(['Maintainer'], ['type' => 'fulltext'])
                ->create();
    }

}
