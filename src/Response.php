<?php
namespace LinksApplication;

/**
 * Class Response
 */
final class Response //implements \Stringable
{
    private array $output = [];

    /**
     * Response constructor.
     * @param array $response output values
     * @param string $type  success or error
     */
    public function __construct(array $response, string $type = 'success')
    {
        $response['type'] = $type;
        $this->output = $response;
    }

    /**
     * Render response
     * @return string
     */
    public function __toString():string
    {
        header('Content-type: text/json');
        return json_encode($this->output);
    }
}
