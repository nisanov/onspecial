<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUserProductTable extends AbstractMigration
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
            ->table('user_product', ['id' => false, 'primary_key' => 'id'])
            ->addColumn('id', 'biginteger', ['signed' => false, 'identity' => true])
            ->addColumn('user_id', 'biginteger', ['signed' => false])
            ->addColumn('product_id', 'biginteger', ['signed' => false])
            ->addColumn('favorite', 'boolean', ['default' => false])
            ->addColumn('ignoring', 'boolean', ['default' => false])
            ->addIndex(['user_id', 'product_id'], ['name' => 'idx_user_product', 'unique' => true])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->addForeignKey('product_id', 'products', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->create();
    }
}
