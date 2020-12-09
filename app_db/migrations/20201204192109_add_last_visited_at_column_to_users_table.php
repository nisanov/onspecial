<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddLastVisitedAtColumnToUsersTable extends AbstractMigration
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
        $table = $this->table('users');
        $table
            ->addColumn('last_visited_at', 'timestamp', ['precision' => 4, 'null' => true, 'default' => null, 'after' => 'authenticated_at'])
            ->update();
    }
}
