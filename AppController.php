<?php
	require_once('Services/DB/FieldsCRUD.php');
	require_once('Services/DB/SubscriberCRUD.php');

	if(isset($_GET['action']))
	{
		if($_GET['action'] == "getAllFields")
		{
			$result = getAllFields();
			echo $result;
		}
		if($_GET['action'] == "getAllSubscribers")
		{
			$result = getAllSubscribers();
			echo $result;
		}
		if($_GET['action'] == "getSubscriberDetails")
		{
			$result = getSubscriberDetails($_GET['subscriber_id']);
			echo $result;
		}
		
	}

	if(isset($_POST['action']))
	{
		if ($_POST['action'] == "createNewField")
		{
			$result = createNewField($_POST['fields']);
			echo $result;
		}
		if ($_POST['action'] == "deleteField")
		{
			$result = deleteField($_POST['field_id']);
			echo $result;
		}
		if ($_POST['action'] == "createNewSubscriber")
		{
			$result = createNewSubscriber($_POST['subscriber']);
			echo $result;
		}
		if ($_POST['action'] == "deleteSubscriber")
		{
			$result = deleteSubscriber($_POST['subscriber_id']);
			echo $result;
		}
		if ($_POST['action'] == "updateSubscriber")
		{
			$result = updateSubscriber($_POST['subscriber']);
			echo $result;
		}
		
		
	}

	//$output = array('flag' => $flag, 'message' => $msg);
	
	//echo json_encode("anc");
?>

