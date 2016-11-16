<?php

	/**
		minimal example of URL to class::method routing without magic (no regex, no closures)
		just run the build in webserver with `php -S localhost:999 router.php` and open your browser at `localhost:999`
		@see http://php.net/manual/en/features.commandline.webserver.php
		use 'mod_rewrite' for apache webserver, e.g.
			RewriteEngine on
			RewriteCond %{REQUEST_FILENAME} !-d
			RewriteCond %{REQUEST_FILENAME} !-f
			RewriteRule . /index.php [L]
	*/



	if (preg_match('/\.(?:html)$/', $_SERVER['REQUEST_URI'])) return false; // preserve html form file



	class Router {

		private $fallback = '';
		private $routes = [];

		/* set fallback */
		public function __construct($fallback){
			$this->fallback = $fallback;
		}

		/* store routes */
		public function route($route){
			$this->routes[] = $route;
		}

		/* dispatch all routes against given url */
		public function run()
		{
			foreach($this->routes as $idx => $route){
				if(
					    $route['action'] == $_SERVER['REQUEST_URI']
					and $route['method'] == $_SERVER['REQUEST_METHOD']
				){
					call_user_func($route['call']);
					$done = true;
				}
			}
			if(!isset($done)) call_user_func($this->fallback); // no routes found for request
		}

	}

	class Index {

		public static function startpage(){
			echo "Welcome to the index! \n";
		}

	}

	class Login {

		public static function validate(){
			echo "Yes sir, you want to login with {$_POST['username']}/{$_POST['password']} \n";
		}

	}



	header('content-type:text/plain');

	/* add fallback controller/method if no other request matches */
	$Router = new Router('Index::startpage');

	/* add routes */
	$Router->route([
		'action' => '/login.php',
		'method' => 'POST',
		'call' => 'Login::validate'
	]);

	/* dispatch request and call routing target */
	$Router->run();
