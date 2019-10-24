<?php

declare(strict_types=1);
require_once __DIR__.'./../../config.php';

function getAllSubscribers()
{
    $conn = openConnection();
    $status = '';
    $result = '';
    $finalQuery = 'SELECT subscriber_id, email_address, name, state, source, fields, created_at from subscriber order by created_at desc';

    $all_subscribers = $conn->query($finalQuery);
    $subscriber = [];
    if ($all_subscribers) {
        if ($all_subscribers->num_rows > 0) {
            while ($row = $all_subscribers->fetch_assoc()) {
                $subscriber[$row['subscriber_id']] = $row;
            }
        }
        $status = 'success';
        $result = $subscriber;
    } else {
        $status = 'error';
        $result = $conn->error;
    }

    $output = ['status' => $status, 'result' => $result];
    closeConnection($conn);

    return json_encode($output);
}

function getAllSubscribersAPI()
{
    $conn = openConnection();
    $status = '';
    $result = '';

    $fields_raw = [];
    $sql = 'SELECT field_id, title, type, created_at from fields order by title';
    $fields_raw_list = $conn->query($sql);
    if ($fields_raw_list) {
        while ($row = $fields_raw_list->fetch_assoc()) {
            $fields_raw[$row['title']] = $row['type'];
        }
    }

    $finalQuery = 'SELECT subscriber_id, email_address, name, state, source, fields, created_at from subscriber order by created_at desc';

    $all_subscribers = $conn->query($finalQuery);
    $subscriber = [];
    if ($all_subscribers) {
        if ($all_subscribers->num_rows > 0) {
            while ($row = $all_subscribers->fetch_assoc()) {
                $all_fields = [];
                $field = [];
                if ($row['fields']) {
                    $fields_arr = explode(',', $row['fields']);
                    foreach ($fields_arr as $key => $value) {
                        $fields_arr_inner = explode(':', $value);
                        if (isset($fields_arr_inner[0], $fields_arr_inner[1])) {
                            $field['key'] = $fields_arr_inner[0];
                            $field['value'] = $fields_arr_inner[1];
                            $field['type'] = $fields_raw[$field['key']];
                        }
                        array_push($all_fields, $field);
                    }
                    $row['fields'] = $all_fields;
                }
                array_push($subscriber, $row);
            }
        }
        $status = 'success';
        $result = $subscriber;
    } else {
        $status = 'error';
        $result = $conn->error;
    }

    $output = ['status' => $status, 'list' => $result];
    closeConnection($conn);

    return json_encode($output);
}

function checkDuplicateSubscriber($email)
{
    $conn = openConnection();
    $status = '';
    $result = '';
    $finalQuery = "SELECT subscriber_id, email_address from subscriber where email_address = '".$email."'";

    $subscriber = $conn->query($finalQuery);
    if ($subscriber) {
        if ($subscriber->num_rows > 0) {
            return true;
        }
    }
    closeConnection($conn);

    return false;
}

function createNewSubscriber($subscriber)
{
    $conn = openConnection();
    $subscriber = json_decode($subscriber);
    $status = '';
    $result = '';

    $email_address = $subscriber->email_address;

    if (checkDuplicateSubscriber($email_address)) {
        $status = 'duplicate_error';
        $result = 'Subscriber with email address '.$email_address.' already exists.';
    } else {
        $firstQueryPart = 'INSERT INTO subscriber (';
        $secondQueryPart = ') VALUES (';

        $firstQueryPart = $firstQueryPart.'email_address,';
        $secondQueryPart = $secondQueryPart."'{$email_address}',";

        if (isset($subscriber->name)) {
            $firstQueryPart = $firstQueryPart.'name,';
            $secondQueryPart = $secondQueryPart."'{$subscriber->name}',";
        }

        if (isset($subscriber->state)) {
            $firstQueryPart = $firstQueryPart.'state,';
            $secondQueryPart = $secondQueryPart."'{$subscriber->state}',";
        }

        if (isset($subscriber->source)) {
            $firstQueryPart = $firstQueryPart.'source,';
            $secondQueryPart = $secondQueryPart."'{$subscriber->source}',";
        }

        if (isset($subscriber->fields)) {
            $firstQueryPart = $firstQueryPart.'fields,';
            $final_value = '';
            foreach ($subscriber->fields as $key => $value) {
                $final_value = $final_value.$key.':'.$value.',';
            }
            $final_value = rtrim($final_value, ',');
            $secondQueryPart = $secondQueryPart."'{$final_value}',";
        }
        //date_default_timezone_set('Asia/Kolkata');
        $today = date('Y-m-d H:i:s');
        $firstQueryPart = $firstQueryPart.' created_at';
        $secondQueryPart = $secondQueryPart." '".$today."'";

        $secondQueryPart = $secondQueryPart.')';
        $finalQuery = $firstQueryPart.$secondQueryPart;
        //echo $finalQuery;
        $insertRow = $conn->query($finalQuery);

        if ($insertRow) {
            $status = 'success';
            $subscriberDetails = getSubscriberByEmailID($subscriber->email_address);
            $subscriberDetails = json_decode($subscriberDetails);
            //$subscriberDetails->subscriber_details->fields = $subscriberDetails->all_fields;
            //$result = $subscriberDetails->subscriber_details;
            $result = $subscriberDetails->list;
        } else {
            $status = 'db_error';
            $result = $conn->error;
        }
    }

    $output = ['status' => $status, 'result' => $result];
    closeConnection($conn);

    return json_encode($output);
}

