<?php
require_once __DIR__ . '/../services/BlogService.php';

class BlogController {
    private $blogservice;

    public function __construct() {
        $this->blogservice = new BlogService();
        header('Content-Type: application/json');
    }

    public function getAll() {
        try {
            $result = $this->blogservice->getAll();
            echo json_encode(['status' => 'success', 'data' => $result]);
        } catch (Exception $e) {
            $this->responseException($e);
        }
    }

    public function getOne($id) {
        try {
            $result = $this->blogservice->getById($id);
            echo json_encode(['status' => 'success', 'data' => $result]);
        } catch (Exception $e) {
            $this->responseException($e);
        }
    }

    public function search($keyword) {
        try {
            $result = $this->blogservice->search($keyword);
            echo json_encode(['status' => 'success', 'data' => $result]);
        } catch (Exception $e) {
            $this->responseException($e);
        }
    }

    public function create($data, $imagePath = null) {
        try {
            $this->blogservice->createOne($data, $imagePath);
            echo json_encode(['status' => 'success', 'message' => 'Post created']);
        } catch (Exception $e) {
            $this->responseException($e);
        }
    }

    public function update($data, $imagePath = null) {
        try {
            $this->blogservice->updatePost($data, $imagePath);
            echo json_encode(['status' => 'success', 'message' => 'Post updated']);
        } catch (Exception $e) {
            $this->responseException($e);
        }
    }

    public function delete($id) {
        try {
            $this->blogservice->deletePost($id);
            echo json_encode(['status' => 'success', 'message' => 'Post deleted']);
        } catch (Exception $e) {
            $this->responseException($e);
        }
    }

    public function responseError($msg, $code = 400) {
        http_response_code($code);
        echo json_encode(['status' => 'error', 'message' => $msg]);
    }

    public function responseException($e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}