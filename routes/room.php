<?php

require_once __DIR__ . '/../controllers/RoomController.php';

function handleRoomRoutes($uri, $method)
{
    $roomController = new RoomController();

    $uriParts = explode('/', $uri);
    if ($uriParts[1] != 'rooms' || $uriParts[0] != ''){
        return false;
    }
    if ($uri == '/rooms' && $method == 'GET') {
        $roomController->index();
        return true;
    }

    if (count($uriParts) == 4 && $uriParts[1] == 'rooms' && $uriParts[2] == 'detail' && isset($uriParts[3]) && $method == 'GET') {
        // Uncomment the following line when the store method is implemented
        $id = $uriParts[3];
        $roomController->getDetail($id);
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