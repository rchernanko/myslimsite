<?php

use \Psr\Http\Message\ServerRequestInterface as Request; //Taken from slim framework site...
require_once('dbconnect.php');

/*
* What we will do now is hook up this script to read from a db table and then display the response as JSON
*
* Separately, I've created a database and table in phpmyadmin
* Database = myslimsite
* Table = books
*
* In order to connect to the db, we will not use an external library / framework such as RedBean or Doctrine.
* Instead, we will go with a something that comes with the standard PHP libraries - MySQLi or PDO
*
* We will use MySQLi - works specifically with MySQL db's
*
* First, we will add a new file in the app/api folder called dbconnect.php and make a connection to the db
*
* Now let's continue with the below...
*/


$app->get('/api/books_db', function () {

    //Calling the database connection (that we have defined in dbconnect.php)
    global $mysqli;

    $query = "select * from books order by id";

    //Making a query via the db connection and storing the result in a variable
    $result = $mysqli->query($query);

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
        //above [] adds every new row to the data associative array
    }

    if (isset($data)) {
        header('Content-Type: application/json');
        //TODO need to read a little bit more about why we need this here - but its good practice
        echo json_encode($data);
        //display all rows in the db table in json :-)
    }

    //Amazing! Now when I go to http://myslimsite/api/books_db...
    //The entries in the db are displayed in JSON
});

//INTERESTING...TODO read more about the REQUEST and RESPONSE

//Now let's display a single row from the db table (by passing in an id)
//We need to use a request variable...see the slim framework site for more info TODO
$app->get('/api/books_db/{id}', function (Request $request) {

    $id = $request->getAttribute('id');

    //Calling the database connection (that we have defined in dbconnect.php)
    global $mysqli;

    $query = "select * from books where id=$id";
    $result = $mysqli->query($query);

    $data[] = $result->fetch_assoc();
    header('Content-Type: application/json');
    echo json_encode($data);
});

//How to post a record to the new db
$app->post('/api/books_db', function (Request $request) {

    $my_name = $_POST['my_name'];
    echo "hello " . $my_name;

    //I've now used POSTMAN and got it to say 'hello richard'
    //See image i've saved in screenshots folder entitled 'postman'

    //David mentions that the use of $_POST and $_GET is quite retro
    //There is another way to do this - comes with the SLIM framework

    $my_name = $request->getParsedBody()['my_name']; //TODO read up on this
    echo "hello again new way " . $my_name;
});


//How to update a record - use put
$app->put('/api/books_db', function () {

});