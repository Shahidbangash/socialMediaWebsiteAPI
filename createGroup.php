<?php
include_once("./connection.php");
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With,Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');

$response_header = array("status_code" => null, "response_message" => null);


if (!$conn) {
    $response_header['status_code'] = 400;
    $response_header['response_message'] = 'Db error  ' . mysqli_error($conn);
    echo json_encode($response_header);
} else {
    $form_data = json_decode(file_get_contents('php://input'), true);

    $groupName = cleanInput($form_data['groupName']);
    $userID = cleanInput($form_data['userID']);


    $sql = "SELECT * FROM groupName WHERE `groupName` = '$groupName' ";

    if ($result = mysqli_query($conn, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            $response_header['status_code'] = 205;
            $response_header['response_message'] = 'Group with that name ' . $groupName . ' already exists';
            echo json_encode($response_header);
        } else {
            $query = mysqli_query($conn, "SELECT max(groupID) as id FROM groupName");
            while ($row = mysqli_fetch_assoc($query)) {
                $groupID = $row['id'];
            }
            $groupID = (int)$groupID + 1;
            $sql_insert = "INSERT INTO groupName (`groupID` , `groupName`,  `userID` ) VALUES ( '$groupID',  '$groupName'  , '$userID')";

            if (mysqli_query($conn, $sql_insert)) {
                $delete_blank = "DELETE FROM groupName  WHERE `groupID` = '' ";
                mysqli_query($conn, $delete_blank);
                $response_header['status_code'] = 200;
                $response_header['response_message'] = 'Group Created successfully : ';
                echo json_encode($response_header);
            } else {
                $response_header['status_code'] = 206;
                $response_header['response_message'] = 'Error ' . mysqli_error($conn);
                echo json_encode($response_header);
            }
        }
    } else {
        $response_header['status_code'] = 400;
        $response_header['response_message'] = 'dB  Error ' . mysqli_error($conn);
        echo json_encode($response_header);
    }
}