function deleteSubscriber($subscriber_id)
{
    $conn = openConnection();
    $status = '';
    $result = '';
    $finalQuery = 'DELETE FROM subscriber WHERE subscriber_id = '.$subscriber_id;

    $query_results = $conn->query($finalQuery);
    if ($query_results) {
        if ($conn->affected_rows > 0) {
            $status = 'success';
            $result = 'Subscriber with ID: '.$subscriber_id.' is deleted';
        } else {
            $status = 'not_found_error';
            $result = 'Subscriber with ID: '.$subscriber_id.' not found';
        }
    } else {
        $status = 'error';
        $result = $conn->error;
    }

    $output = ['status' => $status, 'result' => $result];
    closeConnection($conn);

    return json_encode($output);
}

function deleteSubscriberByMail($email)
{
    $conn = openConnection();
    $status = '';
    $result = '';
    $finalQuery = "DELETE FROM subscriber WHERE email_address = '".$email."'";

    $query_results = $conn->query($finalQuery);
    if ($query_results) {
        if ($conn->affected_rows > 0) {
            $status = 'success';
            $result = 'Subscriber with email address: '.$email.' is deleted';
        } else {
            $status = 'not_found_error';
            $result = 'Subscriber with email address: '.$email.' not found';
        }
    } else {
        $status = 'error';
        $result = $conn->error;
    }

    $output = ['status' => $status, 'result' => $result];
    closeConnection($conn);

    return json_encode($output);
}

function getSubscriberByEmailID($email)
{
    $conn = openConnection();
    $status = '';
    $result = '';

    $fields_raw = [];
    $sql = 'SELECT field_id, title, type, created_at from fields order by title';
    $fields_raw_list = $conn->query($sql);
    if ($fields_raw_list) {
        while ($row = $fields_raw_list->fetch_assoc()) {
            $fields_raw[$row['title']] = $row['type'];
        }
    }

    $finalQuery = "SELECT subscriber_id, email_address, name, state, source, fields, created_at from subscriber where email_address = '".$email."' order by created_at desc";

    $all_subscribers = $conn->query($finalQuery);
    $subscriber = [];
    if ($all_subscribers) {
        if ($all_subscribers->num_rows > 0) {
            while ($row = $all_subscribers->fetch_assoc()) {
                $all_fields = [];
                $field = [];
                if ($row['fields']) {
                    $fields_arr = explode(',', $row['fields']);
                    foreach ($fields_arr as $key => $value) {
                        $fields_arr_inner = explode(':', $value);
                        if (isset($fields_arr_inner[0], $fields_arr_inner[1])) {
                            $field['key'] = $fields_arr_inner[0];
                            $field['value'] = $fields_arr_inner[1];
                            $field['type'] = $fields_raw[$field['key']];
                        }
                        array_push($all_fields, $field);
                    }
                    $row['fields'] = $all_fields;
                }
                array_push($subscriber, $row);
            }
        }
        $status = 'success';
        $result = $subscriber;
    } else {
        $status = 'error';
        $result = $conn->error;
    }

    $output = ['status' => $status, 'list' => $result];
    closeConnection($conn);

    return json_encode($output);
}

