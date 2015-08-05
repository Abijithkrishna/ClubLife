<?php
require_once("praveenlib.php");
$keys=array("eventId");
$respjson= array(
    "status"=>"unprocessed",
    "errorCode"=>1
);
if(checkPOST($keys)){
    $conn=connectSQL();
    if($conn){
        $eventId=safeString($conn,$_POST['eventId']);
        $sql="select eventName,eventDate,userId,ticketCount from events WHERE eventId={$eventId} limit 1";
        if($result=$conn->query($sql)){
            $rowCount=$result->num_rows;
            if($rowCount>0){
                $row=$result->fetch_array();

                $respjson['eventName']=$row['eventName'];
                $respjson['eventDateTime']=$row['eventDate'];
                $respjson['ownerId']=$row['userId'];
                $respjson['ticketCount']=$row['ticketCount'];

                $sql="select hotelName,address from owners where id={$row['userId']} limit 1";
                if($result=$conn->query($sql)){
                    $rowCount=$result->num_rows;
                    if($rowCount>0){
                        $row=$result->fetch_array();
                        $respjson['hotelName']=$row['hotelName'];
                        $respjson['address']=$row['address'];
                        $respjson["status"]="Success";
                        $respjson["errorCode"]=0;
                    }else{
                        $respjson["status"]="Owner Not Found";
                        $respjson["errorCode"]=6;
                    }
                }else{
                    $respjson["status"]="SQL error";
                    $respjson["SqlError"]=$conn->error;
                    $respjson["errorCode"]=4;
                }

            }else{
                $respjson["status"]="Event Not Found";
                $respjson["errorCode"]=6;
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
