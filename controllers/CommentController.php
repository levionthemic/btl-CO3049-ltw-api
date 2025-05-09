<?php
require_once __DIR__ . '/../services/CommentService.php';

class CommentController {
    private $commentservice;

    public function __construct() {
        $this->commentservice = new CommentService();
        header('Content-Type: application/json');
    }

    public function getByNews($news_id) {
        try {
            $result = $this->commentservice->getByNews($news_id);
            echo json_encode(['status' => 'success', 'data' => $result]);
        } catch (Exception $e) {
            $this->responseException($e);
        }
    }

    public function create($data) {
        try {
            $this->commentservice->create($data);
            echo json_encode(['status' => 'success', 'message' => 'Comment added']);
        } catch (Exception $e) {
            $this->responseException($e);
        }
    }

    public function delete($id) {
        try {
            $this->commentservice->delete($id);
            echo json_encode(['status' => 'success', 'message' => 'Comment deleted']);
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
