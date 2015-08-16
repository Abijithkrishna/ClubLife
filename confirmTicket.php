<?php
require_once("praveenlib.php");
require_once('applib.php');
$keys=array("ticketId","eventName");
$respjson= array(
    "status"=>"unprocessed",
    "errorCode"=>1
);
if(checkPOST($keys)){
    $conn=connectSQL();
    if($conn){
        $ticketId=safeString($conn,$_POST['ticketId']);
        $eventName=safeString($conn,$_POST['eventName']);
        $sql="update eventregistration set status=1 where ticketId={$ticketId}";
        if($result=$conn->query($sql)){

            $sql="select userToken from users where id in (select userId from eventregistration where ticketId=$ticketId)";
            if($result=$conn->query($sql)){
                if($result->num_rows>0){
                    $row=$result->fetch_array();

                    $respjson['tokens']=array($row['0']);

                    $message=$eventName." ticked Confirmed";
                    $retJson=sendPushNotification($respjson['tokens'],"Ticket Confirmation",$message);
                    $respjson['pushReturn']=$retJson;
                    $respjson["status"]="success";
                    $respjson["errorCode"]=0;
                }else{
                    $respjson["status"]="User Not Found";
                    $respjson["errorCode"]=6;
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
        $respjson["status"]="SQL Connection error";
        $respjson["SqlError"]=$conn->error;
        $respjson["errorCode"]=3;
    }
}else{
    $respjson["status"]="insufficient Data";
    $respjson["errorCode"]=2;
}

echo json_encode($respjson);
