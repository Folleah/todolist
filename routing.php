<?php

return FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/tasks', [new \App\Controllers\TaskController, 'tasks']);
    $r->addRoute('POST', '/tasks', [new \App\Controllers\TaskController, 'createTask']);
}, [
    'cacheFile' => realpath('./bootstrap/route.cache')
]);