<?php
require_once("praveenlib.php");
require_once("applib.php");
$keys=array("userId","eventName","dateTime","ticketCount");
$respjson= array(
    "status"=>"unprocessed",
    "errorCode"=>1
);
if(checkPOST($keys)){
    $conn=connectSQL();
    if($conn){
        $userId=safeString($conn,$_POST['userId']);
        $eventName=safeString($conn,$_POST['eventName']);
        $dateTime=safeString($conn,$_POST['dateTime']);
        $ticketCount=safeString($conn,$_POST['ticketCount']);
        $sql="insert into events(userId,eventName,eventDate,ticketCount) values({$userId},'{$eventName}','{$dateTime}',{$ticketCount})";
        if($result=$conn->query($sql)){

            $sql="select userToken from users";
            if($result=$conn->query($sql)){
                $ids=array();
                while($row=$result->fetch_array()){
                    $ids[]=$row[0];
                }
                $retJSON=sendPushNotification($ids,"New Event",$eventName." is open for registration");
                $respjson["pushReturn"]=$retJSON;
                $respjson["status"]="Success";
                $respjson["errorCode"]=0;
            }else{
                $respjson["status"]="SQL error";
                $respjson["SqlError"]=$conn->error;
                $respjson["errorCode"]=4;
            }

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
