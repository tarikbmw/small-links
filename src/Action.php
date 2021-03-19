<?php
namespace LinksApplication;

/**
 * Action interface
 */
interface Action
{
    /**
     * Process request and get response as array
     * @return array
     */
    public function process():Response;
}

