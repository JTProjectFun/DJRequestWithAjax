use vdjsongs;
CREATE TABLE IF NOT EXISTS systemUser(id int NOT NULL AUTO_INCREMENT, timedate varchar(32), username varchar(32) NOT NULL UNIQUE, password varchar(64), realname varchar(64),
    email varchar(32), userlevel varchar(32), enabled int DEFAULT 1, PRIMARY KEY (id));
CREATE TABLE IF NOT EXISTS settings(session_timeout int default 120, flood_period int default 30,over_hours int default 2,willexpire int default 1,
    show_requests int default 1, maxUserAge int default 30, maxUserRequestDefault int default 0, maxRequestDefault int default 0);
CREATE TABLE IF NOT EXISTS requestkeys (id int NOT NULL AUTO_INCREMENT, timedate varchar(32),thekey varchar(16) NOT NULL UNIQUE, date varchar(32), 
    showrequests int(1), willexpire int(1), maxRequests int default 0, maxUserRequests int default 0, userid int, showMessages int default 1, PRIMARY KEY(id));
CREATE TABLE IF NOT EXISTS requests(id int NOT NULL AUTO_INCREMENT, timedate varchar(32), thekey varchar(16) NOT NULL, name varchar(64), artist varchar(64), 
    title varchar(64), message varchar(140), willplay int, played int, ipaddr varchar(20), uniqueid varchar(64), PRIMARY KEY(id));
CREATE TABLE IF NOT EXISTS requestusers(id int NOT NULL AUTO_INCREMENT, uniqueid varchar(16), ipaddr varchar(20), thekey varchar(16), banned int DEFAULT 0,
    createdTime varchar(32), numRequests int default 0, logintimes int DEFAULT 0, PRIMARY KEY(id));
INSERT INTO systemUser(timedate, username, password, realname, userlevel) VALUES ("1970-01-01 01:00:00", "admin", "6f404ea32dc5bb6e9b5ba3d82f36476dc5b10e9c", "Administrator", "3");
CREATE TABLE IF NOT EXISTS systemUserKey(id int NOT NULL, pkey varchar(32) NOT NULL, time varchar(32) NOT NULL, status varchar(7) NOT NULL, PRIMARY KEY (id));


