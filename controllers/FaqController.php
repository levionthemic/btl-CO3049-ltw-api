<?php

require_once __DIR__ . '/../services/FaqService.php';

class FaqController
{
  private $faqService;

  public function __construct()
  {
    $this->faqService = new FaqService();
  }

  public function getAll()
  {
    try {
      $result = $this->faqService->getAll();
      echo json_encode(["status" => "success", "data" => $result]);
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function createOne()
  {
    header('Content-Type: application/json; charset=utf-8');

    try {
      $input = json_decode(file_get_contents("php://input"), true);

      if (!isset($input['question']) || !isset($input['answer'])) {
        throw new ApiError('Missing information', 406);
      }
      
      $result = $this->faqService->createOne($input);
      echo json_encode(["status" => "success"]);
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function updateFaq()
  {
    header('Content-Type: application/json; charset=utf-8');

    try {
      $input = json_decode(file_get_contents("php://input"), true);

      if (!isset($input['id']) || !isset($input['question']) || !isset($input['answer'])) {
        throw new ApiError('Missing information', 406);
      }

      $result = $this->faqService->updateFaq($input);
      echo json_encode(["status" => "success"]);
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function deleteFaq($faqId)
  {
    try {
      $result = $this->faqService->deleteFaq($faqId);
      echo json_encode(["status" => "success"]);
    } catch (Exception $e) {
      throw $e;
    }
  }
}
