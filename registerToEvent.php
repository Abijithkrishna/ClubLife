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
        $sql="call takeTicket({$eventId});";
        if($result=$conn->query($sql)){
            $row=$result->fetch_array();
            if($row[0]==1){
                $conn->close();
                $conn=connectSQL();
                $sql="insert into  eventregistration (userId,eventId,userName) VALUES ({$userId},{$eventId},'{$userName}')";
                if($result=$conn->query($sql)){
                    $sql="select userId,eventName from events where eventId=$eventId";
                    if($result=$conn->query($sql)) {
                        if($result->num_rows>0) {
                            require_once('applib.php');
                            $ownerId=$result->fetch_array()['userId'];
                            $eventName=$result->fetch_array()['eventName'];
                            $ids=array($ownerId);
                            $tokens=getTokens($ids)['tokens'];
                            $title="Event Registration";
                            $message="$userName Registered for $eventName";
                            $retjson=sendPushNotification($tokens,$title,$message);


                            $respjson["status"] = "Success";
                            $respjson["errorCode"] = 0;


                        }else{
                            $respjson["status"] = "Event Owner Id not found";
                            $respjson["errorCode"] = 6;
                        }



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
                $respjson["status"]="Ticket not avalilable";
                $respjson["errorCode"]=6;
            }
        }else{
            $respjson["status"]="SQL call error ";
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