function getSubscriberByEmailIDOld($email)
{
    $conn = openConnection();
    $status = '';
    $result = '';
    $finalQuery = "SELECT subscriber_id, email_address, name, state, source, fields, created_at from subscriber where email_address = '".$email."'";

    $subscriber_record = $conn->query($finalQuery);
    $subscriber = [];
    if ($subscriber_record) {
        if ($subscriber_record->num_rows > 0) {
            while ($row = $subscriber_record->fetch_assoc()) {
                $subscriber = $row;
            }
        }
        $status = 'success';
        $result = $subscriber;
    } else {
        $status = 'error';
        $result = $conn->error;
    }

    $all_fields = getAllFields();
    $all_fields = json_decode($all_fields);
    $fields_raw = $all_fields->result;

    $output = ['status' => $status, 'subscriber_details' => $result, 'all_fields' => $fields_raw];
    closeConnection($conn);

    return json_encode($output);
}

function getSubscriberDetails($subscriber_id)
{
    $conn = openConnection();
    $status = '';
    $result = '';
    $finalQuery = "SELECT subscriber_id, email_address, name, state, source, fields, created_at from subscriber where subscriber_id = '".$subscriber_id."'";

    $subscriber_record = $conn->query($finalQuery);
    $subscriber = [];
    if ($subscriber_record) {
        if ($subscriber_record->num_rows > 0) {
            while ($row = $subscriber_record->fetch_assoc()) {
                $subscriber = $row;
            }
        }
        $status = 'success';
        $result = $subscriber;
    } else {
        $status = 'error';
        $result = $conn->error;
    }

    $all_fields = getAllFields();
    $all_fields = json_decode($all_fields);
    $fields_raw = $all_fields->result;

    $output = ['status' => $status, 'subscriber_details' => $result, 'all_fields' => $fields_raw];
    closeConnection($conn);

    return json_encode($output);
}

function updateSubscriber($subscriber)
{
    $conn = openConnection();
    $subscriber = json_decode($subscriber);
    $status = '';
    $result = '';

    $subscriber_id = '';
    $email_address = '';
    $name = '';
    $state = '';
    $source = '';
    $fields = '';

    if (isset($subscriber->subscriber_id)) {
        $subscriber_id = $subscriber->subscriber_id;
    }
    if (isset($subscriber->email_address)) {
        $email_address = $subscriber->email_address;
    }
    if (isset($subscriber->name)) {
        $name = $subscriber->name;
    }
    if (isset($subscriber->state)) {
        $state = $subscriber->state;
    }

    if (isset($subscriber->source)) {
        $source = $subscriber->source;
    }

    if (isset($subscriber->fields)) {
        foreach ($subscriber->fields as $key => $value) {
            $fields = $fields.$key.':'.$value.',';
        }
        $fields = rtrim($fields, ',');
    }

    $finalQuery = "UPDATE subscriber SET name='".$name."', state='".$state."', source='".$source."', fields='".$fields."' WHERE subscriber_id='".$subscriber_id."'";

    $updateRow = $conn->query($finalQuery);
    if ($updateRow) {
        $status = 'success';
        $result = 'Subscriber with ID: '.$subscriber_id.' is updated';
    } else {
        $status = 'db_error';
        $result = $conn->error;
    }

    $output = ['status' => $status, 'result' => $result];
    closeConnection($conn);

    return json_encode($output);
}

function updateSubscriberByMail($subscriber)
{
    $conn = openConnection();
    $subscriber = json_decode($subscriber);
    $status = '';
    $result = '';

    $subscriber_id = '';
    $email_address = '';
    $name = '';
    $state = '';
    $source = '';
    $fields = '';

    if (isset($subscriber->subscriber_id)) {
        $subscriber_id = $subscriber->subscriber_id;
    }
    if (isset($subscriber->email_address)) {
        $email_address = $subscriber->email_address;
    }
    if (isset($subscriber->name)) {
        $name = $subscriber->name;
    }
    if (isset($subscriber->state)) {
        $state = $subscriber->state;
    }

    if (isset($subscriber->source)) {
        $source = $subscriber->source;
    }

    if (isset($subscriber->fields)) {
        foreach ($subscriber->fields as $key => $value) {
            $fields = $fields.$key.':'.$value.',';
        }
        $fields = rtrim($fields, ',');
    }

    $finalQuery = "UPDATE subscriber SET name='".$name."', state='".$state."', source='".$source."', fields='".$fields."' WHERE email_address='".$email_address."'";

    $updateRow = $conn->query($finalQuery);
    if ($updateRow) {
        $status = 'success';
        $result = 'Subscriber with email address: '.$email_address.' is updated';
    } else {
        $status = 'db_error';
        $result = $conn->error;
    }

    $output = ['status' => $status, 'result' => $result];
    closeConnection($conn);

    return json_encode($output);
}

function validateEmail($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    return true;
}
