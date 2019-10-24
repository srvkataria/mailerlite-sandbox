
<?php
// required headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__.'./../Services/DB/FieldsCRUD.php';

$field_list = getAllFields();
$field_list = json_decode($field_list);

if ('success' === $field_list->status) {
    http_response_code(200);
} elseif ('error' === $field_list->status) {
    http_response_code(503);
}

echo json_encode($field_list);
