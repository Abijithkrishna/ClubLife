<?php
$onServer=true;
if($onServer) {
    $dbURL = "mysql.freehostingnoads.net";
    $dbName = "u149044957_club";
    $dbusername = "u149044957_club";
    $dbpassword = "database";
}else{
    $dbURL = "localhost";
    $dbName = "clublife";
    $dbusername = "root";
    $dbpassword = "";
}
$dbdetails = array(
    'url' => $dbURL,
    'name' => $dbName,
    'username' => $dbusername,
    'password' => $dbpassword
);


$errorCode=array(
    1=>"Unprocessed",
    2=>"Insufficient Parameters",
    3=>"DB Connectivity Error",
    4=>"Query Error",
    5=>"Autherntication Failure",
    6=>"Functional Error"
);


?>
