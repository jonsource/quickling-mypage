<?php
/*----------------------------------------------------*/
// Paths
/*----------------------------------------------------*/
$root_path = dirname(__DIR__);
$webroot_path = $root_path.DS.'htdocs';

/*----------------------------------------------------*/
// Include composer autoloading
/*----------------------------------------------------*/
if (file_exists($autoload = $root_path.DS.'vendor'.DS.'autoload.php'))
{
	require_once($autoload);
}

/*----------------------------------------------------*/
// Load environment configuration
/*----------------------------------------------------*/
$environments = array();

// Return array of environment data
if (file_exists($file = $root_path.DS.'config'.DS.'environment.php'))
{
	$environments = require_once($file);
}

// Check if there are environment values
if (empty($environments) || (!is_array($environments) && !$environments instanceof \Closure))
{
	printf('<h1>%s</h1>', 'Unable to load environment data. Please define your environments.');
    printf('<p>%s</p>', 'Use the distributed file '.getcwd().' ../config/environment.php.dist');
}

/*----------------------------------------------------*/
// Set environment
/*----------------------------------------------------*/
// Define path and the environment locations.
$env = new Thms\Config\Environment($root_path.DS, $environments);

/*----------------------------------------------------*/
// Load .env file
/*----------------------------------------------------*/
$location = $env->which();

if (empty($location)) 
{   printf('<h1>%s</h1>', 'Unable to define the environment.');
    die();
}

$loaded = $env->load($location);

if (empty($loaded)) {
    printf('<h1>%s</h1>', 'Unable to locate your environment configuration file.');
    printf('<p>%s</p>', 'Use the distributed '.getcwd().' ../.env.'.$location.'.php.dist');
    printf('<p>%s</p>', 'Remember to fix your .htaccess if not running in the www root dir. See .htaccess.dist for more');
    die(); 
}
/*----------------------------------------------------*/
// Check required vars.
/*----------------------------------------------------*/
$check = $env->check(array('DB_NAME', 'DB_USER', 'DB_PASSWORD', 'DB_HOST', 'WP_HOME', 'WP_SITEURL'), $loaded);

/*----------------------------------------------------*/
// Populate environment vars
/*----------------------------------------------------*/
if ($check)
{
	$env->populate($loaded);
}
else
{
	printf('<h2>%s</h2>', 'Missing environment variables.');
}

/*----------------------------------------------------*/
// Load environment config constants
/*----------------------------------------------------*/
if (file_exists($config = $root_path.DS.'config'.DS.'environments'.DS.$location.'.php'))
{
	require_once($config);
}

/*----------------------------------------------------*/
// Content directory
/*----------------------------------------------------*/
define('CONTENT_DIR', 'content');
define('WP_CONTENT_DIR', $webroot_path.DS.CONTENT_DIR);
define('WP_CONTENT_URL', WP_HOME.'/'.CONTENT_DIR);

/*----------------------------------------------------*/
// Include shared configuration
/*----------------------------------------------------*/
if (file_exists($shared = $root_path.DS.'config'.DS.'shared.php'))
{
	require_once($shared);
}

/*----------------------------------------------------*/
// Path to WordPress
/*----------------------------------------------------*/
if (!defined('ABSPATH'))
{
	define('ABSPATH', $webroot_path.DS.'cms'.DS);
}