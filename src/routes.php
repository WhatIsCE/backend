<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

define('DEBUG',true);

$app->get('/notes/all/limit/{num}', function($request) {

    require_once('db.php');
    $limit_num = intval($request->getAttribute('num'));
    $querystring = "select * from notes order by `date` DESC LIMIT $limit_num";

    try {
        $results = query($querystring,[],true);

        if (count($results)>0) {
         echo json_encode($results);
        } else {
            echo '{"status":"not found"}';
        }
    }
    catch(Exception $e) {
        if (DEBUG) {
            echo $e->getMessage();
        } else {
            echo '{"status":"error"}';
        }

    }

});

$app->post('/notes/new', function($request){

    require_once('db.php');
    $querystring = "INSERT INTO notes (`author`,`content`,`date`) VALUES (:author,:content,NOW())";
    $note_author = $request->getParsedBody()['author'];
    $note_content = $request->getParsedBody()['content'];
    $validation_key = $request->getParsedBody()['key'];

    //SPECIALKEY is defined in dbconfig.php
    //Didn't use a key database because we don't need more than one client.

    if ($validation_key != SPECIALKEY) {
        die('{"status":"Permission denied"}');
    }
    try {
        query($querystring, [
            'author' => $note_author,
            'content' => $note_content
        ]);

        echo '{"status":"done"}';
    }
    catch(Exception $e) {
        if (DEBUG) {
            echo $e->getMessage();
        } else {
            echo '{"status":"error"}';
        }
    }


});

$app->get('/notes/{note_id}', function($request){
    require_once('db.php');

    $get_id = intval($request->getAttribute('note_id'));
    $querystring = "SELECT * from notes WHERE `id`= :id";

    try {
        $results = query($querystring, [
            "id" => $get_id
        ],true);

        if (count($results)>0) {
         echo json_encode($results);
        } else {
            echo '{"status":"Not Found!"}';
        }
    }
    catch(Exception $e) {
        if (DEBUG) {
            echo $e->getMessage();
        } else {
            echo '{"status":"error"}';
        }
    }


});