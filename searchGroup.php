<?php

include("./connection.php");

$incoming_data = json_decode(file_get_contents('php://input'), true);

$session_id = cleanInput($incoming_data['session_id']);
$searchQuery = cleanInput($incoming_data['searchQuery']);

if ($session_id != null) {
    $sql = "SELECT * FROM groupName WHERE `groupName` like '$searchQuery'";

    $posts = [];

    if ($result = mysqli_query($conn, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $groupID = $row['groupID'];

                // this user iD belong to owner of group so we will use this userID to get first and last name of owner and send it back
                $userID = $row['userID'];
                // }
                // query to get first and last name
                $username_query = mysqli_query($conn, "SELECT `firstName` , `lastName` from User where `userID` = '$userID'");
                $userName = mysqli_fetch_assoc($username_query);

                $row["owner"] = $userName['firstName'] . ' ' . $userName['lastName'];

                // sending the group information to user if they are already a part of group or not
                // if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM groupMember WHERE `userID` = '$userID' AND `groupID` = '$groupID'")) > 0) {
                    $row["group_status"] = getGroupStatus($groupID, $userID);
                // } else {
                    // $row["group_status"] = "not joined";
                // }

                array_push($posts, $row);
            }

            $response_header["groups"] = $posts;
            $response_header["status_code"] = 200;
            echo json_encode($response_header);
        } else {
            $response_header["status_code"] = 404;
            $response_header["response_message"] = "No group found";
            echo json_encode($response_header);
        }
    } else {
        $response_header["response_message"] = " Mysqli error " . mysqli_error($conn);
        echo json_encode($response_header);
    }
} else {
    $response_header['session_id'] = $session_id;
    $response_header["response_message"] = "Please login again";
    echo json_encode($response_header);
}


function getGroupStatus($groupID, $userID){
    
    $result = mysqli_query($conn, "SELECT * FROM groupMember WHERE `userID` = '$userID' AND `groupID` = '$groupID'");

    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM groupMember WHERE `userID` = '$userID' AND `groupID` = '$groupID'")) > 0) {
        return "joined";
    } else {
        return "not joined " . mysqli_error($conn);
    }

}
