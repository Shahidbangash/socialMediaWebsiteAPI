<?php

include_once("./connection.php");


$incoming_data = json_decode(file_get_contents('php://input'), true);

$session_id = cleanInput($incoming_data['session_id']);
$userID = cleanInput($incoming_data['userID']);
if ($session_id != null) {

    // query to get group id of user who joined group .. it will return a list of groups, joined by user

    $sql = "SELECT `groupID` FROM  groupMember WHERE  `groupMember`.`userID` = '$userID' ";

    $posts = [];

    if ($result = mysqli_query($conn, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            // loop through each row and append owner name to group
            while ($row = mysqli_fetch_assoc($result)) {
                $groupID = $row['groupID'];

                $groupOwner = mysqli_fetch_assoc(mysqli_query($conn, "SELECT `userID` as ownerID , `groupName` FROM groupName where `groupID` = '$groupID'"));
                $ownerID = $groupOwner['ownerID'];

                $username_query = mysqli_query($conn, "SELECT `firstName` , `lastName` from User where `userID` = '$groupOwner'");

                $userName = mysqli_fetch_assoc($username_query);
                $row["groupOwner"] = $userName['firstName'] . ' ' . $userName['lastName'];
                $row["groupName"] = $groupOwner["groupName"];
                $row["groupID"] = $groupID;
                array_push($posts, $row);
            }

            $response_header["status_code"] = 200;
            $response_header["groupsList"] = $posts;
            // mysqli_fetch_all($result, MYSQLI_ASSOC);
            echo json_encode($response_header);
        } else {
            $response_header["status_code"] = 404;
            $response_header["response_message"] = "You have not joined any group";
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
