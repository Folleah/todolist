<?php declare(strict_types=1);

namespace App;

use App\Core\Config;
use App\Core\Request;
use App\Core\Router;
use FastRoute\Dispatcher;

final class Bootstrap
{
//    private $config;
    private $router;

    public function __construct()
    {
//        $this->config = Config::instance($config);
//        $this->router = new Router();
    }

    /**
     * Run framework
     */
    public function run() : void
    {
        $routeDispatcher = require_once(sprintf('%s/%s', realpath('./'), 'routing.php'));
        $request = new Request();
        $routeInfo = $routeDispatcher->dispatch($request->method(), $request->path());

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                // ... 404 Not Found
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                $handler($request);
                break;
        }
    }
}