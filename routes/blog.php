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
    $data = [
        'title' => $_POST['title'] ?? '',
        'content' => $_POST['content'] ?? ''
    ];

    // Xử lý file upload
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        // Kiểm tra định dạng và kích thước
        if (!in_array($_FILES['image']['type'], $allowedTypes)) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Chỉ hỗ trợ định dạng JPEG, PNG hoặc GIF'
            ]);
            return true;
        }
        if ($_FILES['image']['size'] > $maxSize) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Ảnh phải nhỏ hơn 5MB'
            ]);
            return true;
        }

        $uploadDir = __DIR__ . '/../Uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = uniqid() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '', basename($_FILES['image']['name']));
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
            $imagePath = '/Uploads/' . $fileName;
            error_log('blog.php - imagePath after upload: ' . $imagePath);
        } else {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Không thể lưu file ảnh'
            ]);
            return true;
        }
    }

    error_log('blog.php - Before calling BlogController->create - imagePath: ' . var_export($imagePath, true));
    $blogController->create($data, $imagePath);
    return true;
}

  // PUT /blog/{id} → cập nhật
  if (
    preg_match('#^/blog/(\d+)$#', $uri, $matches)
    && $method === 'POST'
    && isset($_POST['_method'])
    && strtoupper($_POST['_method']) === 'PUT'
) {
    $data = [
        'title' => $_POST['title'] ?? '',
        'content' => $_POST['content'] ?? '',
        'status' => $_POST['status'] ?? '',
        'id' => $matches[1]
    ];

    // Xử lý file upload
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        // Kiểm tra định dạng và kích thước
        if (!in_array($_FILES['image']['type'], $allowedTypes)) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Chỉ hỗ trợ định dạng JPEG, PNG hoặc GIF'
            ]);
            return true;
        }
        if ($_FILES['image']['size'] > $maxSize) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Ảnh phải nhỏ hơn 5MB'
            ]);
            return true;
        }

        $uploadDir = __DIR__ . '/../Uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = uniqid() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '', basename($_FILES['image']['name']));
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
            $imagePath = '/Uploads/' . $fileName;
            error_log('blog.php - imagePath after upload: ' . $imagePath);
        } else {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Không thể lưu file ảnh'
            ]);
            return true;
        }
    }

    error_log('blog.php - Before calling BlogController->update - imagePath: ' . var_export($imagePath, true));
    $blogController->update($data, $imagePath);
    return true;
}

  // DELETE /blog/{id}
  if (preg_match('#^/blog/(\d+)$#', $uri, $matches) && $method === 'DELETE') {
    $blogController->delete($matches[1]);
    return true;
  }

  return false;
}