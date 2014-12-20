<?php
// Sendgrid-remove-email.php
//
// Removes an email address from a given Recipient List by name
// 
// Created by Michael Meluso, 2014. 
// www.michaelmeluso.com

function removeFromList($address, $list) {
    require "sendgrid-global-vars.php";

    $params = array(
        'api_user'  => $user,
        'api_key'   => $pass,
        'email'     => $address,
        'list'      => $list
    );
    
    $successMsg = "<p class=\"sgSuccess\">User with email address \"" . $address . "\" was successfully removed from list \"";
    foreach ($list as $value) {
        $listsStr = $listsStr + $value + ", ";
    }
    
    $request =  $url.'newsletter/lists/email/delete.json';
    // Generate curl request
    $session = curl_init($request);
    // Tell curl to use HTTP POST
    curl_setopt ($session, CURLOPT_POST, true);
    // Tell curl that this is the body of the POST
    curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
    // Tell curl not to return headers, but do return the response
    curl_setopt($session, CURLOPT_HEADER, false);
    // Tell PHP not to use SSLv3 (instead opting for TLS)
    curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    
    // obtain response
    $response = curl_exec($session);
    curl_close($session);
    
    if (strpos($response, "\"removed\"") !== FALSE) {
        return $successMsg . $list . "\".</p>";
    } else {
        $target = $params["list"];
        return "<p class=\"sgFailure\">Removing address \"" . $address . "\" from list \"" . $target . "\" failed: " . $response . "</p>";
    }
};
?>