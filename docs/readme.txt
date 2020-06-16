How to use this app.
====================

Setting up the app
==================
A MySQL database is required for this web app.  Set up a new database called 'djrequests' and create a user with enough privileges to be able to use the database 
(GRANT, INSERT, UPDATE, DELETE, SELECT).
Into this new database, import the .sql file from the admin directory:  mysql -u USERNAME -p PASSWORD < install.sql

Initial Configuration
=====================
A new installation will only have one user, called 'admin' with a password of 'password'.

Log into the admin page by going to http://yoursite.com/requests/admin

Enter the username and password.  It's probably wise to change the password from the 'manage system users' page.

Events
======
Creating an event.  Every user of the front end of the system needs to log in.  To do this, they either scan a QR code containing a 
unique URL for the event, or they enter the 'key' for the event manually.

When creating a new event, a form will be displayed containing a newly generated random key. The key can be replaced with your own text.
"wednesday15" is more memorable than "sadfgdfg435267" for example.  In this form, select the date the key will be active from, and 
whether or not it can be used after the active date (this is the 'willexpire' setting).  There are also settings for the max number of 
requests per user, and a total number of requests for the event itself.

*** NOTE: Use ONLY letters (a-z, A-Z), numbers (0-9) and underscores (_) for event keys. No spaces, or anything else. ***

Every user who logs in for the first time is given a unique code. This is known as the 'user string' and is used to keep track of user's
requests, how many they make & the time between each request made.  Admins can list requests by event or by user.  Users can be banned 
(and all of their requests will be deleted), or all their requests can be deleted.  Requests can be deleted individually from the 
'manage requests' page too.

System Users
============
There are currently three levels of admin user.  

Level 1:  can log into the backend of the app & can see events assigned to them and their associated requests. 
They can mark requests as played, delete requests, ban users & delete all of one user's requests.

Level 2: Can log into the backend of the app, create events, see events they've created and are assigned to them and their associated requests.
They can mark requests as played, delete requests, ban users & delete all of one user's requests.

Level 3: Superuser. Can log into the backend of the app, create and administer (edit, disable & delete) other system users, see all events created by other users, administer
all requests on the app. They can mark requests as played, delete requests, ban users & delete all of one user's requests.

Settings
========
Session Timeout: How long an app user within the frontend can be inactive for before they are automatically logged out.

Flood Period: The minimum amount of time which must have elapsed between a user's request submissions. This setting is to help prevent spamming.

Max Requests per User: The default number of requests assigned to a user when a new event is created.

Max Requests per Event: The default number of requests assigned to an event when a new event is created.

Overrun (hours): How many hours into the next day an event key can be valid for, e.g. for a key to expire at 2am the following morning, set this to 2

Show Requests By Default: Whether requests will be shown by default when a new event is created.

Keys Expire By Default: Whether events created can be logged into after their initial active date.

Keep Users For (days): How long to keep event users in the database.
