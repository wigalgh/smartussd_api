# smartussd_api
A PHP sample script that allows developers to integrate with the WIGAL Smart USSD API without hustle. Note, this sample scripts comes with additional functions to integrate WIGAL services like the iSMS for SMS sending and Redde for Mobile Money Payments.

We have also added text documents (thus; logs.txt and Transactions.txt) where all log files will be captured. The db_script folder contains the sql script to setup the databse for your integration.

If you have not registered for your iSMS account for your bulk SMS solutions, kindly do so by using this link: https://isms.wigalsolutions.com/ismsweb/#toregister

And for your payment solutions, signup with Redde Online here: https://app.reddeonline.com/register

NB: The maximum number of characters that can be displayed on a page must not be more than 160 Characters including White Spaces. If this condition is not met the message will be truncated and will not display the whole message.
Also note that special characters like “$<&” must not be part of the USERDATA passed, as this will not allow the USSD menus to be displayed.

