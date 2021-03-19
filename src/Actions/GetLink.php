<?php
namespace LinksApplication;

/**
 * Class getLink
 * Get small link by URL
 */
class GetLink implements Action
{
    use UserApi;

    /**
     * Get link if exits or create new one
     * @param string|null   $url
     * @param int|null      $user
     * @return Response
     */
    public function process(?string $url = NULL, ?int $user = 0):Response
    {
        if (!$this->getUserCount($user))
            throw new \Exception('User not found.');

        $urlID = $this->getUrlID($url, $user);
        $linkID = !$urlID ? $this->createLink($url, $user) : $this->getLink($url, $user)['linkID'];
        if (!$linkID)
            throw new \Exception('Unable to create link entry');

        $link = sprintf("%s%d", settings['siteUrl'], $linkID);

        return new Response( ['link' => $link] );
    }

    /**
     * Search for url and get ID
     * @param string    $url
     * @param int       $user
     * @return int
     * @throws \Exception
     */
    private function getUrlID(string $url, int $user):int
    {
        $db = $this->getConnection();

        $statement = $db->prepare('select urlID from url where origin = ?');
        $statement->bind_param('s', $url);
        if (!$statement->execute())
            throw new \Exception('Failed to execute query.');

        $urlID = 0;
        $statement->bind_result($urlID);
        $statement->fetch();
        $statement->close();

        return $urlID;
    }

    /**
     * Add new link entry and return its ID
     * @param string    $url
     * @param int       $user
     * @return int
     * @throws \Exception
     */
    private function createLink(string $url, int $user):int
    {
        $db = $this->getConnection();

        // Store URL
        $statement = $db->prepare('insert into url(origin) values (?)');
        $statement->bind_param('s', $url);
        if (!$statement->execute())
            throw new \Exception('Failed to execute query.');
        $statement->close();
        $urlID = $db->insert_id;

        // Creating link entry
        $statement = $db->prepare('insert into link(userID, urlID) values (?, ?)');
        $statement->bind_param('ii', $user, $urlID);
        if (!$statement->execute())
            throw new \Exception('Failed to execute query.');
        $statement->close();

        return  $db->insert_id;
    }

    /**
     * Get link ID by url
     * @param string    $url
     * @param int       $user
     * @return int
     * @throws \Exception
     */
    private function getLink(string $url, int $user):array
    {
        $db = $this->getConnection();
        $linkID     = 0;
        $visited    = 0;

        // Store URL
        $statement = $db->prepare('select linkID, visited from url 
        left join link using(urlID) where origin = ? and userID = ?');
        $statement->bind_param('si', $url, $user);
        if (!$statement->execute())
            throw new \Exception('Failed to execute query.');

        $statement->bind_result($linkID, $visited);
        $statement->fetch();
        $statement->close();

        if (!$linkID)
            throw new \Exception('Link entry not found');

        return ['linkID' => $linkID, 'visited' => $visited];
    }
}
