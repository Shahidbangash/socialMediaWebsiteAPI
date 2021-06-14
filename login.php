<?php

// include_once("./session.php");
include_once("./connection.php");
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With,Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');

session_start();

$incoming_data = json_decode(file_get_contents('php://input'), true);

$name = cleanInput($incoming_data['username']);
$userPassword = cleanInput($incoming_data['password']);


$sql = " SELECT * FROM `User` WHERE `username` = '$name'";

if ($result = mysqli_query($conn, $sql)) {
    if (mysqli_num_rows($result) <= 0) {
        $row = mysqli_fetch_assoc($result);
        // $response_header['username'] = $row['username'];

        $response_header['response_message'] = "Sorry No user exist with email or username " . $name . " exists";
        $response_header['result_code'] = 206;
        echo json_encode($response_header);
    } else {
        $sql = " SELECT * FROM `User` WHERE `username` = '$name' AND `password` = '$userPassword'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) >= 1) {
            $row = mysqli_fetch_assoc($result);
            // session_start();

            setcookie('username', $name, time() + 3600, '/');
            setcookie('name', $row['firstName'] . " 
" . $row['lastName'], time() + 3600, '/');

            setcookie('username', $name, time() + 3600, '/');
            setcookie('name', $row['firstName'] . " " . $row['lastName'], time() + 3600, '/');

            $_SESSION['username'] = $name;
            $session_id = generateRandomString(12);
            $_SESSION['session_id'] = $session_id;
            // $response_header['session_id2'] = $_SESSION['session_id'];
            $response_header['username'] = $row['username'];
            $response_header['session_id'] = $_SESSION['session_id'];
            $response_header['userID'] = $row['userID'];
            $response_header['result_code'] = 200;
            $response_header['response_message'] = "Success";
            echo json_encode($response_header);
        } else {
            $response_header['response_message'] = "Sorry You have entered invalid password ! try another password";
            $response_header['result_code'] = 206;
            echo json_encode($response_header);
        }
    }
} else {
    $response_header['response_message'] = "Error : " . mysqli_error($conn);
    echo json_encode($response_header);
}
