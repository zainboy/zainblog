<?php
/**
 * User: zain
 * Date: 2017/1/13
 * Time: 15:11
 */

define('DS', DIRECTORY_SEPARATOR);
defined('BASE_PATH') or define('BASE_PATH', realpath(__DIR__).DS.'..'.DS);
defined('APP_PATH') or define('APP_PATH', BASE_PATH.'app'.DS);
defined('CACHE_PATH') or define('CACHE_PATH', BASE_PATH.'storage'.DS);
require BASE_PATH.'vendor/autoload.php';
require APP_PATH.'zain/helper.php';

\Zain\Config::set(include APP_PATH . 'config.php');
Zain\Session::boot();
$controllersDirectory = APP_PATH . 'controllers';
$modelsDirectory = APP_PATH . 'models';
Zain\ClassLoader::register();
Zain\ClassLoader::addDirectories(array($controllersDirectory, $modelsDirectory));

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$capsule->addConnection(\Zain\Config::get('database'));
// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();
// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

$blade = new \duncan3dc\Laravel\BladeInstance(APP_PATH.'views',CACHE_PATH.'views');
\duncan3dc\Laravel\Blade::setInstance($blade);

require APP_PATH.'functions.php';
require APP_PATH.'route.php';