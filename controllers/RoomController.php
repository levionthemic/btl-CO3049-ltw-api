<?php
require_once __DIR__ . '/../services/RoomService.php';
require_once __DIR__ . '/../utils/validators.php';
require_once __DIR__ . '/../utils/constants.php';
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
            if (!is_numeric($id))
                throw new Exception('Invalid Id', 400);
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
            if (!is_numeric($userId))
                throw new Exception('Invalid Id', 400);

            $bookings = $this->roomService->getRoomBooking($userId);

            echo json_encode(["status" => "success", "data" => $bookings]);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function deleteRoom($id)
    {
        try {
            if (!is_numeric($id))
                throw new Exception('Invalid Id', 400);

            $res = $this->roomService->deleteRoom($id);

            echo json_encode(["status" => "success", "data" => $res]);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function booking()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $input = json_decode(file_get_contents("php://input"), true);
            if (!isset($input['user_id']) || !isset($input['room_id']) || !isset($input['check_in_date']) || !isset($input['check_out_date']) || !isset($input['guests_count']) || !isset($input['total_price']) || !isset($input['status']))
                throw new Exception('Missing information', 406);

            if (!is_numeric($input["user_id"]))
                throw new Exception('Invalid User Id', 400);
            if (!is_numeric($input["room_id"]))
                throw new Exception('Invalid Room Id', 400);
            if (!isValidDate($input["check_in_date"]))
                throw new Exception('Invalid Check-in Date', 400);
            if (!isValidDate($input["check_out_date"]))
                throw new Exception('Invalid Check-out Date', 400);
            if (!is_numeric($input["guests_count"]))
                throw new Exception('Invalid Guests Count', 400);
            if (!is_numeric($input["total_price"]))
                throw new Exception('Invalid Total Price', 400);
            if (!in_array($input["status"], BookingStatus::VALID))
                throw new Exception('Invalid Status', 400);



            $response = $this->roomService->booking($input);
            echo json_encode(["status" => "success", "data" => $response]);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function addReview()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $input = json_decode(file_get_contents("php://input"), true);
            if (!isset($input['user_id']) || !isset($input['room_id']) || !isset($input['content']) || !isset($input['rating'])) {
                throw new Exception('Missing information', 406);
            }

            if (!is_numeric($input["user_id"]))
                throw new Exception('Invalid User Id', 400);
            if (!is_numeric($input["room_id"]))
                throw new Exception('Invalid Room Id', 400);
            if (!is_string($input["content"]))
                throw new Exception('Invalid Review Content', 400);
            if (!is_numeric($input["rating"]))
                throw new Exception('Invalid Review Rating', 400);
            $rating = (int) $input["rating"];  

            if ($rating < 0 || $rating > 5) {
                throw new Exception('Review Rating must be between 0 and 5', 400);
            }

            $response = $this->roomService->addReview($input);
            echo json_encode(["status" => "success", "data" => $response]);
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