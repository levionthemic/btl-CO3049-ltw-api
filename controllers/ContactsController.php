<?php

require_once __DIR__ . '/../models/ContactsModel.php';

class ContactsController
{
  private $contactsModel;

  public function __construct()
  {
    $this->contactsModel = new ContactsModel();
    $this->setupCORS();
  }


  private function setupCORS()
  {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    header('Access-Control-Allow-Credentials: true');

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
      header('HTTP/1.1 200 OK');
      exit();
    }
  }

  public function getAllContacts()
  {
    $contacts = $this->contactsModel->getAllContacts();
    echo json_encode($contacts);
  }

  public function addContact()
  {
    header('Content-Type: application/json');

    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['name']) || !isset($data['email']) || !isset($data['message'])) {
      http_response_code(400);
      echo json_encode([
        'status' => 'error',
        'message' => 'Missing required fields: name, email, message'
      ]);
      return;
    }

    try {
      // Insert into database
      $result = $this->contactsModel->addContact($data);

      if ($result) {
        http_response_code(201);
        echo json_encode([
          'status' => 'success',
          'message' => 'Contact created successfully'
        ]);
      } else {
        throw new Exception('Database insertion failed');
      }
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'status' => 'error',
        'message' => 'Failed to create contact'
      ]);
    }
  }

  public function markAsRead($id)
  {
    $result = $this->contactsModel->markAsRead($id);
    echo json_encode($result);
  }

  public function markAsResponded($id)
  {
    $result = $this->contactsModel->markAsResponded($id);
    echo json_encode($result);
  }

  public function getPaginatedContacts($page, $limit)
  {
    $contacts = $this->contactsModel->getPaginatedContacts($page, $limit);
    $totalContacts = $this->contactsModel->getTotalContactsCount(); 
    echo json_encode([
      'contacts' => $contacts,
      'total_contacts' => $totalContacts,
    ]);
  }

  public function deleteContact($id)
  {
    $result = $this->contactsModel->deleteContact($id);
    if ($result) {
      http_response_code(200);
      echo json_encode([
        'status' => 'success',
        'message' => 'Contact deleted successfully'
      ]);
    } else {
      http_response_code(500);
      echo json_encode([
        'status' => 'error',
        'message' => 'Failed to delete contact'
      ]);
    }
  }
}
