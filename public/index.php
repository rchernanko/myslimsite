<?php

require '../vendor/autoload.php';

$app = new \Slim\App;

require_once('../app/api/books.php');
require_once('../app/api/books_db.php');
require_once('../app/api/users.php');
require_once('../app/api/genres.php');

$app->run();