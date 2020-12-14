<?php

/*Below are the various functions the ussdflow script depends on
 NB: These functions are self explanatory, so ensure to pay strict attention during your implementation.*/


function deleteTracking($msisdn) {

include('include/connect.php');

//query to fetch all data of msisdn from tracking Table//
    $resettq="DELETE FROM tracking WHERE ID='$msisdn' ";
     $resett=$conn->prepare($resettq);
     $resett->execute();
}


function insertTracking($msisdn,$sessionid,$mode,$username,$time,$userdata,$track) {
include('include/connect.php');

//query to insert msisdn into the tracking table//
   $new_menuq="INSERT INTO tracking VALUES('$msisdn','$sessionid','$mode','$username','$time','$userdata','$track')";
        $new_menu=$conn->prepare($new_menuq);
        $new_menu->execute();
}


function deleteProgress($msisdn) {
include('include/connect.php');

//query to delect msisdn from progress table//
    $resett1q="DELETE  FROM progress WHERE ID='$msisdn' ";
    $resett1=$conn->prepare($resett1q);
    $resett1->execute();
}


function insertProgress($msisdn,$sessionid,$option,$donor_name,$amount,$network,$walletno,$volunteer_name,$age,$constituency) {
include('include/connect.php');


//query to insert passed parameters into the progress table//
      $progressq="INSERT INTO `progress`(`ID`, `sessionId`, `option`, `donor_name`, `amount`, `network`, `walletno`, `volunteer_name`, `age`, `constituency`) VALUES ('$msisdn','$sessionid','$option','$donor_name', '$amount', '$network', '$walletno', '$volunteer_name', '$age', '$constituency')";
      $progress=$conn->prepare($progressq);
      $progress->execute();
}


function getTracking($msisdn) {
include('include/connect.php');

  //query to fetch all data of msisdn from tracking Table//
  $check_menuq="SELECT * FROM tracking WHERE ID='$msisdn' ";
  $check_menu=$conn->prepare($check_menuq);
  $check_menu->execute();
      $row=$check_menu->fetch();

      return $row;
}


function getProgress($msisdn) {
include('include/connect.php');

      //query to fetch all data of msisdn from progress Table//
     $selectprogq="SELECT * FROM progress WHERE ID='$msisdn' ";
     $selectprog=$conn->prepare($selectprogq);
     $selectprog->execute();
     $rowopt=$selectprog->fetch();

      return $rowopt;
}


function updateTracking($msisdn,$sessionid,$mode,$username,$time,$userdata,$track) {
include('include/connect.php');

  //query to update msisdn with passed parameters into the tracking table//
 $menu_updateq="UPDATE tracking SET sessionId='$sessionid',mode='$mode',username='$username',`time`='$time',userData='$userdata',track='$track' WHERE ID='$msisdn'";
  $menu_update=$conn->prepare($menu_updateq);
  $menu_update->execute();
}


function updateProgress($msisdn,$sessionid,$field,$data) {
include('include/connect.php');

//query to update individual field based on the option passed//
 $progressq="";

 if($field=="option"){
   $progressq="UPDATE `progress` SET `sessionId`='$sessionid', `option`='$data' WHERE `ID`='$msisdn'  ";
 }
 if($field=="donor_name"){
   $progressq="UPDATE `progress` SET `sessionId`='$sessionid', `donor_name`='$data' WHERE `ID`='$msisdn'  ";
 }
if($field=="amount"){
   $progressq="UPDATE `progress` SET `sessionId`='$sessionid', `amount`='$data' WHERE `ID`='$msisdn'  ";
 }
if($field=="network"){
   $progressq="UPDATE `progress` SET `sessionId`='$sessionid', `network`='$data' WHERE `ID`='$msisdn'  ";
 }
 if($field=="walletno"){
   $progressq="UPDATE `progress` SET `sessionId`='$sessionid', `walletno`='$data' WHERE `ID`='$msisdn'  ";
 }
 if($field=="volunteer_name"){
   $progressq="UPDATE `progress` SET `sessionId`='$sessionid', `volunteer_name`='$data' WHERE `ID`='$msisdn'  ";
 }
  if($field=="age"){
   $progressq="UPDATE `progress` SET `sessionId`='$sessionid', `age`='$data' WHERE `ID`='$msisdn'  ";
 }
  if($field=="constituency"){
   $progressq="UPDATE `progress` SET `sessionId`='$sessionid', `constituency`='$data' WHERE `ID`='$msisdn'  ";
 }
 
  $progress=$conn->prepare($progressq);
  $progress->execute();
}



function insertTransaction($amount,$donor_name,$walletno,$network) {
include('include/connect.php');


//Generate Client transaction ID
$clienttrans = "BAKE" . rand(100000, 999999);
$clienttransid = $clienttrans;

$status="PROGRESS";

      $progressq="INSERT INTO `transactions`(`clientid`, `donor_name`, `amount`, `status`, `reference`, `telcotransid`, `wallet_num`, `wallet_network`) VALUES ('$clienttransid','$donor_name','$amount','$status','donation payment','NULL','$walletno','$network')";
      $progress=$conn->prepare($progressq);
      $progress->execute();

      sendMobileMoney($clienttransid,$walletno,$network,$amount);
}

