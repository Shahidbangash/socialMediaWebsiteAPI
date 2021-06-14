<?php
include_once("./connection.php");
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With,Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');


$incoming_data = json_decode(file_get_contents('php://input'), true);

$oldPassword = cleanInput($incoming_data['currentPassword']);
$newPassword = cleanInput($incoming_data['newPassword']);
$sessionID = cleanInput($incoming_data['session_id']);
$userID = cleanInput($incoming_data['userID']);

if (empty($sessionID)) {
    $response_header['message'] = "Sorry you have not logged in ";
    $response_header['response_code'] = 206;
    echo json_encode($response_header);
} else {

    $sql = "SELECT * FROM User WHERE `password` = '$oldPassword' ";

    if ($result = mysqli_query($conn, $sql)) {

        if (mysqli_num_rows($result) <= 0) {
            $response_header['status_code'] = 207;
            $response_header['response_message'] = 'You have entered invalid Old password';
            echo json_encode($response_header);
        } else {

            $sql = "UPDATE User set `password` = '$newPassword'  WHERE `userID` = '$userID' ";
            $update = mysqli_query($conn, $sql);

            if (mysqli_affected_rows($conn) > 0) {
                $response_header["response_message"] = "Successfully updated Password";
                $response_header["response_code"] = 200;
                echo json_encode($response_header);
            } else {
                $response_header["response_message"] = "Sorry we Could not Password !! " . mysqli_error($conn);
                $response_header["response_code"] = 207;
                echo json_encode($response_header);
            }
        }
    } else {
        $response_header["response_message"] = "Sorry we Could not Password !! " . mysqli_error($conn);
        $response_header["response_code"] = 207;
        echo json_encode($response_header);
    }
}
