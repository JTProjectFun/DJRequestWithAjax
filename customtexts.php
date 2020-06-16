<?php

// passwordsuccessString
$passwordsuccessString="Your password was changed successfully";

// passwordmismatchString
$passwordmismatchString="The passwords supplied do not match. Please try again.";

// successString - The message displayed to a user when a request submission is successful.
$successString="THANK-YOU! Your request submission was successful.";

// bannedString - The message displayed to a user when they've been banned.
$bannedString="I'm sorry. You cannot make any more requests.";

// toomanyString - The message displayed to a user when the maximum number of requests for an event has been made. 
$toomanyString="You have already submitted the maximum number of requests set by the administrator";

// sqlerrorString - The message displayed to a user when there's a problem with the database.
$sqlerrorString="There seems to be a problem with the database.";

// tooshortString - The message displayed to a user when the text they enter into a field is too short. %FIELD% will be replaced by the name of the field causing the message
$tooshortString="There was a problem with the %FIELD% field. Please try again.";

// toomanyuserString - The message displayed to a user when they've used up their allocation of requests.
$toomanyuserString="";

// floodalertString - This text informs the user they must wait between request submissions. $UNITS% will display minutes or seconds. %AMOUNT% will give the number of time units.
$floodalertString="You may only make one request every %AMOUNT% %UNITS%. Try again soon.";

// fieldblankString - This text informs the user they must not leave the %FIELD% name blank. %FIELD% will be replaced by the name of the field they left blank.
$fieldblankString="The %FIELD% field cannot be left blank";

// fieldtoolongString - This text informs the user the text they entered in the %FIELD% was too long. %FIELD% will be replaced by the name of the field.
$fieldtoolongString="The %FIELD% you entered is too long. Please use less than 64 characters.";

// norequestsString - This text informs the user there have been no requests made.
$norequestsString="There have been no requests made yet.";

// 
$numrequestsString="";

//
$errormsgString="";

//
$warningmsgString="";

// forgotpassemailString - This text forms a template for sending forgotten password emails. %COMPANYNAME% will be replaced by the name of the company. %LINK% will be replaced with a unique link
// to reset a system user's password. %ADMINREALNAME% will be replaced with the 'real name' of the superuser with the lowest user ID
$forgotpassemailString="This is an email in response to your request for login details at %COMPANYNAME%. \n To reset your password, please visit this link (or copy & paste it into a web browser):"
." %LINK% . \n Best regards, %ADMINREALNAME%";


?>
