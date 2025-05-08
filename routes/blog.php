<?php

require_once __DIR__ . '/../controllers/BlogController.php';

function handleBlogRoutes($uri, $method)
{
  $blogController = new BlogController();

  // GET /blog → danh sách bài viết
  if ($uri === '/blog' && $method === 'GET') {
    $blogController->getAll();
    return true;
  }

  // GET /blog/search?keyword=...
  if (strpos($uri, '/blog/search') === 0 && $method === 'GET') {
    if (isset($_GET['keyword'])) {
      $blogController->search($_GET['keyword']);
      return true;
    }
  }

  // GET /blog/{id}
  if (preg_match('#^/blog/(\d+)$#', $uri, $matches) && $method === 'GET') {
    $blogController->getOne($matches[1]);
    return true;
  }

  // POST /blog → tạo mới
  if ($uri === '/blog/create' && $method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $blogController->create($data);
    return true;
  }

  // PUT /blog/{id} → cập nhật
  if (preg_match('#^/blog/(\d+)$#', $uri, $matches) && $method === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    $data['id'] = $matches[1];
    $blogController->update($data);
    return true;
  }

  // DELETE /blog/{id}
  if (preg_match('#^/blog/(\d+)$#', $uri, $matches) && $method === 'DELETE') {
    $blogController->delete($matches[1]);
    return true;
  }

  return false;
}
