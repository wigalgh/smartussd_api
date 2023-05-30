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


function insertProgress($msisdn,$sessionid,$option,$donor_name,$amount,$network,$walletno,$volunteer_name,$age,$email) {
include('include/connect.php');


  //query to insert passed parameters into the progress table//
  $progressq="INSERT INTO `progress`(`ID`, `sessionId`, `option`, `donor_name`, `amount`, `network`, `walletno`, `volunteer_name`, `age`, `email`) VALUES ('$msisdn','$sessionid','$option','$donor_name', '$amount', '$network', '$walletno', '$volunteer_name', '$age', '$email')";
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
  if($field=="email"){
   $progressq="UPDATE `progress` SET `sessionId`='$sessionid', `email`='$data' WHERE `ID`='$msisdn'  ";
 }
 
  $progress=$conn->prepare($progressq);
  $progress->execute();
}



function insertTransaction($amount,$donor_name,$walletno,$network,$TRAFFIC_ID) {
include('include/connect.php');


//Generate Client transaction ID
$clienttrans = "BAKE" . rand(100000, 999999);
$clienttransid = $clienttrans;

$status="PROGRESS";

  $progressq="INSERT INTO `transactions`(`clientid`, `ussdtrafficid`, `donor_name`, `amount`, `status`, `reference`, `telcotransid`, `wallet_num`, `wallet_network`) VALUES ('$clienttransid','$TRAFFIC_ID','$donor_name','$amount','$status','donation payment','NULL','$walletno','$network')";
  $progress=$conn->prepare($progressq);
  $progress->execute();

  sendMobileMoney($clienttransid,$walletno,$network,$amount,$TRAFFIC_ID);
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

function insertvolunteer($full_name,$mobile_number,$age,$email) {
include('include/connect.php');

  $progressq="INSERT INTO `volunteers`(`full_name`, `mobile_number`, `age`, `email`) VALUES ('$full_name','$mobile_number','$age','$email')";
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
    $time= date("Y/m/d h:i:s");
    $reciepients= $msisdn;
    $message= $message;
    $from= 'your_senderid';
    $frog_username= 'your_frog_username';
    $frog_password= 'your_frog_password';
    

   //using the FROG legacy API to send simple SMS message
    $messageApi='https://frog.wigal.com.gh/ismsweb/sendmsg';

    $params='username='.$frog_username.'&password='.$frog_password.'&from='.$from.'&to='.$reciepients.'&message='.$message;
    alllogs_log("SMS Parameter log on " , $time , $params);

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$messageApi);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$params);
    $return=curl_exec($ch);
    // @file_put_contents('logs.txt',"\n" . "SMS request log on ". $time . "=> " . $return . "\n",FILE_APPEND);
    alllogs_log("SMS request log on " , $time , $return);

    curl_close($ch);
}


function sendEmai($email,$message) 
{
  include('include/connect.php');
    $time= date("Y/m/d h:i:s");
    $reciepients= $email;
    $message= $message;
    $from= 'your_senderid';
    $frog_username= 'your_frog_username';
    $frog_password= 'your_frog_password';
    $mesgid = rand(10, 99);

   //using the FROG V2 API to send simple Email message
    $messageApi='https://frog.wigal.com.gh/api/v2/sendmsg/';

    $messagedata = array(
    'username'=> $frog_username,
    'password'=> $frog_password,
    'senderid'=> $from,
    'destinations'=> [
            [
              'destination'=> $reciepients,
              'msgid'=> $mesgid 
            ]
        ],
    'message'=> $message,
    'service'=> 'EMAIL',
    'subject'=> 'Thank you Message',
    'fromemail'=> 'support@wigal.com.gh', 
    'smstype'=> ''
    );

  $jsonbody = json_encode($messagedata); 
  alllogs_log("Email Parameter log on " , $time , $jsonbody);
  
  $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $messageApi,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $jsonbody,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Accept-Encoding: UTF-8'
      ),
    ));

    $response = curl_exec($curl);

    // @file_put_contents('logs.txt',"\n" . "Email request log on ". $time . "=> " . $response . "\n",FILE_APPEND);
    alllogs_log("Email request log on " , $time , $response);

    curl_close($curl);
    // echo $response;
}

function sendVoice($mobile_number,$message) 
{
  include('include/connect.php');
    $time= date("Y/m/d h:i:s");
    $reciepients= $mobile_number;
    $message= $message;
    $from= 'your_senderid';
    $frog_username= 'your_frog_username';
    $frog_password= 'your_frog_password';
    $mesgid = rand(10, 99);

   //using the FROG V2 API to send simple Voice message
    $messageApi='https://frog.wigal.com.gh/api/v2/sendmsg/';

    $messagedata = array(
    'username'=> $frog_username,
    'password'=> $frog_password,
    'senderid'=> $from,
    'destinations'=> [
            [
              'destination'=> $reciepients,
              'msgid'=> $mesgid 
            ]
        ],
    'message'=> $message,
    'service'=> 'VOICE',
    'subject'=> 'Thank you Message',
    'fromemail'=> '', 
    'smstype'=> ''
    );

  $jsonbody = json_encode($messagedata); 
  alllogs_log("Voice Parameter log on " , $time , $jsonbody);

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $messageApi,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $jsonbody,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Accept-Encoding: UTF-8'
      ),
    ));

    $response = curl_exec($curl);

    // @file_put_contents('logs.txt',"\n" . "Voice request log on ". $time . "=> " . $response . "\n",FILE_APPEND);
    alllogs_log("Voice request log on " , $time , $response);


    curl_close($curl);
    // echo $response;
}


function sendMobileMoney($clienttransid,$walletno,$network,$amount,$TRAFFIC_ID)
{
  $time=date("Y/m/d h:i:s");

  $params = array(
          'amount'=>$amount,
          'appid'=>"your_redde_appid",
          'clientreference'=>"Donation Payment by client",
          'clienttransid'=>$clienttransid,
          'description'=>"Donation Payment",
          'nickname'=>"Bakeside Campaign",
          'paymentoption'=>$network,
          'vouchercode'=>"",
          'walletnumber'=>$walletno,
          "ussdtrafficid"=>$TRAFFIC_ID  //Provide this if you want the OTP to be removed from your Payment prompt
          );
  $jsonbody = json_encode($params);

  // @file_put_contents('logs.txt',"Payment request log on ". $time ."=> ". $jsonbody . "\n",FILE_APPEND);
  alllogs_log("Payment request log on " , $time , $jsonbody);


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
   // @file_put_contents('logs.txt',"Payment response log on ".$time ."=> ". $response . "\n",FILE_APPEND);
   alllogs_log("Payment response log on " , $time , $response);

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
