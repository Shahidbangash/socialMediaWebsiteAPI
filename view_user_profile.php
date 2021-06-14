<?php

include_once("./connection.php");


$incoming_data = json_decode(file_get_contents('php://input'), true);

$name  = cleanInput($incoming_data['userID']);
// $name  = 'erat';

// $incoming_data = json_decode(file_get_contents('php://input'), true);

$sql = "SELECT * FROM User WHERE `userID` = '$name'";

try {
    if ($result = mysqli_query($conn, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            echo json_encode($row);
        } else {
            $response_header["message"] = "Sorry no profile exist with username " . $name;
            echo json_encode($response_header);
        }
    } else {
        $response_header["message"] = " " . mysqli_error($conn);
        echo json_encode($response_header);
    }
} catch (Exception $excpetion) {
    $response_header["message"] = " " . mysqli_error($conn);
    echo json_encode($response_header);
}
