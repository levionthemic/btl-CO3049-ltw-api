<?php

require_once __DIR__ . '/../controllers/RoomController.php';

function handleRoomRoutes($uri, $method)
{
    $roomController = new RoomController();

    $uriParts = explode('/', $uri);
    if ($uriParts[1] != 'rooms' || $uriParts[0] != '') {
        return false;
    }

    // [GET]:API_ROOT/api/rooms
    if ($uri == '/rooms' && $method == 'GET') {
        $roomController->index();
        return true;
    }

    // [POST]:API_ROOT/api/rooms/booking
    if (count($uriParts) == 3 && $uriParts[1] == 'rooms' && $uriParts[2] == 'booking' && $method == 'POST') {
        $roomController->booking();
        return true;
    }

    // [POST]:API_ROOT/api/rooms/send-review
    if (count($uriParts) == 3 && $uriParts[1] == 'rooms' && $uriParts[2] == 'send-review' && $method == 'POST') {
        $roomController->addReview();
        return true;
    }

    // [GET]:API_ROOT/api/rooms/detail/:id
    if (count($uriParts) == 4 && $uriParts[1] == 'rooms' && $uriParts[2] == 'detail' && isset($uriParts[3]) && $method == 'GET') {
        $id = $uriParts[3];
        $roomController->getDetail($id);
        return true;
    }

    // [GET]:API_ROOT/api/rooms/get-booking/:id
    if (count($uriParts) == 4 && $uriParts[1] == 'rooms' && $uriParts[2] == 'get-booking' && isset($uriParts[3]) && $method == 'GET') {
        $userId = $uriParts[3];
        $roomController->getRoomBooking($userId);
        return true;
    }

    // [GET]:API_ROOT/api/rooms/get-bookings
    if (count($uriParts) == 3 && $uriParts[1] == 'rooms' && $uriParts[2] == 'get-bookings' && $method == 'GET') {
        $roomController->getAllBookings();
        return true;
    }

    // [DELETE]:API_ROOT/api/rooms/delete/:id
    if (count($uriParts) == 4 && $uriParts[1] == 'rooms' && $uriParts[2] == 'delete' && isset($uriParts[3]) && $method == 'DELETE') {
        $id = $uriParts[3];
        $roomController->deleteRoom($id);
        return true;
    }

    // [DELETE]:API_ROOT/api/rooms/delete-booking/:id
    if (count($uriParts) == 4 && $uriParts[1] == 'rooms' && $uriParts[2] == 'delete-booking' && isset($uriParts[3]) && $method == 'DELETE') {
        $id = $uriParts[3];
        $roomController->deleteBooking($id);
        return true;
    }

    // [POST]:API_ROOT/api/rooms/update-booking
    if (count($uriParts) == 3 && $uriParts[1] == 'rooms' && $uriParts[2] == 'update-booking' && $method == 'POST') {
        $roomController->updateRoomBooking();
        return true;
    }

    //[POST]:API_ROOT/api/rooms/update-room/:id
    if (count($uriParts) == 4 && $uriParts[1] == 'rooms' && $uriParts[2] == 'update-room' && isset($uriParts[3]) && $method == 'POST') {
        $id = $uriParts[3];
        $roomController->updateRoom($id);
        return true;
    }

    //[POST]:API_ROOT/api/rooms/create-room
    if (count($uriParts) == 3 && $uriParts[1] == 'rooms' && $uriParts[2] == 'create-room'  && $method == 'POST') {
        $roomController->createRoom();
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