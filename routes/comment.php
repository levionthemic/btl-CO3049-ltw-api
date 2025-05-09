<?php

require_once __DIR__ . '/../controllers/CommentController.php';

function handleCommentRoutes($uri, $method)
{
  $commentController = new CommentController();

  // Lấy tất cả bình luận theo bài viết
  if (preg_match('#^/blog/(\d+)/comments$#', $uri, $matches) && $method === 'GET') {
    $newsId = $matches[1];
    $commentController->getByNews($newsId);
    return true;
  }

  // Thêm bình luận cho bài viết
  if (preg_match('#^/blog/(\d+)/comments$#', $uri, $matches) && $method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $data['news_id'] = (int)$matches[1];
    $commentController->create($data);
    return true;
  }

  // Xoá bình luận theo id
  if (preg_match('#^/comments/(\d+)$#', $uri, $matches) && $method === 'DELETE') {
    $commentId = $matches[1];
    $commentController->delete($commentId);
    return true;
  }

  return false;
}
