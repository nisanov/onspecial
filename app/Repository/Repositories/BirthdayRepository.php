<?php

declare(strict_types=1);

namespace App\Repository\Repositories;

use App\Model\Models\User;
use App\Repository\GenericRepository;
use Phalcon\Mvc\Model\ResultsetInterface;

class BirthdayRepository extends GenericRepository
{
    /**
     * Get ordered birthdays for the specified user.
     *
     * @param User $user
     * @return ResultsetInterface
     */
    public function getBirthdays(User $user): ResultsetInterface
    {
        $query = "
            SELECT
                birthday.*,
                CAST(DATEDIFF(CONCAT(YEAR(NOW()), SUBSTR(birthday.born, 5)), NOW()) AS SIGNED) days
            FROM
                App\Model\Models\Birthday birthday
            WHERE
                birthday.user_id = :userId:
            ORDER BY
                SIGN(days) DESC, days
        ";

        return $this->manager->executeQuery($query, ['userId' => $user->id]);
    }
}