function updateTransaction($clientid,$status) {
include('include/connect.php');

 $menu_updateq="UPDATE transactions SET status='$status' WHERE clientid='$clientid'";
  $menu_update=$conn->prepare($menu_updateq);
  $menu_update->execute();

  if ($status=="PAID") {
      $transdetails=gettrans($clientid); 
      $mmdetails=getmmtrans($clientid);
      $donor_name=$transdetails['donor_name'];
      $amount=$transdetails['amount'];
      $clientid=$transdetails['clientid'];
      $wallet_network=$transdetails['wallet_network'];
      $wallet_number=$transdetails['wallet_num'];
      $mmtransid=$mmdetails['telcotransid'];
      
      $menu_updateq="UPDATE transactions SET status='$status', telcotransid= '$mmtransid' WHERE clientid='$clientid'";
      $menu_update=$conn->prepare($menu_updateq);
      $menu_update->execute();

      insertdonation($donor_name,$amount,$clientid,$mmtransid,$wallet_network,$wallet_number);
  }

}

function insertdonation($donor_name,$amount,$clientid,$mmtransid,$wallet_network,$wallet_number) {
include('include/connect.php');

  $progressq="INSERT INTO `donations`(`name`, `amount`, `clientid`, `mmtransid`, `wallet_network`, `wallet_number`) VALUES ('$donor_name','$amount','$clientid','$mmtransid','$wallet_network','$wallet_number')";
  $progress=$conn->prepare($progressq);
  $progress->execute();
}

function insertvolunteer($full_name,$mobile_number,$age,$constituency) {
include('include/connect.php');

  $progressq="INSERT INTO `volunteers`(`full_name`, `mobile_number`, `age`, `constituency`) VALUES ('$full_name','$mobile_number','$age','$constituency')";
  $progress=$conn->prepare($progressq);
  $progress->execute();
}

function gettrans($clientid) {
include('include/connect.php');

     $selectprogq="SELECT * FROM transactions WHERE clientid='$clientid' ";
     $selectprog=$conn->prepare($selectprogq);
     $selectprog->execute();
     $rowopt=$selectprog->fetch();

      return $rowopt;
}

function getmmtrans($clientid) {
include('include/connect.php');

     $selectprogq="SELECT * FROM mm_transactions WHERE clienttransid='$clientid' and status='PAID' ";
     $selectprog=$conn->prepare($selectprogq);
     $selectprog->execute();
     $rowopt=$selectprog->fetch();

      return $rowopt;
}

function insertmmTransaction($clienttransid,$clientreference,$telcotransid,$transactionid,$status,$statusdate,$reason) {
include('include/connect.php');

         $progressq="INSERT INTO `mm_transactions`(`clienttransid`, `clientreference`, `telcotransid`, `transactionid`, `status`, `statusdate`, `reason`) VALUES ('$clienttransid','$clientreference','$telcotransid','$transactionid','$status','$statusdate','$reason')";
      $progress=$conn->prepare($progressq);
      $progress->execute();
}



function sendSMS($msisdn,$message) 
{
  include('include/connect.php');
    $time=date("Y/m/d h:i:s");
    $reciepients=$msisdn;
    $message=$message;
    $from='WIGAL';
    $smsusername='your_isms_username';
    $smspassword='your_isms_password';
    $sent_by="WIGAL";
    

   //initiate api to send message
    $messageApi='https://isms.wigalsolutions.com/ismsweb/sendmsg/';

    $params='username='.$smsusername.'&password='.$smspassword.'&from='.$from.'&to='.$reciepients.'&message='.$message;

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$messageApi);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$params);
    $return=curl_exec($ch);
    @file_put_contents('logs.txt',"\n" . "SMS request log on ". $time . "=> " . $return . "\n",FILE_APPEND);

    curl_close($ch);
}


function sendMobileMoney($clienttransid,$walletno,$network,$amount)
{
  $time=date("Y/m/d h:i:s");

  $params = array(
          'amount'=>$amount,
          'appid'=>your_redde_appid,
          'clientreference'=>"Donation Payment by client",
          'clienttransid'=>$clienttransid,
          'description'=>"Donation Payment",
          'nickname'=>"Bakeside Campaign",
          'paymentoption'=>$network,
          'vouchercode'=>"",
          'walletnumber'=>$walletno
          );
  $jsonbody = json_encode($params);

    @file_put_contents('logs.txt',"Payment request log on ". $time ."=> ". $jsonbody . "\n",FILE_APPEND);

   //initiate api to send Mobile Money Request
    $curl = curl_init();
           curl_setopt_array($curl, array(
           CURLOPT_URL => "https://api.reddeonline.com/v1/receive/",
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => "",
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 30,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => "POST",
           CURLOPT_POSTFIELDS =>  $jsonbody,
           CURLOPT_HTTPHEADER => array(
             "ApiKey: your_redde_apikey",
             "Content-Type: application/json"
          ),
        )
      );

   $response = curl_exec($curl);
   @file_put_contents('logs.txt',"Payment response log on ".$time ."=> ". $response . "\n",FILE_APPEND);

   $err = curl_error($curl);

   curl_close($curl);

     if ($err) {

     } else {

        $report = json_decode($response);
        if($report->status == 'OK')
        {
          updateTransaction($clienttransid,"PENDING");
          // echo $response;
       }
    }
}


?>
