<?php

declare(strict_types=1);
require_once __DIR__.'./../../config.php';

function getAllFields()
{
    $conn = openConnection();
    $status = '';
    $result = '';
    $finalQuery = 'SELECT field_id, title, type, created_at from fields order by title';

    $all_fields = $conn->query($finalQuery);
    $fields = [];
    if ($all_fields) {
        if ($all_fields->num_rows > 0) {
            while ($row = $all_fields->fetch_assoc()) {
                $fields[$row['field_id']] = $row;
            }
        }
        $status = 'success';
        $result = $fields;
    } else {
        $status = 'error';
        $result = $conn->error;
    }

    $output = ['status' => $status, 'result' => $result];
    closeConnection($conn);

    return json_encode($output);
}

function checkDuplicateTtile($title)
{
    $conn = openConnection();
    $status = '';
    $result = '';
    $finalQuery = "SELECT field_id, title, type, created_at from fields where title = '".$title."'";

    $all_fields = $conn->query($finalQuery);
    if ($all_fields) {
        if ($all_fields->num_rows > 0) {
            return true;
        }
    }
    closeConnection($conn);

    return false;
}

function createNewField($fields)
{
    $conn = openConnection();
    $fields = json_decode($fields);
    $status = '';
    $result = '';

    $field_name = $fields->field_name;
    $field_type = $fields->field_type;

    if (checkDuplicateTtile($field_name)) {
        $status = 'duplicate_error';
        $result = 'Field '.$field_name.' already exists.';
    } else {
        $firstQueryPart = 'INSERT INTO fields (';
        $secondQueryPart = ') VALUES (';

        $firstQueryPart = $firstQueryPart.'title,';
        $secondQueryPart = $secondQueryPart."'{$field_name}',";

        $firstQueryPart = $firstQueryPart.'type,';
        $secondQueryPart = $secondQueryPart."'{$field_type}',";

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
        } else {
            $status = 'db_error';
            $result = $conn->error;
        }
    }

    $output = ['status' => $status, 'result' => $result];
    closeConnection($conn);

    return json_encode($output);
}

function deleteField($field_id)
{
    $conn = openConnection();
    $status = '';
    $result = '';
    $finalQuery = 'DELETE FROM fields WHERE field_id = '.$field_id;

    $query_results = $conn->query($finalQuery);
    if ($query_results) {
        $status = 'success';
        $result = '';
    } else {
        $status = 'error';
        $result = $conn->error;
    }

    $output = ['status' => $status, 'result' => $result];
    closeConnection($conn);

    return json_encode($output);
}
