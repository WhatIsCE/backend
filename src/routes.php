<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->get('/notes/all/limit/{num}', function() {

    require_once('db.php');
    $query = "select * from library order by book_id";
    $result = $connection->query($query);
    while ($row = $result->fetch_assoc()){
        $data[] = $row;
    }
    echo json_encode($data);

});

$app->post('/notes/new', function($request){

    require_once('db.php');
    $get_id = $request->getAttribute('book_id');
    $query = "INSERT INTO notes SET book_name = ?, book_isbn = ?, book_category = ? WHERE book_id = $get_id";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("sss",$book_name,$book_isbn,$book_category);
    $book_name = $request->getParsedBody()['book_name'];
    $book_isbn = $request->getParsedBody()['book_isbn'];
    $book_category = $request->getParsedBody()['book_category'];
    $stmt->execute();

});

$app->get('/notes/{note_id}', function($request){
    require_once('db.php');

    $get_id = $request->getAttribute('note_id');
    $querystring = "SELECT * from notes WHERE `id`= :id";

    $results = query($query, [
        "id" => $get_id
    ],true);

    if (count($results)>0) {
        echo json_encode($results);
    } else {
        echo '{"error":"ERROR"}';
    }

});