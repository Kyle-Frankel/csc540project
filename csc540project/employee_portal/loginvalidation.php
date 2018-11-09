<?php
require "database.php";


session_start();
if (isset($_SESSION['username'])) {
    header( 'Location: ../employee_portal/portal.php ' );
}

$user = $_POST["username"];
$pw = $_POST["password"];
$savedPw = "";

$conn = getConnection();
$query = "SELECT * from employee where username = '$user'";

$res = mysqli_query($conn, $query);

if(mysqli_num_rows($res) != 1){
    $data = array(
        "isValid"=> false,
        "feedback"=> "User not found."
    );
    cleanupDBResources($conn,$res);
    echo json_encode($data);
    exit;
}else{
    $row = $res->fetch_row();
    $savedPw = $row[2];
    $userId = $row[0];
}


if(password_verify($pw,$savedPw)) {
    $update = "UPDATE employee SET isLoggedIn=true WHERE username = '$user'";
    $up = mysqli_query($conn, $update);
    $data = array(
        "isValid"=> true,
        "feedback"=>"Logged in!"
    );
    $_SESSION['username'] = $user;
    $_SESSION['userid'] = $userId;
    echo json_encode($data);
}else{
    $data = array(
        "isValid"=> false,
        "feedback"=> "Incorrect password."
    );
    cleanupDBResources($conn,$res);
    echo json_encode($data);
}
exit;


?>



