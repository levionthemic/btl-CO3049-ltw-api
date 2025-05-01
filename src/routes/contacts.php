<?php

require_once __DIR__ . '/../controllers/ContactsController.php';

function handleContactsRoutes($uri, $method)
{
    $contactsController = new ContactsController();

    // Routing for contacts
    if (strpos($uri, 'contacts') === 0 && $method === 'GET') {

        $queryParams = [];
        parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $queryParams);
        if (isset($queryParams['page']) && isset($queryParams['limit'])) {
            $page = (int)$queryParams['page'];
            $limit = (int)$queryParams['limit'];
            $contactsController->getPaginatedContacts($page, $limit);
        }
        else {
            $contactsController->getAllContacts();
        }
        // $contactsController->getAllContacts();
        return true;
    }

    if ($uri === 'contacts' && $method === 'POST') {
        $contactsController->addContact();
        return true;
    }

    // Handling routes with an ID (e.g., api/contacts/1)
    $uriParts = explode('/', $uri);
    if (count($uriParts) === 2 &&  $uriParts[0] === 'contacts' && is_numeric($uriParts[1])) {
        $id = $uriParts[1];

        // if ($method === 'GET') {
        //     $contactsController->getContactById($id);
        //     return true;
        // }

        if ($method === 'PUT') {
            $contactsController->markAsRead($id);
            return true;
        }

        // if ($method === 'DELETE') {
        //     $contactsController->deleteContact($id);
        //     return true;
        // }
    }

    if (count($uriParts) === 3 &&  $uriParts[0] === 'contacts' && is_numeric($uriParts[2])) {
        $id = $uriParts[2];

        if ($uriParts[1] === 'read') {
            $contactsController->markAsRead($id);
            return true;
        }

        if ($uriParts[1] === 'responded') {
            $contactsController->markAsResponded($id);
            return true;
        }
    }

    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request',
        'uri' => $uri,
        'method' => $method
    ]);
    http_response_code(404);

    return false;
}