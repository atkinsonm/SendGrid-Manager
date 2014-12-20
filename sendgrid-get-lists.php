<?php
// Sendgrid-get-lists.php
//
// List all Recipient Lists, or check if a particular List exists.
// 
// Created by Michael Meluso, 2014. 
// www.michaelmeluso.com

function getLists($queryString) {
    require "sendgrid-global-vars.php";

    $params = array(
        'api_user'  => $user,
        'api_key'   => $pass
      );
    
    if (!(is_null($queryString))) {
        $qArray = array(
            'list' => $queryString
        );
        $params = array_merge($params, $qArray); 
    }

    $request =  $url.'newsletter/lists/get.json';
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

    // Split string into list names and print
    $lists = explode('},{"list": "', $response);
    $lists[0] = substr($lists[0],11);
    $lists[count($lists)-1] = substr($lists[count($lists)-1],0,strlen($lists[count($lists)-1])-2);
    
    return $lists;
}

function countLists($queryString) {
    require "sendgrid-global-vars.php";

    $params = array(
        'api_user'  => $user,
        'api_key'   => $pass,
        'list'      => $queryString
      );
    
    $request =  $url.'newsletter/lists/email/count.json';
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

    if (strpos($response, "\"count\"") !== FALSE) {
        $value = substr($response, 9);
        return substr($value, 0, strlen($value)-1);
    } else {
        $target = $params["list"];
        return "<p class=\"sgFailure\">Obtaining email count from list \"" . $target . "\" failed: " . $response . "</p>";
    }
}

// Generate statistics link
function getStatURL($list) {
    $statBase = 'https://sendgrid.com/newsletter/listStatistics/id/';
    switch($list) {
        case "Aeronautics":
            $result = 19694480;
            break;
        case "Agricultural/AG PR":
            $result = 19693679;
            break;
        case "Architect/Specifier":
            $result = 19693489;
            break;
        case "Automotive":
            $result = 18674052;
            break;
        case "Builder/Remodeler/Home Improvement":
            $result = 19693599;
            break;
        case "Government Agency":
            $result = 19694002;
            break;
        case "HVAC/Plumber":
            $result = 19693611;
            break;
        case "Industrial/Engineer":
            $result = 19200524;
            break;
        case "Inspectors/Energy Auditors":
            $result = 19693676;
            break;
        case "Insulation/Windows":
            $result = 19693603;
            break;
        case "LO/MIT":
            $result = 19682684;
            break;
        case "LO/MIT Building Industry PR":
            $result = 19683053;
            break;
        case "LO/MIT Installers":
            $result = 19693486;
            break;
        case "LO/MIT Retail":
            $result = 19694835;
            break;
        case "Painter":
            $result = 19693629;
            break;
        case "Pest Control/Mold Removal":
            $result = 19694986;
            break;
        case "Plastics/Packaging":
            $result = 19694005;
            break;
        case "Roofer":
            $result = 19693667;
            break;
        case "SOLKOTE":
            $result = 19682699;
            break;
        case "Solkte Cutomers":
            $result = 18673932;
            break;
        default:
            return "https://sendgrid.com/newsletter/lists";
    }
    return $statBase . $result;
}

function getEmails($list, $queryString) {
    require "sendgrid-global-vars.php";

    $params = array(
        'api_user'  => $user,
        'api_key'   => $pass,
        'list'      => $list
      );
    
    if (!(is_null($queryString))) {
        $qArray = array(
            'email' => $queryString
        );
        $params = array_merge($params, $qArray); 
    }
    
    $request =  $url.'newsletter/lists/email/get.json';
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

    if (is_null($queryString)) {
        // Split string into list emails and print
        $lists = explode('},{"email": "', $response);
        $lists[0] = substr($lists[0],12);
        $lists[count($lists)-1] = substr($lists[count($lists)-1],0,strlen($lists[count($lists)-1])-2);
        $i = 0;
        foreach($lists as $entry) {
            $lists[$i] = substr($entry, 0, strpos($entry, '"'));
            $i = $i + 1;
        }
        return $lists;
    } else {
        if (strpos($response, "\"email\"") !== FALSE) {
            return $list;
        }
    }
}
?>