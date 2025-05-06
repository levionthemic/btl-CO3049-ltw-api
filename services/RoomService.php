<?php
require_once __DIR__ . '/../models/RoomModel.php';

class RoomService
{
  private $roomModel;
  public function __construct()
  {
    $this->roomModel = new Room();
  }

  public function getAllRooms($queryParams = [])
  {
    try {
      $rooms = $this->roomModel->getAll($queryParams);
      return $rooms;
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function getDetail($id)
  {
    try {
      $room = $this->roomModel->getOneById($id);
      return $room;
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function getRoomBooking($userId)
  {
    try {
      $bookings = $this->roomModel->getRoomBooking($userId);
      return $bookings;
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
  }
  public function booking($data)
  {
    try {
      $bookingData = [
        "user_id" => $data["user_id"],
        "room_id" => $data["room_id"],
        "check_in_date" => $data["check_in_date"],
        "check_out_date" => $data["check_out_date"],
        "guests_count" => $data["guests_count"],
        "total_price" => $data["total_price"],
        "status" => $data["status"]
      ];

      $response = $this->roomModel->createBooking($bookingData);
      return $response;
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
  }
}
?>