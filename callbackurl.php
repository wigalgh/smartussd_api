<?php
	include('functions.php');
	$time=date("Y/m/d h:i:s");
	$data =  @file_get_contents('php://input'); //get callback response from Redde

	//log data into appropriate text file
	// @file_put_contents('logs.txt',"Callback response log on ". $time ."=> ". $data . "\n",FILE_APPEND);
	// @file_put_contents('Transactions.txt',"Callback response on ".$time ."=> ". $data. "\n" ,FILE_APPEND);
	alllogs_log("Callback response log on " , $time , $data);  //Create the log folder and write the logs according to their date

	//decode the data and proccess it
	$recieveddata = json_decode($data);
	if(!empty($data) && is_object($recieveddata))
	{
		insertmmTransaction($recieveddata->clienttransid,$recieveddata->clientreference,$recieveddata->telcotransid,$recieveddata->transactionid,$recieveddata->status,$recieveddata->statusdate,$recieveddata->reason);
	    updateTransaction($recieveddata->clienttransid,$recieveddata->status);
	}
?>
