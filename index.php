<?php

use PRS\Core\Router;
use PRS\Core\Request;

//require the main file joining all the parts of the app
require_once __DIR__.'/src/bootstrap.php';

//Try to load the routes, direct the URI and check the request method
 try {

    Router::load('routes.php')->direct(Request::uri(), Request::method());

} catch (\Exception $e) {

    //Instead of catching the exception here we redirect the same to our main error handler
    abort($e->getMessage(), $e->getCode());
}