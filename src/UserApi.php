<?php
namespace LinksApplication;

/**
 * Trait UserApi
 * Common db methods for user entries
 */
trait UserApi
{
    use Database;

    protected function getUserCount(int $userID):int
    {
        $user = 0;
        $db = $this->getConnection();

        $statement = $db->prepare('select count(userID) from user where userID = ?');
        $statement->bind_param('i', $userID);
        if (!$statement->execute())
            throw new \Exception('Failed to execute query.');

        $statement->bind_result($user);
        $statement->fetch();
        $statement->close();

        return $user;
    }
}
