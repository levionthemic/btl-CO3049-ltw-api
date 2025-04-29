<?php

require_once __DIR__ . '/../models/Faq.php';
require_once __DIR__ . '/../providers/JwtProvider.php';

class FaqService
{
  private $faqModel;

  public function __construct()
  {
    $this->faqModel = new Faq();
  }

  public function getAll()
  {
    try {
      $response = $this->faqModel->getAll();
      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function createOne($data)
  {
    try {
      $response = $this->faqModel->createOne($data);
      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function updateFaq($data)
  {
    try {
      $response = $this->faqModel->updateFaq($data);
      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function deleteFaq($faqId)
  {
    try {
      $response = $this->faqModel->deleteFaq($faqId);
      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }
}
