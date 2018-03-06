# Router classs that can utilize HTTP-method and URL to handle PHP controllers

```php
/**
* minimal example of URL to class::method routing without magic (no regex, no closures)
* just run the build in webserver with `php -S localhost:999 router.php` and open your browser at `localhost:999`
* @see http://php.net/manual/en/features.commandline.webserver.php
* use 'mod_rewrite' for apache webserver, e.g.
*    RewriteEngine on
*    RewriteCond %{REQUEST_FILENAME} !-d
*    RewriteCond %{REQUEST_FILENAME} !-f
*    RewriteRule . /index.php [L]
*/

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
```