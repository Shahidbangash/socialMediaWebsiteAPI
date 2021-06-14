<?php
include_once("./connection.php");
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With,Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');


$incoming_data = json_decode(file_get_contents('php://input'), true);

$userID = cleanInput($incoming_data['userID']);
$new_bio = cleanInput($incoming_data['bio']);

$sessionID = cleanInput($incoming_data['session_id']);

if (empty($sessionID)) {
    $response_header['response_message'] = "Sorry you have not logged in ";
    $response_header['response_code'] = 206;
    echo json_encode($response_header);
} else {

    $updateQuery = "Update User set `bio` = '$new_bio'  WHERE `userID` = '$userID'";

    $update = mysqli_query($conn, $updateQuery);

    if (mysqli_affected_rows($conn) > 0) {
        $response_header["response_message"] = "Successfully updated Bio";
        $response_header["response_code"] = 200;
        echo json_encode($response_header);
    } else {
        $response_header["response_message"] = "Sorry we Could not update Bio " . mysqli_error($conn);
        $response_header["response_code"] = 200;
        echo json_encode($response_header);
    }
}
