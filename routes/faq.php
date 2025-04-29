<?php

require_once __DIR__ . '/../controllers/FaqController.php';

function handleFaqRoutes($uri, $method)
{
  $faqController = new FaqController();

  if ($uri === '/faq' && $method === 'GET') {
    $faqController->getAll();
    return true;
  }

  if ($uri === '/faq/add' && $method === 'POST') {
    $faqController->createOne();
    return true;
  }

  if ($uri === '/faq/edit' && $method === 'PUT') {
    $faqController->updateFaq();
    return true;
  }

  if (preg_match('/faq\/delete\/(\d+)/', $uri, $matches) && $method === 'DELETE') {
    $faqId = $matches[1];
    $faqController->deleteFaq($faqId);
    return true;
  }

  return false;
}
