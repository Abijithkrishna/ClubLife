<?php
require_once("praveenlib.php");
require_once("applib.php");
$keys=array("eventId");
$respjson= array(
    "status"=>"unprocessed",
    "errorCode"=>1
);
if(checkPOST($keys)){
    $conn=connectSQL();
    if($conn){
        $eventId=safeString($conn,$_POST['eventId']);

        $sql="delete from events  where eventId=$eventId";
        if($result=$conn->query($sql)){

            $sql="select userToken from users where id in (select userId from eventregistration where eventId=$eventId)";
            if($result=$conn->query($sql)){
                $ids=array();
                while($row=$result->fetch_array()){
                    $ids[]=$row[0];
                }
                $retJSON=sendPushNotification($ids,"Event Removed",$eventName." is Removed");
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
            $respjson['sql']=$sql;
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
