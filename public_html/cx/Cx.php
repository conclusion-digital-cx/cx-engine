<?php

use Medoo\Medoo;
require_once "lib/Medoo.php";
require_once "lib/Router.php";
require_once "lib/Response.php";
require_once "lib/Service.php";
// Nice dev helper
require_once "lib/debugToConsole.php";

// Use as closure
return function ($config = []) {
	$cx = new Cx($config);

	function getCx() {
	global $cx;
		return $cx;
	}

	return $cx;
};

print_r(getCx());

class Cx
{
	public $notfound;
	public $debug = false;
	public $theme = "2020";
	public $blocks = [];
	public $regions = [
		'title' => [],
		'head' => [],
		'afterbody' => [],
		'content' => [],
		'menu' => [],
		'main' => [],
		'footer' => []
	];
	public $router;
	public $page;

	function __construct($_config = [])
	{
		$this->router = new Router();   // Fork of AltoRouter

		// Convert to object
		$config = (object) array_merge(
			[
				"debug" => true
			],
			(array)$_config
		);

		// Global config
		define("CONFIG", (array)$config);
        // print_r(CONFIG);

		// Database
		if($config->db) {
			$this->db = new Medoo($config->db);
		}

		// Decompose config
		// $this->theme = $config->theme;
		$this->debug = $config->debug;
		// $this->root = $config->root;
	}

	function service($entity)
	{
		$db = $this->db;
		return new Service($entity, $db);
	}

	function debug($what, $title = "")
	{
		if ($this->debug) {
			// if (isset($_GET['debug']) || $this->debug) {
			debugToConsole($what, $title);
		}
	}

	// Set callback
	function notfound($cb)
	{
		$this->notfound = $cb;
	}

	function listen()
	{
		return $this->_listen($this->router);
	}

	function _listen($router = [], $requestUri = null) {
		// match current request url
		$matches = $router->matches($requestUri);
		// debugToConsole($matches);
		// debugToConsole($this->router);

		// Loop the matches
		foreach ($matches as $match) {
			$request = (object) $match['params'];

			// // Build the $request parameter
			// $request = (object) array(
			// 	'settings'	=> $this->settings,
			// 	'path'		=> $this->current,
			// 	'params'	=> (object) $variables,
			// 	'headers'	=> $this->headers,
			// 	'query'		=> $this->query,
			// 	'cookies'	=> $this->cookies,
			// 	'body'		=> $this->body
			// );

			$response = (object) [
				'body' => ''
			];

			$settings = array(
				'view_engine'	=> '',	// Default template engine
				'views'			=> '', // The path where the templates are
				'allow_php'		=> true, // Allow the execution of PHP within the templates?
				'pretty_json'	=> false, // Make the output of json pretty
				'pretty_print'	=> false, // Make the templates render pretty
				'cache_dir'		=> '/tmp'  // Where the cache of templates should be
			);

			// $resp = $match['target']($request, $response);
			// TODO: Support more than two handlers for the same route.
			$next = function () {
			};

			// Build the response parameter
			// Koa style ?
			// $response = (object)[
			// 	'body'=> ""
			// ];
			// Express Style 
			$response = new Response($settings);

			// Call the handler
			$return = $match['target']($request, $response, $next);

			// IMPROVE Handle nested routes if return is Router
			// $isNestedRoute = is_object($return) && get_class($return) === "Router";
			// if( $isNestedRoute ) {
			// 	$nestedRouter = $return;
			// 	$this->_listen($nestedRouter);
			// }

			// Stop loop on false returns
			if (!$return) break;
		}
	}

	function region($name)
	{
		// Context for render
		$regions = $this->regions;
		$page = $this->page;
		$blocks = $this->blocks;

		$blocksInregion = $regions[$name];
		// debug("Render region: $name");
		foreach ($blocksInregion as $mixed) {
			// $block = $blocks[$blockName];

			$type = ($mixed instanceof Closure) ?
				'function' :
				gettype($mixed);

			// debug($mixed);
			// debug($type);
			switch ($type) {
				case 'object':
					include $mixed->file;
					break;
				case 'string':
					echo $mixed;
					break;
				case 'function':
					echo $mixed($page);
					break;
			}
		}
	}

	function renderFile($file)
	{
		ob_start();
		include $file;
		return ob_get_clean();
	}

	function __toString()
	{
		return $this->renderFile($this->root . "/themes/{$this->theme}/index.php");
	}
}
