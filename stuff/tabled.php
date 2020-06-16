<?php 
header('Content-Type: text/html; charset=iso-8859-1');

function loadVDJstuff($file) {
    $i = 0;
    $xml = simplexml_load_file($file) or die("Error: could not load database file");
    foreach ($xml as $song) {
        if (trim($song->Tags['Author']) && trim($song->Tags['Author']) != "acapella") {
            $songlist[$i][0] = trim($song->Tags['Author']);
            $songlist[$i][1] = trim($song->Tags['Title']);
            $songlist[$i][2] = trim($song->Tags['Year']);
            $songlist[$i][3] = trim($song['FilePath']);
            $songlist[$i][4] = trim($song->Infos['PlayCount']);
            $songlist[$i][5] = trim($song->Infos['SongLength']);
            $i++;
        }
    }
    return $songlist;
}

$tracklist = loadVDJstuff("database.xml");


function dropThe($theThing) {
	$theThing = preg_replace('/^the\s/i', '', $theThing);
	return $theThing;
}

if (isset($_GET['siteID'])) {
	$siteID = htmlspecialchars(stripslashes($_GET['siteID']));
	switch ($siteID) {
		case 'ck':
			$returnText = 'Crazy-"K"';
			$returnURL = 'http://www.crazyk.co.uk/';
			break;
		case 'urb':
			$returnText = 'Ultimate Rodeo Bulls';
			$returnURL = 'http://www.ultimaterodeobulls.co.uk/';
			break;
		case 'bdcc':
			$returnText = "Barn Dance Caller Centre";
			$returnURL = 'http://www.barndancecallercentre.co.uk/';
			break;
		case 'bbb':
			$returnText = "Bucking Bronco Bulls";
			$returnURL = 'http://www.buckingbroncobulls.co.uk/';
			break;
		case 'rbd':
			$returnText = "Rodeo Bulls Direct";
			$returnURL = 'http://rodeobullsdirect.co.uk/';
			break;
		case 'wrb':
			$returnText = "Wacky Rodeo Bulls";
			$returnURL = 'http://www.wackyrodeobulls.co.uk/';
			break;
	}
}
?>
<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="iso-8859-1" />
    <title>Track Filtering Example</title>
	<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.min.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
	<script type="text/javascript" charset="utf8" src="jquery.dataTables.js"></script>
	<script>
		$(document).ready( function () {
			$('#tracklist').DataTable( {
				"pageLength": 25,
				"search": {
					"caseInsensitive": true
				}
			});

			$( "#filterBox" ).focus();

		} );

		function returnToSite() {
			if (document.referrer == "") {
				window.location = "<?php echo $returnURL; ?>"
			} else {
				history.back()
			}
			return false
		}
	</script>
	<style>
        html, body, div, h1 
        {
            padding: 0;
            margin: 0;
            font-family: Arial;
			font-size: 10pt;
            }
        h1 {
            font-size: x-large;            
            color: navy;
            background: whitesmoke;
            text-align: center;
            padding: 3px;
			margin-bottom:15px;
        }
        .returnLink {
			text-align:center;
			padding:5px auto;
			font-weight:bold;
			color: navy;
		}
		#ScrollBox {
            width:90%;
			padding:10px;
			margin:10px auto;
            border: 2px solid silver;
            border-radius: 4px;
            box-shadow: 3px 3px 6px #535353;
          }
    </style>
</head>
    <body>
		<h1>Track Filtering Example</h1>
		<?php if (isset($returnText)) echo '<div class="returnLink"><a href="#" onclick="returnToSite();">Click here to return to ' . $returnText . '</a></div>' . PHP_EOL; ?>
		<div id="ScrollBox">
			<table id="tracklist" class="display">
				<thead>
					<tr>
						<th>Artist</th>
						<th>Title</th>
						<th>Year</th>
						<th>Playcount</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach($tracklist as $key => $track) {
						if (isset($track[0])) $artist = $track[0];
						if (isset($track[1])) $title = $track[1];
						if (isset($track[2])) $year = $track[2];
						if (isset($track[4])) $playcount = $track[4];
						echo '				<tr>' . PHP_EOL;
						echo '					<td>' . $artist . '</td>' . PHP_EOL;
						echo '					<td>' . $title . '</td>' . PHP_EOL;
						echo '					<td>' . $year . '</td>' . PHP_EOL;
						echo '					<td>' . $playcount . '</td>' . PHP_EOL;
						echo '				</tr>' . PHP_EOL;
						unset($artist);
						unset($title);
						unset($year);
						unset($track);
					}
					?>
				</tbody>
			</table>
		</div>
		<?php if (isset($returnText)) echo '<div class="returnLink"><a href="#" onclick="returnToSite();">Click here to return to ' . $returnText . '</a></div>' . PHP_EOL; ?>
    </body>
</html>
