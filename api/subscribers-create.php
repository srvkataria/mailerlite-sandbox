
<?php
// required headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

require_once __DIR__.'./../Services/DB/SubscriberCRUD.php';
require_once __DIR__.'./../Services/DB/FieldsCRUD.php';

$subscriber = json_decode(file_get_contents('php://input'));

if (empty($subscriber->email)) {
    http_response_code(404);
    $result = new \stdClass();

    $result->status = 'email_error';
    $result->message = 'Email address missing';
} else {
    if (!validateEmail($subscriber->email)) {
        http_response_code(404);
        $result = new \stdClass();

        $result->status = 'email_error';
        $result->message = 'Email address not valid';
    } else {
        $subscriber->email_address = $subscriber->email;
        $subscriber->source = 'api';

        $result = createNewSubscriber(json_encode($subscriber));
        $result = json_decode($result);
        /*$result = new \stdClass();
        $result->status="success";
        $result->response = json_encode($subscriber);*/
        if ('success' === $result->status) {
            http_response_code(200);
        } elseif ('error' === $result->status || 'db_error' === $result->status) {
            http_response_code(503);
        }
    }
}

echo json_encode($result);
