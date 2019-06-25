<?php 
use FastRoute\RouteCollector;
use function ICanBoogie\pluralize;

/**
 * Creates a CRUD route
 * 
 * @param RouteCollector Routing context
 * @param string Resource name
 * @param mixed Controller (could be class or fully-qualified classname)
 */
function crud(RouteCollector $r, $resource, $controller) {
    $resources = pluralize($resource);

    $r->addRoute('GET', "/{$resources}", $controller);
    $r->addRoute('POST', "/{$resources}", [$controller, 'create']);

    // TODO investigate PATCH / PUT requests here
    $r->addRoute('GET', "/{$resources}/{id:\d+}", [$controller, 'show']);
    $r->addRoute('POST', "/{$resources}/{id:\d+}", [$controller, 'update']);
    $r->addRoute('GET', "/{$resources}/{id:\d+}", [$controller, 'delete']);
}

return FastRoute\simpleDispatcher(function (RouteCollector $r) {
    
    $r->addRoute('GET', '/posts/{slug:[0-9a-z\.-]+}', ['Blog\Controller\PostController', 'show']); 

    $r->addGroup('/admin', function (RouteCollector $r) {
        // We do not want use slugs here
        crud($r, 'post', 'Blog\Controller\Admin\PostController');
    });

    // REST-ified login URL-s. 
    $r->get('/login', 'Blog\Controller\SessionController');
    $r->post('/login', ['Blog\Controller\SessionController', 'create']);
    $r->post('/logout', ['Blog\Controller\SessionController', 'delete']);
});
