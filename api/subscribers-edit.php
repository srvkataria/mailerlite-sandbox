
<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
require_once __DIR__.'./../Services/DB/SubscriberCRUD.php';
require_once __DIR__.'./../Services/DB/FieldsCRUD.php';


$subscriber = json_decode(file_get_contents("php://input"));

if (empty($subscriber->subscriber_id) && empty($subscriber->email)) {
	http_response_code(503);
	$result = new \stdClass();
        
	$result->status = "input_error";
	$result->message = "Missing subscriber ID or email address of the subscriber";
} else {
	$subscriber->source = "api";
	if (!empty($subscriber->subscriber_id)) {
		$result = updateSubscriber(json_encode($subscriber));
	} else if (!empty($subscriber->email)) {
		$subscriber->email_address = $subscriber->email;
		$result = updateSubscriberByMail(json_encode($subscriber));
	}

	$result = json_decode($result);
	/*$result = new \stdClass();
    $result->status="success";
	$result->response = json_encode($subscriber);*/
	if ($result->status == "success") {
		http_response_code(200);
	} else if ($result->status == "error" || $result->status == "db_error") {
		http_response_code(503);
	} 
}


echo json_encode($result);