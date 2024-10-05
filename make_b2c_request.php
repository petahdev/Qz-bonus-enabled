<?php
// make_b2c_request.php
include 'get_access_token.php';

function makeMpesaB2CRequest($phoneNumber, $amount) {
    $accessToken = getMpesaAccessToken();

    $url = 'https://api.safaricom.co.ke/mpesa/b2c/v1/paymentrequest'; // Live URL
    // For sandbox: 'https://sandbox.safaricom.co.ke/mpesa/b2c/v1/paymentrequest'

    $headers = ['Authorization: Bearer ' . $accessToken, 'Content-Type: application/json'];

    $data = [
        'InitiatorName' => 'YourInitiatorName', // Replace with your initiator name
        'SecurityCredential' => 'YourSecurityCredential', // Replace with encrypted security credential
        'CommandID' => 'BusinessPayment', // Command ID for B2C request
        'Amount' => $amount,
        'PartyA' => '600000', // Replace with your MPESA short code
        'PartyB' => $phoneNumber, // Customer phone number (in format 2547XXXXXXXX)
        'Remarks' => 'Withdrawal',
        'QueueTimeOutURL' => 'https://yourdomain.com/mpesa_timeout.php', // URL for timeout callback
        'ResultURL' => 'https://yourdomain.com/mpesa_result.php', // URL for result callback
        'Occasion' => 'WithdrawalPayment'
    ];

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}

// Example: Call this function to send money
$phoneNumber = '2547XXXXXXXX'; // Replace with recipient's phone number
$amount = 1000; // Amount to send in Ksh
$response = makeMpesaB2CRequest($phoneNumber, $amount);
echo $response;
