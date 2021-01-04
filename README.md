# ussd_api
A PHP sample script that allows developers to integrate with the WIGAL USSD API without hustle. Note, this sample script comes with additional functions to integrate with the WIGAL services like FROG for SMS, EMAIL and VOICE SMS sending and Reddeonline for Payment Solutions.


If you have not registered for your FROG account for your bulk SMS solutions, kindly do so by using this link: https://frog.wigal.com.gh/login
And for your payment solutions, signup with Reddeonline here: https://app.reddeonline.com/register


We have also added text documents (thus; logs.txt and Transactions.txt) where all log files are captured for troubleshooting sake. The "db_script" folder contains the sql script to setup the databse for your integration.


NB: The maximum number of characters that can be displayed on a page must not be more than 160 Characters including White Spaces. If this condition is not met the message will be truncated and will not display the whole message.
Also note that special characters like “$<&” must not be part of the "USERDATA" passed, as this will not allow the USSD menus to be displayed.
