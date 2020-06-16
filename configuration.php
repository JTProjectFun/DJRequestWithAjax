<?php
// Your name or company name
$company_name = "Mr Deejay";

// where this is installed, with trailing slash e.g. http://www.mydomain.com/requests/
// only used in the generation of QR codes for now
$baseURL = "http://192.168.1.30/request/";

// full URL to your logo - recommended 840px wide
$logoURL = "https://www.mrdeejay.co.uk/wp/wp-content/uploads/2018/09/header-1.png";

// database details next
$host 		= "localhost"; // Your hostname
$username	= "vdjsongs"; // Your host username
$password	= "vdjsongs"; // Your host password
$db		= "vdjsongs"; // Your database name

// Session timeout - default 120 minutes. How long a user can be logged in for before they have to login again
$session_timeout=60;

// Flood Period - how many seconds must elapse before a user can make consecutive requests. Default 30.
$flood_period=10;

// Over hours - how long an event 'day' can overrun by (to allow requests to be made after midnight). Default 2 hours
$over_hours=2;

// If set to 1, events will expire by default
$willexpire=1;

// If set to 1, requests will be shown by default.

$show_requests=1;

// MaxUserAge - users older than this (in days) will be purged from the database
$maxUserAge=90;

// MaxUserRequestDefault - how many requests a user can make by default.  0 == unlimited
$maxUserRequestDefault=0;


// MaxRequestDefault - how many requests an event can have by default.  0 == unlimited
$maxRequestDefault=0;

// don't edit anything below here
$record = "";
?>
