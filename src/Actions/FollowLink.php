<?php
namespace LinksApplication;

/**
 * Class FollowLink
 * Redirect to origin by small link
 */
class FollowLink implements Action
{
    use Database;

    /**
     * @param int|null $link link id
     * @return Response
     */
    public function process(?int $link = 0):Response
    {
        $url = $this->getUrlOrigin($link);
        $this->updateVisits($link);

        header(sprintf("Location: %s", $url));
        die();
    }

    /**
     * Get url origin by link ID
     * @param int $linkID
     * @return string
     * @throws \Exception
     */
    private  function getUrlOrigin(int $linkID):string
    {
        $db = $this->getConnection();
        $statement = $db->prepare('select origin from link 
        left join url using(urlID) where linkID = ?');
        $statement->bind_param('i', $linkID);
        if (!$statement->execute())
            throw new \Exception('Failed to execute query.');

        $url = NULL;
        $statement->bind_result($url);
        $statement->fetch();
        $statement->close();
        if (!$url)
            throw new \Exception('URL not found.');
        return $url;
    }

    /**
     * Update visits value
     * @param int $linkID
     * @throws \Exception
     */
    private function updateVisits(int $linkID)
    {
        $db = $this->getConnection();

        $statement = $db->prepare('update link set visited = visited + 1 where linkID = ?');
        $statement->bind_param('i', $linkID);
        if (!$statement->execute())
            throw new \Exception('Failed to execute query.');
        $statement->close();
    }
}
