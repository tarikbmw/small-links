<?php
namespace LinksApplication;

/**
 * Autoloader class
 * @author tarik
 */
class Autoload
{
    /**
     * Array of registered classnames
     * @var array
     */
    static $loadedClasses = [];

    const PATH = 
    [
    	'src' => '../src/', 
    	'actions' => '../src/Actions/'
	];
	
    const EXT = ".php";

    function __construct()
    {
        spl_autoload_extensions(self::EXT);
        spl_autoload_register(function($className)
        {
	        $tmp = explode('\\', $className);	        
        	$className = array_pop($tmp); // PHP Strict Standards
            $file = self::PATH['src'].$className.self::EXT;
            if (!file_exists($file))
            {
                        $file = self::PATH['actions'].$className.self::EXT;
			            if (!file_exists($file))
                            throw new \Exception("Could not find class `$className` in file `$file`");

            }

            require_once $file;

            self::$loadedClasses[$className] = $file;
        });
    }
}
