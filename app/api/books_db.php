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

//HOW TO POST DATA AND CREATE A NEW RECORD
$app->post('/api/books_db', function (Request $request) {

    /*

    Prior to me adding to the db, this is what i had up and running...
    Basically echo'd something onto my browser when i hit the books_db endpoint

    $my_name = $_POST['my_name'];
    echo "hello " . $my_name;

    //I've now used POSTMAN and got it to say 'hello richard'
    //See image i've saved in screenshots folder entitled 'postman'

    //David mentions that the use of $_POST and $_GET is quite retro
    //There is another way to do this - comes with the SLIM framework

    $my_name = $request->getParsedBody()['my_name']; //TODO read up on this - PSR7
    echo "hello again new way " . $my_name;

    */

    //Now, let's get this connected to my DB and let's use PREPARED STATEMENTS

    global $mysqli;

    $query = "INSERT INTO `books` (`book_title`, `author`, `amazon_url`) VALUES (?,?,?)";

    //3 question marks = these are parameter markers. used for variable binding

    $stmt = $mysqli->prepare($query);

    $stmt->bind_param("sss", $book_title, $author, $amazon_url);
    //each of these s's stands for string.
    //I'm saying =  "i'm going to be adding 3 variables and i am expecting 3 strings"
    //my three variables are book_title, author and amazon_url

    $book_title = $request->getParsedBody()['book_title'];
    $author = $request->getParsedBody()['author'];
    $amazon_url = $request->getParsedBody()['amazon_url'];

    $stmt->execute();

    //When i use postman to post this (with variables in the body), a new record is then put into the database
    //Awesome! See image entitled 'postman_post' in screenshots

    //So what are the benefits of using prepared statements (vs $_POST)
    //1 - Speed - it's very superfast...query is already in computer memory and is simlpy adding new parameters
    //TODO video stopped - look this up

});


//HOW TO UPDATE A RECORD ON THE DATABASE
$app->put('/api/books_db/{id}', function (Request $request) {

    /*
     * Prior to me linking this up to the db, this is what this looked like. when i did a PUT in postman, it
     * displayed the echo'd text

    $my_name = $request->getParsedBody()['my_name'];
    echo "hello this is a put request with " . $my_name;

    //works in postman :-) but needed to change content type to x-www-form-urlencoded

     */

    //Now let's hook it up to the db...

    global $mysqli;

    $id = $request->getAttribute('id');

    $query = "UPDATE `books` SET `book_title` = ?, `author` = ?, `amazon_url` = ? WHERE `books`.`id` = $id";

    $stmt = $mysqli->prepare($query);

    $stmt->bind_param("sss", $book_title, $author, $amazon_url);

    $book_title = $request->getParsedBody()['book_title'];
    $author = $request->getParsedBody()['author'];
    $amazon_url = $request->getParsedBody()['amazon_url'];

    $stmt->execute();

    //When i use postman to PUT this (with variables in the body), the record (with the corresponding id) is updated
    //e.g. http://myslimsite/api/books_db/7 (plus body values)
    //Awesome! See image entitled 'postman_put' in screenshots

});

//HOW TO DELETE A RECORD FROm THE DATABASE
$app->delete('/api/books_db/{id}', function (Request $request) {

    global $mysqli;

    $id = $request->getAttribute('id');
    $query = "delete from books where id = $id";
    $result = $mysqli->query($query);

    //amazing - do a delete in postman - http://myslimsite/api/books_db/4
    //see image entitled 'postman_delete' screenshote

});