<?php
namespace LinksApplication;

/**
 * Class LinkStatistics
 * Get statistics by user
 */
class LinkStatistics implements Action
{
    use UserApi;

    public function process(int $start = 0, int $count = 20, int $user = 0):Response
    {
        if ($user)
            if (!$this->getUserCount($user))
                throw new \Exception('User not found');

        return new Response(['links' => $this->getStat($start, $count, $user)]);
    }

    protected function getStat(int $start, int $count, int $user):array
    {
        $result     = [];
        $db         = $this->getConnection();
        $statement  = NULL;
        $url        = NULL;
        $visited    = NULL;

        if (!$user)
        {
            $statement = $db->prepare('select origin, visited from link 
                                        left join url using(urlID) limit ?,?');
            $statement->bind_param('ii', $start, $count);
        }
        else
        {
            $statement = $db->prepare('select origin, visited from link 
                                        left join url using(urlID) where userID = ? limit ?,?');
            $statement->bind_param('iii', $user, $start, $count);
        }

        if (!$statement->execute())
            throw new \Exception('Failed to execute query.');


        $statement->bind_result($url, $visited);
        while ($statement->fetch())
            $result[] = ['url' => $url, 'visited' => $visited];

        $statement->close();

        return $result;
    }
}
