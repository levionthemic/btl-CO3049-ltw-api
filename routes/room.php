<?php

require_once __DIR__ . '/../controllers/RoomController.php';

function handleRoomRoutes($uri, $method)
{
    $roomController = new RoomController();

    if ($uri == '/rooms' && $method == 'GET') {
        $roomController->index();
        return true;
    }

    // if ($uri == '/rooms' && $method == 'POST') {
    //     $roomController->store();
    //     return true;
    // }

    // if (preg_match('/^\/rooms\/(\d+)$/', $uri, $matches) && $method == 'GET') {
    //     $roomController->show($matches[1]);
    //     return true;
    // }

    // if (preg_match('/^\/rooms\/(\d+)$/', $uri, $matches) && $method == 'PUT') {
    //     $roomController->update($matches[1]);
    //     return true;
    // }

    // if (preg_match('/^\/rooms\/(\d+)$/', $uri, $matches) && $method == 'DELETE') {
    //     $roomController->destroy($matches[1]);
    //     return true;
    // }

    return false;
}
?>