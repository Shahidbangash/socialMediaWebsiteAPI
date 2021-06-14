<?php
include_once("./connection.php");
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With,Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');

$response_header = array("status_code" => null, "response_message" => null);


if (!$conn) {
    $response_header['status_code'] = 400;
    $response_header['response_message'] = 'Db error ' . mysqli_error($conn);
    echo json_encode($response_header);
} else {
    $form_data = json_decode(file_get_contents('php://input'), true);

    $groupID = cleanInput($form_data['groupID']);
    $userID = cleanInput($form_data['userID']);

    $sql = "DELETE FROM groupMember WHERE `groupID` = '$groupID' AND `userID` = '$userID'";

    if (mysqli_query($conn, $sql)) {
        $response_header['status_code'] = 200;
        $response_header['response_message'] = 'You have left group successfully: ';
        echo json_encode($response_header);
    } else {
        $response_header['status_code'] = 206;
        $response_header['response_message'] = 'Error ' . mysqli_error($conn);
        echo json_encode($response_header);
    }
}
