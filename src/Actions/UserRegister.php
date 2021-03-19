<?php
namespace LinksApplication;

/**
 * Class UserRegister
 * Register new user and/or get user ID if exists
 */
class UserRegister implements Action
{
    use Database;

    /**
     * @param string|null $login user email or some text ID, IP, etc
     * @return Response
     */
    public function process(?string $login = NULL):Response
    {
        /**
         * TODO: Check login by regular expressions
         */
        return new Response(['user' => $this->register($login)]);
    }

    /**
     * Register new user and/or get its ID by login
     * @param string $login
     * @return int
     * @throws \Exception
     */
    private function register(string $login):int
    {
        $user = $this->getUser($login);
        if ($user)
            return $user;

        $db = $this->getConnection();
        $statement = $db->prepare('insert into user(mail) values (?)');
        $statement->bind_param('s', $login);
        if (!$statement->execute())
            throw new \Exception('Failed to execute query.');
        $statement->close();

        return $db->insert_id;
    }

    /**
     * Get userID by login
     * @param string $login
     * @return int
     * @throws \Exception
     */
    protected function getUser(string $login):int
    {
        $user = 0;
        $db = $this->getConnection();

        $statement = $db->prepare('select userID as count from user where mail = ?');
        $statement->bind_param('s', $login);
        if (!$statement->execute())
            throw new \Exception('Failed to execute query.');

        $statement->bind_result($user );
        $statement->fetch();
        $statement->close();

        return $user;
    }
}
