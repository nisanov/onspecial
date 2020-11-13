<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateSpecialsTable extends AbstractMigration
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
     */
    public function change(): void
    {
        $this
            ->table('specials', ['id' => false, 'primary_key' => 'id'])
            ->addColumn('id', 'biginteger', ['signed' => false, 'identity' => true])
            ->addColumn('store_id', 'biginteger', ['signed' => false])
            ->addColumn('product_id', 'biginteger', ['signed' => false])
            ->addColumn('current_price', 'decimal', ['signed' => false, 'precision' => 8, 'scale' => 2])
            ->addColumn('previous_price', 'decimal', ['signed' => false, 'precision' => 8, 'scale' => 2])
            ->addColumn('ratio_price', 'string', ['null' => true, 'default' => null])
            ->addTimestamps()
            ->addForeignKey('store_id', 'stores', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->addForeignKey('product_id', 'products', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->create();
    }
}
