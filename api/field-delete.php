
<?php
// required headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

require_once __DIR__.'./../Services/DB/SubscriberCRUD.php';
require_once __DIR__.'./../Services/DB/FieldsCRUD.php';

$field = json_decode(file_get_contents('php://input'));

if (empty($field->field_id) && empty($field->title)) {
    http_response_code(404);
    $result = new \stdClass();

    $result->status = 'input_error';
    $result->message = 'Missing field ID or title';
} else {
    if (!empty($field->field_id)) {
        $result = deleteField($field->field_id);
    } else if (!empty($field->title)) {
        $result = deleteFieldByTitle($field->title);
    }

    $result = json_decode($result);
    if ('success' === $result->status) {
        http_response_code(200);
    } elseif ('error' === $result->status || 'db_error' === $result->status) {
        http_response_code(503);
    }
}

echo json_encode($result);
