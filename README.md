# ussd_api

Introducing a PHP sample script for seamless integration with the WIGAL USSD API. This script provides developers with easy access to WIGAL services, including FROG for SMS, EMAIL, and VOICE messaging, as well as Reddeonline for Payment Solutions.

To benefit from our bulk SMS solutions using FROG, kindly register for a FROG account at: https://frog.wigal.com.gh/login. For payment solutions, sign up with Reddeonline here: https://app.reddeonline.com/register.

We have included text documents (Logs Folder, logs.txt, and Transactions.txt) to capture log files for troubleshooting purposes. The "db_script" folder contains the SQL script for easy database setup.

Please note that the maximum number of characters for a USSD page should not exceed 160 characters, including white spaces. Messages exceeding this limit will be truncated. Also, avoid using special characters like "$<&" in the "USERDATA" field, as they may interfere with the display of USSD menus.

We have introduced a new feature for the payment service (REDDE). WIGAL clients integrating with our USSD short code can now remove the OTP prompt by providing the USSD Traffic ID. Remember to close your USSD session before initiating the payment request to remove the OTP prompt. Failure to do so will result in the OTP not being removed from the payment request.