<?php

use PRS\Core\App;
use PRS\Core\Database\Connection;
use PRS\Core\Database\QueryBuilder;

include __DIR__ . '/../vendor/autoload.php';

include __DIR__ . '/helpers.php';

//configur config to always point to config.php
App::bind('config', require __DIR__ .'/../config.php');

/**
 *Bind the Database credentials and connect to the app
 *Bind the requred database file above to 
 *an instance of the connections
 */
session_start();

App::bind('database', new QueryBuilder(
  Connection::make(App::get('config')['sqlite'])
));

//DB()->selectAll('users');