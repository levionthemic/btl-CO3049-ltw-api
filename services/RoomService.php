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
 
 
}
?>