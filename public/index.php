<?php
namespace LinksApplication;

require_once '../src/Settings.php';
require_once '../src/Common.php';

/**
 * Registering autoloader
 */
new Autoload();

/**
 * Main section
 */
try
{
    /**
     * Process get request
     */
    if (isset($_GET['link']))
    {
        $linkID = (int)$_GET['link'];
        if ($linkID)
        {
            $action = new FollowLink();
            $action->process($linkID);
        }
    }

    /**
     * Get request body from input stream
     */
    $in = file_get_contents('php://input');
    if (!$in)
        throw new \Exception('Empty request body');

    /**
     * Convert request body from json to array
     */
    $request = json_decode($in);
    if (!$request)
        throw new \Exception('Bad request');

    if (!isset($request->action) || !$request->action)
        throw new \Exception('No action passed');

    /**
     * Searching for action
     */
    if (!isset(settings['action'][$request->action]))
        throw new \Exception('No action found for `'.$request->action.'`');

    /**
     * Check for request parameters
     */
     if (isset(settings['action'][$request->action]['requiredParameters']))
		foreach (settings['action'][$request->action]['requiredParameters'] as $parameter)
		    if (!isset($request->$parameter))
		        throw new \Exception('No parameter `'.$parameter.'` passed');

    /**
     * Create action class instance
     */
    $actionClass = '\LinksApplication\\'.settings['action'][$request->action]['callback'];
    if (!class_exists($actionClass))
        throw new \Exception('Callback `'.$request->action.'` not found');
    $action = new $actionClass();

    /**
     * Get class reflection object and method
     */
    $refClass = new \ReflectionClass($actionClass);
    $refMethod = $refClass->getMethod('process');

    /**
     * Get method parameters
     */
    $refParameters = $refMethod->getParameters();
    $args = [];
    foreach ($refParameters as $parameter)
        $args[] = $request->{$parameter->getName()} ?? $parameter->getDefaultValue();

    /**
     * Invokes method with parameters and render output
     */
    echo $refMethod->invokeArgs($action, $args);
}
catch(\Exception $error)
{
    header('500 Internal Server Error');
    echo new Response(['message'   => $error->getMessage()], 'error');
}
