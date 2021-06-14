<?php

include_once("./connection.php");


$incoming_data = json_decode(file_get_contents('php://input'), true);

$session_id = cleanInput($incoming_data['session_id']);
$userID = cleanInput($incoming_data['userID']);
if ($session_id != null) {
    $sql = "SELECT * FROM groupPosts , groupMember WHERE `groupPosts`.`groupID` = `groupMember`.`groupID` AND `groupMember`.`userID` = '$userID' ";

    $posts = [];

    if ($result = mysqli_query($conn, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $userID = $row['userID'];

                $username_query = mysqli_query($conn, "SELECT `firstName` , `lastName` from User where `userID` = '$userID'");
                $userName = mysqli_fetch_assoc($username_query);
                $row["postedBy"] = $userName['firstName'] . ' ' . $userName['lastName'];

                array_push($posts, $row);
            }

            $response_header["status_code"] = 200;
            $response_header["groupPosts"] = $posts;
            // mysqli_fetch_all($result, MYSQLI_ASSOC);
            echo json_encode($response_header);
        } else {
            $response_header["status_code"] = 404;
            $response_header["response_message"] = "No Post found";
            echo json_encode($response_header);
        }
        // echo json_encode($response_header);
    } else {
        $response_header["response_message"] = "Mysqli error " . mysqli_error($conn);
        echo json_encode($response_header);
    }
} else {
    $response_header['session_id'] = $session_id;
    $response_header["response_message"] = " Please login again";
    echo json_encode($response_header);
}
