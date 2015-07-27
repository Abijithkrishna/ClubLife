<?php
require_once("praveenlib.php");
$keys=array("username","password");
$respjson= array(
    "userType"=>"owner",
    "status"=>"unprocessed",
    "errorCode"=>1
);
if(checkPOST($keys)){
    $conn=connectSQL();
    if($conn){

        $username=safeString($conn,$_POST['username']);
        $password=safeString($conn,$_POST['password']);
        $sql="select id,hotelName,address from owners where username='{$username}' and password='{$password}' limit 1";
        if($result=$conn->query($sql)){
            $userCount=$result->num_rows;
            if($userCount==1){
                $row=$result->fetch_array();
                $respjson['userId']=$row["id"];
                $respjson['hotelName']=$row['hotelName'];
                $respjson['address']=$row['address'];
                $respjson["status"]="Success";
                $respjson["errorCode"]=0;

            }else{
                $respjson["status"]="Authentication Failure";
                $respjson['errorCode']=5;
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
