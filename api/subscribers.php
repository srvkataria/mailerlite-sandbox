
<?php
// required headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__.'./../Services/DB/SubscriberCRUD.php';

$subscriber_list = getAllSubscribersAPI();
$subscriber_list = json_decode($subscriber_list);

if ('success' === $subscriber_list->status) {
    http_response_code(200);
} elseif ('error' === $subscriber_list->status) {
    http_response_code(404);
}

echo json_encode($subscriber_list);
