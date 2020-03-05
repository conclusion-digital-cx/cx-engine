<?php

/**
 * This class is called automatically when handling a request and shouldn't be called directly
 */

class Response
{
	/**
	 * An array of headers to be sent
	 * @var array
	 */
	private $headers;

	/**
	 * The settings of the instance
	 * @var array
	 */
	private $settings;

	/**
	 * Have we sent cookies in this response?
	 * @var boolean
	 */
	private $cookies = false;

	/**
	 * Variables avaible within the entire instance (@see \Express\Express)
	 * @var stdClass
	 */
	private $locals;

	/**
	 * An instance of the view engine
	 */
	private $engine;

	/**
	 * Regex to check if the file has the view extension
	 * @var string
	 */
	const REGEX_EXT = '/\.(mustache|pug|jade)$/i';

	/**
	 * Constructor
	 *
	 * @param array The default express settings
	 * @param stdClass An object with the app locals (@see \Express\Express)
	 * @return void
	 */
	public function __construct($settings = array(), $locals = null)
	{
		$this->headers = array();
		$this->settings = $settings;

		if (!$locals) {
			$this->locals = new \stdClass;
		}

		// if (in_array($this->settings['view_engine'], array('jade','pug')))
		// {
		// 	$this->engine = new Jade($this->settings['cache_dir'], $this->settings['pretty_print']);
		// }
		// elseif ($this->settings['view_engine'] == 'mustache')
		// {
		// 	$this->engine = new \Mustache_Engine();
		// }
	}

	/**
	 * Sets the status code of the response
	 *
	 * @param int The HTTP code
	 * @return Response
	 */
	public function status($code)
	{
		http_response_code($code);

		return $this;
	}

	/**
	 * Send a response with JSON
	 *
	 * @param array The content to send
	 * @return void
	 */
	public function json($body)
	{
		$this->header("Content-Type", "application/json");
		$this->headers();

		if ($this->settings['pretty_json']) {
			echo json_encode($body, JSON_PRETTY_PRINT);
		} else {
			echo json_encode($body);
		}
	}

	/**
	 * A bind for the setcookie PHP func. This allows using only some parameters and not in order.
	 *
	 * @param string Name of the cookie
	 * @param string Value of the cookie
	 * @param array Cookie options
	 * @return void
	 */
	public function cookie($name, $value, $options = array())
	{
		$this->cookies = true;

		// If not defined, create the cookie for this session
		$expire = 0;

		// Use the root of the app as a default path
		$path = '/';

		// Domain of the cookie
		$domain = '';

		// Transmit only with https?
		$secure = false;

		// Prevent js from accesing the cookie
		$httpOnly = false;

		// Replace the settings with the options received
		if (!empty($options)) {
			extract($options);
		}

		setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
	}

	/**
	 * Removes a cookie
	 *
	 * @param string Name of the cookie
	 * @return void
	 */
	public function clearCookie($name, $settings = array())
	{
		/**
		 * For more info about $settings
		 * @see $this->cookie()
		 */
		$settings['expire'] = time() - 60 * 60 * 24 * 365;

		$this->cookie($name, '', $settings);
	}

	/**
	 * Sends an attachment to force the download of a file
	 *
	 * @param string Path to the file
	 * @param string Name of the file
	 * @return void
	 */
	public function download($path, $name = '')
	{
		// Clear the existing headers
		$this->headers = array(
			'Content-Type'				=> 'application/octet-stream',
			'Content-Transfer-Encoding'	=> 'Binary',
			'Content-disposition'		=> 'attachment'
		);

		if ($name != '') {
			$this->header('Content-disposition', 'attachment; filename="' . $name . '"');
		}

		// Send headers
		$this->headers();

		// Send content
		die(readfile($path));
	}

	/**
	 * Render a template using the configured view engine
	 *
	 * @param string Path of the template
	 * @param array Variables to be put into the view
	 * @return void
	 */
	public function render($path, $regions = array(), $return = false)
	{
		// if ($this->settings['view_engine'] == '')
		// {
		// 	throw new \Exception("There is no engine configured for this view");
		// }

		// Path to the template file
		// $view = $this->solvePath($this->settings['views'].'/'.$path);

		// debugToConsole($regions);

		/**
		 * Template Helpers
		 */
		$asset = function ($dir = '') {
			return str_replace(APP, "", $dir);
		};
				
		$service = function ($entity = '') {
			$cx = getCx();
			return new Service($entity, $cx->db);
		};

		// TODO add closure support
		$region = function ($name) use ($regions) {
			$scope = isset($regions[$name]) ? $regions[$name] : null;
			// echo $name;
			// getCx()->debug($scope);

			if(is_string($scope)) {
				echo $scope;
			} else {
				$type = $scope[0];
				// getCx()->debug($type);

				// Helpers
				$service = function ($entity = '') {
					$cx = getCx();
					return new Service($entity, $cx->db);
				};
				$asset = function ($dir = '') {
					return str_replace(APP, "", $dir);
				};

				// ['file' => 'view.php']
				if($scope['file']) {
					include $scope['file'];
				}

				// ['file', 'view.php']
				if($type === 'file') {
					include $scope[1];
				}
				echo isset($scope[$name]) ? $scope[$name] : '';
			}
		};
 
		// Include layout
		include $path;
		// echo $view;

		// Stop everything else
		exit;
	}


	/**
	 * Redirects to a location using Location header
	 *
	 * @param string URL
	 * @return void
	 */
	public function location($url)
	{
		header('Location: ' . $url);

		// Stop once redirected
		exit;
	}

	/**
	 * Redirects to a location using a http redirect
	 *
	 * @param string URL
	 * @param bool Is a permanent redirect?
	 * @return void
	 */
	public function redirect($url, $permanent = false)
	{
		$code = ($permanent) ? 301 : 302;

		header('Location: ' . $url, true, $code);

		// Stop once redirected
		exit;
	}

	/**
	 * Send the response headers
	 *
	 * @return void
	 */
	private function headers()
	{
		if (headers_sent() && !$this->cookies) {
			return;
		}

		foreach ($this->headers as $header => $content) {
			header($header . ': ' . $content);
		}
	}

	/**
	 * Sets a header
	 *
	 * @param string header
	 * @param string content
	 * @return void
	 */
	public function header($header, $content)
	{
		$this->headers[$header] = $content;
	}

	/**
	 * Send a response with a String
	 *
	 * @param string Response
	 * @return void
	 */
	public function send($body)
	{
		$this->headers();

		echo $body;
	}
}
