<?php

require_once __DIR__ . '/../controllers/BlogController.php';
require_once __DIR__ . '/../config.php'; // File chứa kết nối PDO

function handleBlogRoutes($uri, $method)
{
  global $pdo; // Đảm bảo $pdo có sẵn
  if (!$pdo) {
      http_response_code(500);
      header('Content-Type: application/json');
      echo json_encode([
          'status' => 'error',
          'message' => 'Không thể kết nối database'
      ]);
      error_log('blog.php: Database connection error: $pdo is null');
      return true;
  }

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
            if (!mkdir($uploadDir, 0755, true)) {
                http_response_code(500);
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Không thể tạo thư mục Uploads'
                ]);
                error_log('blog.php: Failed to create directory: ' . $uploadDir);
                return true;
            }
        }

        $fileName = uniqid() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '', basename($_FILES['image']['name']));
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
            $imagePath = '/Uploads/' . $fileName;
        } else {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Không thể lưu file ảnh'
            ]);
            error_log('blog.php: Failed to move uploaded file to: ' . $filePath);
            return true;
        }
    }

    $blogController->create($data, $imagePath);
    return true;
  }

  // PUT /blog/{id} → cập nhật
  if (preg_match('#^/blog/(\d+)$#', $uri, $matches) && $method === 'PUT') {
    // Log dữ liệu nhận được
    error_log('PUT /blog/' . $matches[1] . ' - POST data: ' . json_encode($_POST));
    error_log('PUT /blog/' . $matches[1] . ' - FILES data: ' . json_encode($_FILES));

    // Lấy dữ liệu hiện tại từ database
    try {
        $stmt = $pdo->prepare("SELECT title, content, image FROM news WHERE id = ?");
        $stmt->execute([$matches[1]]);
        $current = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$current) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Bài viết không tồn tại'
            ]);
            return true;
        }
    } catch (Exception $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'Lỗi khi truy vấn database'
        ]);
        error_log('blog.php: Database query error: ' . $e->getMessage());
        return true;
    }

    // Chỉ cập nhật các trường được gửi, giữ giá trị cũ nếu không gửi
    $data = [
        'title' => isset($_POST['title']) && $_POST['title'] !== '' ? $_POST['title'] : $current['title'],
        'content' => isset($_POST['content']) && $_POST['content'] !== '' ? $_POST['content'] : $current['content'],
        'id' => $matches[1]
    ];

    $imagePath = $current['image']; // Giữ ảnh cũ mặc định
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
            if (!mkdir($uploadDir, 0755, true)) {
                http_response_code(500);
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Không thể tạo thư mục Uploads'
                ]);
                error_log('blog.php: Failed to create directory: ' . $uploadDir);
                return true;
            }
        }

        $fileName = uniqid() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '', basename($_FILES['image']['name']));
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
            $imagePath = '/Uploads/' . $fileName;
            // Xóa ảnh cũ nếu tồn tại
            if ($current['image'] && file_exists(__DIR__ . '/../' . $current['image'])) {
                unlink(__DIR__ . '/../' . $current['image']);
            }
            error_log('blog.php: Uploaded image: ' . $imagePath);
        } else {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Không thể lưu file ảnh'
            ]);
            error_log('blog.php: Failed to move uploaded file to: ' . $filePath);
            return true;
        }
    }

    try {
        $blogController->update($data, $imagePath);
    } catch (Exception $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'Lỗi khi cập nhật bài viết: ' . $e->getMessage()
        ]);
        error_log('blog.php: Update error: ' . $e->getMessage());
        return true;
    }
    return true;
  }

  // DELETE /blog/{id}
  if (preg_match('#^/blog/(\d+)$#', $uri, $matches) && $method === 'DELETE') {
    $blogController->delete($matches[1]);
    return true;
  }

  return false;
}