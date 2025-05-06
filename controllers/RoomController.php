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

    public function getDetail($id)
    {
        try {
            $room = $this->roomService->getDetail($id);
            if (!$room) {
                throw new Exception('Room not found', 404);
            }
            echo json_encode(["status" => "success", "data" => $room]);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getRoomBooking($userId)
    {
        try {
            $bookings = $this->roomService->getRoomBooking($userId);
            
            echo json_encode(["status" => "success", "data" => $bookings]);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function booking()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $input = json_decode(file_get_contents("php://input"), true);
            if (!isset($input['user_id']) || !isset($input['room_id']) || !isset($input['check_in_date']) || !isset($input['check_out_date']) || !isset($input['guests_count']) || !isset($input['total_price']) || !isset($input['status'])) {
                throw new ApiError('Missing information', 406);
            }

            $response = $this->roomService->booking($input);
            echo json_encode(["status" => "success", "data" => $response]);
        } catch (\Throwable $th) {
            //throw $th;
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