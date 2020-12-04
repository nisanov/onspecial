<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateScrapesTable extends AbstractMigration
{
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
     * 
     * @return void
     */
    public function change(): void
    {
        $this
            ->table('scrapes', ['id' => false, 'primary_key' => 'id'])
            ->addColumn('id', 'biginteger', ['signed' => false, 'identity' => true])
            ->addColumn('store_id', 'biginteger', ['signed' => false])
            ->addColumn('records', 'integer', ['signed' => false])
            ->addColumn('expired', 'integer', ['signed' => false])
            ->addColumn('seconds', 'decimal', ['signed' => false, 'precision' => 8, 'scale' => 4])
            ->addColumn('error', 'text', ['null' => true, 'default' => null])
            ->addColumn('created_at', 'timestamp', ['precision' => 4])
            ->addColumn('updated_at', 'timestamp', ['precision' => 4, 'null' => true, 'default' => null])
            ->addForeignKey('store_id', 'stores', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->create();
    }
}
