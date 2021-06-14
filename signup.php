<?php
include_once("./connection.php");
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With,Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');

$response_header = array("status_code" => null, "response_message" => null);


if (!$conn) {
    // die("Connection failed: " . mysqli_connect_error());
    $response_header['status_code'] = 400;
    $response_header['response_message'] = 'Db error  ' . mysqli_error($conn);
    echo json_encode($response_header);
} else {

    $form_data = json_decode(file_get_contents('php://input'), true);
    // $form_data = array_values($form_data);

    $firstName = cleanInput($form_data['first_name']);
    $lastName = cleanInput($form_data['last_name']);
    $username = cleanInput($form_data['username']);
    $email = cleanInput($form_data['email']);
    $password = cleanInput($form_data['password']);

    $sql = "SELECT * FROM User WHERE `email` = '$email' ";

    if ($result = mysqli_query($conn, $sql)) {

        if (mysqli_num_rows($result) > 0) {
            $response_header['status_code'] = 205;
            $response_header['response_message'] = 'User Email ' . $email . ' already exists ';
            echo json_encode($response_header);
        } else {
            // $password_encription
            // = password_hash($form_data[3], PASSWORD_DEFAULT);

            $query = mysqli_query($conn, "SELECT max(userID) as id FROM User");
            while ($row = mysqli_fetch_assoc($query)) {
                $userID = $row['id'];
            }
            $userID = (int)$userID + 1;

            // $$userID = rand(pow(10, 20), pow(10, 23) - 1);
            // $sql_insert = "INSERT INTO User (`userID` , `username`,  `firstName`, `lastName`, `email` , `phoneNumber` , `DOB`,  `password`, `followers` , `bio` ) VALUES ( '$userID',  '$form_data[3]' , '$form_data[1]', '$form_data[2]' , '$form_data[0]' , '$form_data[4]' , ''  , '' , '' , '')";
            $sql_insert = "INSERT INTO User (`userID` , `username`,  `firstName`, `lastName`, `email` , `phoneNumber` , `DOB`,  `password`, `followers` , `bio` ) VALUES ( '$userID',   '$email' , '$firstName', '$lastName' , '$email' , '' , ''  , '$password' , '' , '')";

            if (mysqli_query($conn, $sql_insert)) {
                $delete_blank = "DELETE FROM `user` WHERE `email` = '' ";
                mysqli_query($conn, $delete_blank);
                $response_header['status_code'] = 200;
                $response_header['response_message'] = 'Account Registered Successfully with userID : ' . $userID;
                echo json_encode($response_header);
                // mysql_free_result() 
            } else {
                $response_header['status_code'] = 206;
                $response_header['response_message'] = 'Error' . mysqli_error($conn);
                echo json_encode($response_header);
            }
        }
    } else {
        $response_header['status_code'] = 400;
        $response_header['response_message'] = 'dB  Error' . mysqli_error($conn);
        echo json_encode($response_header);
    }
}
