<?php declare(strict_types=1);
require_once 'Services/DB/FieldsCRUD.php';
    require_once 'Services/DB/SubscriberCRUD.php';

    if (isset($_GET['action'])) {
        if ('getAllFields' === $_GET['action']) {
            $result = getAllFields();
            echo $result;
        }
        if ('getAllSubscribers' === $_GET['action']) {
            $result = getAllSubscribers();
            echo $result;
        }
        if ('getSubscriberDetails' === $_GET['action']) {
            $result = getSubscriberDetails($_GET['subscriber_id']);
            echo $result;
        }
    }

    if (isset($_POST['action'])) {
        if ('createNewField' === $_POST['action']) {
            $result = createNewField($_POST['fields']);
            echo $result;
        }
        if ('deleteField' === $_POST['action']) {
            $result = deleteField($_POST['field_id']);
            echo $result;
        }
        if ('createNewSubscriber' === $_POST['action']) {
            $result = createNewSubscriber($_POST['subscriber']);
            echo $result;
        }
        if ('deleteSubscriber' === $_POST['action']) {
            $result = deleteSubscriber($_POST['subscriber_id']);
            echo $result;
        }
        if ('updateSubscriber' === $_POST['action']) {
            $result = updateSubscriber($_POST['subscriber']);
            echo $result;
        }
    }

    //$output = array('flag' => $flag, 'message' => $msg);

    //echo json_encode("anc");
?>

