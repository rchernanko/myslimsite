<?php

/*
 * An example of a route...
 * TODO read up on routes!
 *
 * Basic anatomy of a route:
 *
 * 1) $app = we are building up the app object (which is initialised in index.php)
 * 2) the method 'get' = the get means that we are reading the url, we are getting from the url. david says there are a few ways to do this...
 * 3) '/api/books' = the url pattern we are looking for
 * 4) Our action...what are we going to do when someone goes to the url above ('/api/books')
 */
$app->get('/api/books', function () {
    echo 'Welcome to books';
});


//So I've now gone back into the index.php file and 'require_once' this file...
//So now when i type myslimsite/api/books... the above echo is printed out :-)


/*
 * Just as a demo, there doesn't have to be a file for us to reference it in here...
 */
$app->get('/banana', function () {
    echo 'Welcome to banana';
});

//So if i type myslimsite/banana, 'welcome to banana' will be printed out