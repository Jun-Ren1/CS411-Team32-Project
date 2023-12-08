<?php

require_once 'vendor/autoload.php';

session_start();

$clientID = '1066473816090-d79hu6srgu04n1md0e6a6s6eo62pmcka.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/login/login.php';

// Google API Client
$gclient = new Google_Client();

$gclient->setClientId($clientID);
$gclient->setClientSecret($clientSecret);
$gclient->setRedirectUri('http://localhost/login/login.php');

$gclient->addScope('email');
$gclient->addScope('profile');


$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "login_sample_db";

if(!$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)){

  die("failed to connect!"); 
}
