<?php

require_once __DIR__ . '/../models/Blog.php';

class BlogService {
  private $blogModel;

  public function __construct() {
    $this->blogModel = new Blog();
  }

  public function getAll() {
    try {
      $response = $this->blogModel->getAll();
      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function getById($id) {
    try {
      $response = $this->blogModel->getById($id);
      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function search($keyword) {
    try {
      $response = $this->blogModel->search($keyword);
      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function createOne($data, $imagePath = null) {
    try {
      $response = $this->blogModel->createOne($data, $imagePath);
      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function updatePost($data, $imagePath = null) {
    try {
      $response = $this->blogModel->updatePost($data, $imagePath);
      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function deletePost($id) {
    try {
      $response = $this->blogModel->deletePost($id);
      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }
}