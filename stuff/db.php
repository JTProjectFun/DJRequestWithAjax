<?php

$xml = simplexml_load_file('database.xml') or die("Error: could not load database file");
foreach ($xml as $song) {

$artist = addslashes($song->Tags['Author']);
$title = addslashes($song->Tags['Title']);
$year = $song->Tags['Year'];
$filepath = addslashes($song['FilePath']);
$playcount = $song->Infos['PlayCount'];
$lengthsecs = $song->Infos['SongLength'];
if (empty ($lengthsecs)) { $lengthsecs=0; }
if (empty ($playcount)) { $playcount = 0; }
if (empty ($year)) { $year = 0; }
if (!empty ($artist)){
echo "\r\n";
echo 'INSERT INTO songs (artist, title, year, playcount, lengthsecs, filepath) VALUES ("'.$artist.'","'.$title.'","'.$year.'","'.$playcount.'","'.$lengthsecs.'","'.$filepath.'");';
}
}

 ?>



