<?php
require_once __DIR__ . '/../services/RoomService.php';

class RoomController
{
    private $roomService;

    public function __construct()
    {
        $this->roomService = new RoomService();
    }

    public function index()
    {
        try {
            $queryParams = $_GET;
            $rooms = $this->roomService->getAllRooms($queryParams);
            echo json_encode(["status" => "success", "data" => $rooms]);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // public function store()
    // {
    //     try {
    //         $input = json_decode(file_get_contents("php://input"), true);

    //         if (!isset($input['name']) || !isset($input['capacity'])) {
    //             throw new ApiError('Missing information', 406);
    //         }

    //         $response = $this->roomService->createRoom($input);

    //         echo json_encode(["status" => "success", "data" => $response]);
    //     } catch (Exception $e) {
    //         throw new Exception($e->getMessage());
    //     }
    // }

    // public function show($id)
    // {
    //     try {
    //         $room = $this->roomService->getRoomById($id);
    //         echo json_encode(["status" => "success", "data" => $room]);
    //     } catch (Exception $e) {
    //         throw new Exception($e->getMessage());
    //     }
    // }

    // public function update($id)
    // {
    //     try {
    //         $input = json_decode(file_get_contents("php://input"), true);

    //         if (!isset($input['name']) || !isset($input['capacity'])) {
    //             throw new ApiError('Missing information', 406);
    //         }

    //         $response = $this->roomService->updateRoom($id, $input);

    //         echo json_encode(["status" => "success", "data" => $response]);
    //     } catch (Exception $e) {
    //         throw new Exception($e->getMessage());
    //     }
    // }

    // public function destroy($id)
    // {
    //     try {
    //         $this->roomService->deleteRoom($id);
    //         echo json_encode(["status" => "success"]);
    //     } catch (Exception $e) {
    //         throw new Exception($e->getMessage());
    //     }
    // }
}
?>