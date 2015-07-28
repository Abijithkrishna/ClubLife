<?php
require_once("praveenlib.php");
$keys=array("userId","eventId","userName");
$respjson= array(
    "status"=>"unprocessed",
    "errorCode"=>1
);
if(checkPOST($keys)){
    $conn=connectSQL();
    if($conn){
        $eventId=safeString($conn,$_POST['eventId']);
        $userId=safeString($conn,$_POST['userId']);
        $userName=safeString($conn,$_POST['userName']);
        $sql="insert into eventregistration (userId,eventId,userName) VALUES ({$userId},{$eventId},'{$userName}')";
        if($result=$conn->query($sql)){
            $respjson["status"]="Success";
            $respjson["errorCode"]=0;


        }else{
            $respjson["status"]="SQL error";
            $respjson["SqlError"]=$conn->error;
            $respjson["errorCode"]=4;
        }
    }else{
        $respjson["status"]="SQL Connection error";
        $respjson["SqlError"]=$conn->error;
        $respjson["errorCode"]=3;
    }
}else{
    $respjson["status"]="insufficient Data";
    $respjson["errorCode"]=2;
}

echo json_encode($respjson);
