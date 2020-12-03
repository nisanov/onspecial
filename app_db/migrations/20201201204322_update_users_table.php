<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UpdateUsersTable extends AbstractMigration
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
        $table = $this->table('users');
        $table
            ->addColumn('show_seen', 'boolean', ['default' => true, 'after' => 'password'])
            ->addColumn('show_favorites', 'boolean', ['default' => true, 'after' => 'show_seen'])
            ->addColumn('show_ignored', 'boolean', ['default' => false, 'after' => 'show_favorites'])
            ->update();
    }
}
