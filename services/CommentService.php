<?php
require_once __DIR__ . '/../models/Comment.php';

class CommentService {
  private $commentModel;

  public function __construct() {
    $this->commentModel = new Comment();
  }

  public function getByNews($newsId) {
    try {
      $response = $this->commentModel->getByNews($newsId);
      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function create($data) {
    try {
      $response = $this->commentModel->createOne($data);
      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }
  
  public function delete($id) {
    try {
        return $this->commentModel->deleteOne($id);
    } catch (Exception $e) {
        throw $e;
    }
}
}
