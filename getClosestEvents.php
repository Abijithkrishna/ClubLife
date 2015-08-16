<?php
require_once("praveenlib.php");
require_once("applib.php");
function cmp($a, $b)
{
    if ($a[2] == $b[2]) {
        return 0;
    }
    return ($a[2] < $b[2]) ? -1 : 1;
}

$respjson= array(
    "status"=>"unprocessed",
    "errorCode"=>1
);
$postKeys=array("lat","lon");

if(checkPOST($postKeys)) {

    $conn = connectSQL();
    if ($conn) {

        $sql = "select eventName,eventId,latitude,longitude from events WHERE  eventDate>NOW()";
        if ($result = $conn->query($sql)) {
            $respjson["list"] = array();
            while ($row = $result->fetch_array()) {
                $entry = array(
                    $row['eventId'],
                    $row['eventName']

                );
                $entry[]=distance($_POST['lat'],$_POST['lon'],$row['latitude'],$row['longitude']);
                $respjson["list"][] = $entry;
                usort($respjson["list"],"cmp");
            }



                $respjson["status"] = "Success";
            $respjson["errorCode"] = 0;
        } else {
            $respjson["status"] = "SQL error";
            $respjson["SqlError"] = $conn->error;
            $respjson["errorCode"] = 4;
        }
    } else {
        $respjson["status"] = "SQL Connection error";
        $respjson["SqlError"] = $conn->error;
        $respjson["errorCode"] = 3;
    }
}else{
    $respjson["status"]="insufficient Data";
    $respjson["errorCode"]=2;
}


echo json_encode($respjson);
