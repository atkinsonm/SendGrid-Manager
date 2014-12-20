<?php
// Sendgrid-add-list.php
//
// Adds a given list by name
// 
// Created by Michael Meluso, 2014. 
// www.michaelmeluso.com

function addList($list) {
    require "sendgrid-global-vars.php";
    
    $params = array(
        'api_user'  => $user,
        'api_key'   => $pass,
        'list'      => $list
    );
    
    $successMsg = "<p class=\"sgSuccess\">Recipient list \"" . $list . "\" was successfully added.</p>";
    
    $request =  $url.'newsletter/lists/add.json';
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
    
    if (strpos($response, "\"success\"") !== FALSE) {
        return $successMsg;
    } else {
        return "<p class=\"sgFailure\">Adding list \"" . $list . "\" failed: " . $response . "</p>";
    }
};
?>