<?php
include('include/connect.php');
include('functions.php');

$MSISDN=$_GET['msisdn'];
$SESSION_ID=$_GET['sessionid'];
$NETWORKID=$_GET['network'];
$MODE=$_GET['mode'];
$DATA=$_GET['userdata'];
$USERNAME= $_GET['username'];
$TRAFFIC_ID= $_GET['trafficid'];

$TIME=date("Y/m/d h:i:s");
$today=date("Y-m-d");
$RESPONSE_DATA="";
$mobile_moneyApi="https://api.reddeonline.com/v1/receive";
$nickname="bakeside";

$welcome="Welcome to Bakeside Campaign.^Select your Option:^^1. Donate ^2. Volunteer";

//STEP ONE CHECK IF ITS START OF SESSION
if ($MODE=="start")
{
deleteTracking($MSISDN);
insertTracking($MSISDN,$SESSION_ID,$MODE,$USERNAME,$TIME,'1','1');
$RESPONSE_DATA="$NETWORKID|MORE|$MSISDN|$SESSION_ID|$welcome|$USERNAME|$TRAFFIC_ID";

echo $RESPONSE_DATA;

deleteProgress($MSISDN);
insertProgress($MSISDN,$SESSION_ID,'','','',$NETWORKID,'','','','');
}
else
 {
  $row =getTracking($MSISDN);
  $userData=$row['userData'];
  $track=$row['track'];
  $rowopt = getProgress($MSISDN);
  $option=$rowopt['option'];
  $donor_name=$rowopt['donor_name'];
  $amount=$rowopt['amount'];
  $network=$rowopt['network'];
  $walletno=$rowopt['walletno'];
  $volunteer_name=$rowopt['volunteer_name'];
  $age=$rowopt['age'];
  $email=$rowopt['email'];

    switch ($track)
    {
      case "1":
              if($DATA == '1'){
                        updateTracking($MSISDN,$SESSION_ID,$MODE,$USERNAME,$TIME,$DATA,'2');
                        $RESPONSE_DATA = "$NETWORKID|MORE|$MSISDN|$SESSION_ID|Enter your Full Name (First - Middle - Surname) to donate:|$USERNAME |$TRAFFIC_ID";
                        echo htmlspecialchars($RESPONSE_DATA);
                       updateProgress($MSISDN,$SESSION_ID,"option","Donate");
                    }
                elseif ($DATA == '2') {
                        updateTracking($MSISDN,$SESSION_ID,$MODE,$USERNAME,$TIME,$DATA,'2');
                        $RESPONSE_DATA = "$NETWORKID|MORE|$MSISDN|$SESSION_ID|Enter your Full Name (First - Middle - Surname) to Volunteer:|$USERNAME |$TRAFFIC_ID";
                          echo htmlspecialchars($RESPONSE_DATA);
                         updateProgress($MSISDN,$SESSION_ID,"option","Volunteer");
                      }
                  else{
                     updateTracking($MSISDN,$SESSION_ID,$MODE,$USERNAME,$TIME,$DATA,'1');
                     $RESPONSE_DATA = "$NETWORKID|MORE|$MSISDN|$SESSION_ID|$welcome|$USERNAME |$TRAFFIC_ID";
                      echo htmlspecialchars($RESPONSE_DATA);
                   }
        break;

        case "2":
                if ($option == 'Donate') {
                     updateTracking($MSISDN,$SESSION_ID,$MODE,$USERNAME,$TIME,$DATA,'3');
                     $RESPONSE_DATA = "$NETWORKID|MORE|$MSISDN|$SESSION_ID|Enter Amount to Donate: |$USERNAME |$TRAFFIC_ID";
                     echo htmlspecialchars($RESPONSE_DATA);
                    updateProgress($MSISDN,$SESSION_ID,"donor_name",$DATA);
                    }
                  elseif($option == 'Volunteer') {

                     updateTracking($MSISDN,$SESSION_ID,$MODE,$USERNAME,$TIME,$DATA,'3');
                     $RESPONSE_DATA = "$NETWORKID|MORE|$MSISDN|$SESSION_ID|Enter Age:|$USERNAME |$TRAFFIC_ID";
                     echo htmlspecialchars($RESPONSE_DATA);
                     updateProgress($MSISDN,$SESSION_ID,"volunteer_name",$DATA);
                    }
          break;

          case "3":
              if ($option == 'Donate') {
                  if(is_numeric($DATA)) {
                    $fetchdis = getProgress($MSISDN);
                    $walletno=$fetchdis['ID'];
                    $donor_name=$fetchdis['donor_name'];
                    $network=$fetchdis['network'];

                       updateTracking($MSISDN,$SESSION_ID,$MODE,$USERNAME,$TIME,$DATA,'4');
                       $RESPONSE_DATA = "$NETWORKID|MORE|$MSISDN|$SESSION_ID|Confirm Donation Summary^Name: $donor_name ^Amount: GHS $DATA^$network Wallet Num: $walletno ^^1. Yes^2. No |$USERNAME |$TRAFFIC_ID";
                       echo htmlspecialchars($RESPONSE_DATA);
                      updateProgress($MSISDN,$SESSION_ID,"amount",$DATA);
                  }
                    else {
                      updateTracking($MSISDN,$SESSION_ID,$MODE,$USERNAME,$TIME,$DATA,'3');
                       $RESPONSE_DATA = "$NETWORKID|MORE|$MSISDN|$SESSION_ID|Invalid Amount inputted, Kindly ensure you enter a valid numeric amount.|$USERNAME |$TRAFFIC_ID";
                       echo $RESPONSE_DATA;
                      updateProgress($MSISDN,$SESSION_ID,"amount",$DATA);
                    }
              }
              elseif($option == 'Volunteer') {
                if (is_numeric($DATA)) {
                
                     updateTracking($MSISDN,$SESSION_ID,$MODE,$USERNAME,$TIME,$DATA,'4');
                     $RESPONSE_DATA = "$NETWORKID|MORE|$MSISDN|$SESSION_ID|Enter your Email Address:|$USERNAME |$TRAFFIC_ID";
                     echo $RESPONSE_DATA;
                     updateProgress($MSISDN,$SESSION_ID,"age",$DATA);
                   }
                   else{
                    updateTracking($MSISDN,$SESSION_ID,$MODE,$USERNAME,$TIME,$DATA,'3');
                     $RESPONSE_DATA = "$NETWORKID|MORE|$MSISDN|$SESSION_ID|Invalid Age inputted, Kindly ensure you enter a valid Age.|$USERNAME |$TRAFFIC_ID";
                     echo $RESPONSE_DATA;
                    updateProgress($MSISDN,$SESSION_ID,"age",$DATA);
                  }
              }
            break;
            
            case "4":
                if ($option == 'Donate') {
                        if($DATA=='1'){
                          $fetchdis = getProgress($MSISDN);
                          $walletno=$fetchdis['ID'];
                          $donor_name=$fetchdis['donor_name'];
                          $network=$fetchdis['network'];
                             updateTracking($MSISDN,$SESSION_ID,$MODE,$USERNAME,$TIME,$DATA,'5');
                            $RESPONSE_DATA = "$NETWORKID|END|$MSISDN|$SESSION_ID|Your payment request has been initiated. Kindly approve the payment prompt from $network.|$USERNAME |$TRAFFIC_ID";
                            echo $RESPONSE_DATA;
                            session_write_close();  //close the USSD seesion 
                            fastcgi_finish_request();    //close the USSD seesion
                            sleep(3); //wait for 3 seconds to iniate the payment request
                            insertTransaction($amount,$donor_name,$walletno,$network,$TRAFFIC_ID);
                        }

                        else{
                          updateTracking($MSISDN,$SESSION_ID,$MODE,$USERNAME,$TIME,$DATA,'5');
                          $RESPONSE_DATA = "$NETWORKID|END|$MSISDN|$SESSION_ID|Payment Cancelled.Thanks for using our system|$USERNAME |$TRAFFIC_ID";
                          echo $RESPONSE_DATA;
                        }
                        
                    }
                  elseif($option== 'Volunteer') {
                                                         
                       if (filter_var($DATA, FILTER_VALIDATE_EMAIL)) {
                       $fetchdis = getProgress($MSISDN);
                       $volunteer_name=$fetchdis['volunteer_name'];
                       $age=$fetchdis['age'];

                          updateTracking($MSISDN,$SESSION_ID,$MODE,$USERNAME,$TIME,$DATA,'5');
                          $RESPONSE_DATA = "$NETWORKID|MORE|$MSISDN|$SESSION_ID|Confirm Summary^Name: $volunteer_name ^Age: $age^Email: $DATA ^^1. Yes^2. No |$USERNAME |$TRAFFIC_ID";
                          echo $RESPONSE_DATA;
                         updateProgress($MSISDN,$SESSION_ID,"email",$DATA);
                       }
                       else{
                        updateTracking($MSISDN,$SESSION_ID,$MODE,$USERNAME,$TIME,$DATA,'4');
                         $RESPONSE_DATA = "$NETWORKID|MORE|$MSISDN|$SESSION_ID|Invalid Email inputted, Kindly ensure you enter a valid Email.|$USERNAME |$TRAFFIC_ID";
                         echo $RESPONSE_DATA;
                        updateProgress($MSISDN,$SESSION_ID,"email",$DATA);
                       }
                  }
              break;

                case "5":
                      if ($option=='Volunteer') {
                          if($DATA=="1"){
                            $fetchdis = getProgress($MSISDN);
                            $full_name=$fetchdis['volunteer_name'];
                            $mobile_number=$fetchdis['ID'];
                            $age=$fetchdis['age'];
                            $email=$fetchdis['email'];
                            $message= "Thank you $full_name with the email address $email for voluntering with Bakeside.";

                            updateTracking($MSISDN,$SESSION_ID,$MODE,$USERNAME,$TIME,$DATA,'6');
                            insertvolunteer($full_name,$mobile_number,$age,$email);
                            sendSMS($mobile_number,$message);
                            sendEmai($email,$message);
                            sendVoice($mobile_number,$message);
                            $RESPONSE_DATA = "$NETWORKID|END|$MSISDN|$SESSION_ID|Thank you for Volunteering with Bakeside.|$USERNAME |$TRAFFIC_ID";
                             echo $RESPONSE_DATA;

                            }else{
                               updateTracking($MSISDN,$SESSION_ID,$MODE,$USERNAME,$TIME,$DATA,'6');
                               $RESPONSE_DATA = "$NETWORKID|END|$MSISDN|$SESSION_ID|Request Cancelled. Thanks for using our system.|$USERNAME |$TRAFFIC_ID";
                               echo $RESPONSE_DATA;
                            }
                        }
                  break;
      }
    }

?>
