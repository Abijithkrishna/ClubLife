<?php
$onServer=false;
if($onServer) {
    $dbURL = "mysql.freehostingnoads.net";
    $dbName = "u149044957_track";
    $dbusername = "u149044957_track";
    $dbpassword = "database";
}else{
    $dbURL = "localhost";
    $dbName = "clublife";
    $dbusername = "root";
    $dbpassword = "db";
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
    3=>"DB Connectivity error",
    4=>"Query Error"
);


?>
