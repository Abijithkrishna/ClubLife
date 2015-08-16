<?php
require_once('datas.php');

function sendPushNotification($tokens,$title,$message){
    global $appdata;
    $fields = array(
        'registration_ids' => $tokens,
        'data' => array(
            "message"=>$message,
            "title"=>$title
            )
    );

    $headers = array(
        'Authorization: key='.$appdata['apiKey'],
        'Content-Type: application/json'
    );
    // Open connection
    $ch = curl_init();

    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $appdata['gcmURL']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Disabling SSL Certificate support temporarly
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    // Execute post
    $returnData=array();
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
        $returnData['error']=TRUE;
    }
    $returnData['error']=FALSE;
    $returnData['response']=json_decode($result);


    // Close connection
    curl_close($ch);
    return $returnData;
}

function getOwnerTokens($ids){
    require_once('praveenlib.php');
    $returnData=array(
        'error'=>0
    );
    if(count($ids)==0){
        $returnData['error']=6;
        $returnData['status']="userid required";
        return $returnData;
    }



    $conn=connectSQL();
    if($conn){
        $idString="".$ids[0];
        for($i=1;$i<count($ids);$i++){
            $idString.=",".$ids[$i];
        }
        $query="select ownerToken from owners where id in ($idString)";
        if($result=$conn->query($query)){
            $returnData['tokens']=array();
            while($row=$result->fetch_array()){
                $returnData['tokens'][]=$row[0];
            }
        }else{
            $returnData["status"]="SQL error";
            $returnData["SqlError"]=$conn->error;
            $returnData["errorCode"]=4;
        }
    }else{
        $returnData["status"]="SQL Connection error";
        $returnData["SqlError"]=$conn->error;
        $returnData["errorCode"]=3;

    }

    return $returnData;

}

function getUserTokens($ids){
    require_once('praveenlib.php');
    $returnData=array(
        'error'=>0
    );
    if(count($ids)==0){
        $returnData['error']=6;
        $returnData['status']="userid required";
        return $returnData;
    }



    $conn=connectSQL();
    if($conn){
        $idString="".$ids[0];
        for($i=1;$i<count($ids);$i++){
            $idString.=",".$ids[$i];
        }
        $query="select userToken from users where id in ($idString)";
        if($result=$conn->query($query)){
            $returnData['tokens']=array();
            while($row=$result->fetch_array()){
                $returnData['tokens'][]=$row[0];
            }
        }else{
            $returnData["status"]="SQL error";
            $returnData["SqlError"]=$conn->error;
            $returnData["errorCode"]=4;
        }
    }else{
        $returnData["status"]="SQL Connection error";
        $returnData["SqlError"]=$conn->error;
        $returnData["errorCode"]=3;

    }

    return $returnData;

}

function distance($lat1, $lon1, $lat2, $lon2)
{
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    return $dist;
}