<?php

$dbConnect = new SQLite3('/volume1/docker/supertuxkart/stkservers.db');

require('tracks.en.php');

function convert($time) {
    $minutes = floor($time / 60);
    $seconds = floor($time % 60);
    $decimalplaces = ($time - $minutes * 60 - $seconds) * 10000;
    return sprintf('%02d:%02d.%04d', $minutes, $seconds, $decimalplaces);
}

function displayRow($trackName, $icon, $reverse, $mode, $laps, $username, $result, $misc = 0) {
    $trackName = htmlspecialchars($trackName);
    $icon = htmlspecialchars($icon);
    $reverse = htmlspecialchars($reverse);
    $mode = htmlspecialchars($mode);
    $laps = htmlspecialchars($laps);
    $username = htmlspecialchars($username);
    $misc = htmlspecialchars($misc);

    echo "<tr>";
    echo "<td>{$trackName}</td>";
    echo "<td><img class='trackicon' src='./media/tracks/{$icon}.png'></td>";
    echo "<td>{$reverse}</td>";
    echo "<td>{$mode}</td>";
    echo "<td>{$laps}</td>";
    echo "<td>";
    if (!empty($_GET['venue'])) {
        echo "<img class='headingicon' style='float:left;' src='./media/medal_rank{$misc}.png'>";
    }
    echo "{$username}</td>";
    echo "<td>" . convert($result) . "</td>";    
    if (empty($_GET['venue']) && ($_GET['recent'] == "true")) {
        $misc = nl2br(str_replace(" ", "\n", $misc));
        echo "<td>{$misc}</td>";
    }    
    if (empty($_GET['venue'])) {
        $unique = htmlspecialchars($_GET['unique']);
        echo "<td><a href='?venue={$icon}&amp;laps={$laps}&amp;mode={$mode}&amp;reverse={$reverse}&amp;unique={$unique}'>more</a></td>";
    }
    echo "</tr>\n";
}
?>

<!doctype html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>SuperTuxKart Highscore Table</title>
    <link rel="icon" type="image/png"  href="./media/icon.png">
    <link rel="stylesheet" href="./css/default.css" />
    <link rel="stylesheet" href="./css/more-style.css" />
</head>
<body style="background-color:#F5EFE0">
    <form action="" method="GET">
        <table style='max-width:1200px; margin-left: auto; margin-right: auto'>
            <tr>
                <td style='padding-top: 0.1em;padding-bottom: 0.1em;'>
                    <div class="records_form" style='text-align: left;'>
                        <b>Track:</b><br>
                        <select name="venue">
                            <option value="">Choose Venue...</option>
                            <?php foreach ($track as $misc): 
                                $venueID = htmlspecialchars($misc[0], ENT_QUOTES, 'UTF-8');
                                $venueTitle = htmlspecialchars($misc[1], ENT_QUOTES, 'UTF-8'); ?>
                                <option value="<?= $venueID ?>" <?= isset($_GET['venue']) && $_GET['venue'] == $venueID ? "selected" : "" ?>><?= $venueTitle ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </td>
                <td style='padding-top: 0.1em;padding-bottom: 0.1em;'>
                    <div class="records_form" style='text-align: left;'>
                        <b>Mode:</b><br>
                        <input type="radio" name="mode" value="normal" <?= $_GET['mode'] == "normal" ? "checked" : "" ?>> Normal
                        <input type="radio" name="mode" value="time-trial" <?= $_GET['mode'] == "time-trial" ? "checked" : "" ?>> Time-Trial
                        <input type="radio" name="mode" value="" <?= isset($_GET['mode']) && $_GET['mode'] == "" ? "checked" : "" ?>> Both
                    </div>
                </td>
                <td style='padding-top: 0.1em;padding-bottom: 0.1em;'>
                    <div class="records_form" style='text-align: left;'>
                        <b>Recent Records:</b><br>
                        <input type="radio" name="recent" value="true" <?= $_GET['recent'] == "true" ? "checked" : "" ?>> Yes
                        <input type="radio" name="recent" value="false" <?= $_GET['recent'] == "false" ? "checked" : "" ?>> No
                    </div>
                </td>
                <td rowspan="2" style='padding-top: 0.1em;padding-bottom: 0.1em;'><div class="btn"><input type="submit" value="Submit"></div></td>
            </tr>
            <tr>
                <td style='padding-top: 0.1em;padding-bottom: 0.1em;'>
                    <div class="records_form" style='text-align: left;'>
                        <b>Laps:</b><br>
                        <select name="laps">
                            <option value="">Select Laps...</option>
                            <?php for ($misc = 1; $misc <= 50; $misc++): 
                                $misc = htmlspecialchars($misc); ?>
                                <option value="<?= $misc ?>" <?= isset($_GET['laps']) && $_GET['laps'] == $misc ? "selected" : "" ?>><?= $misc ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </td>
                <td style='padding-top: 0.1em;padding-bottom: 0.1em;'>
                    <div class="records_form" style='text-align: left;'>
                        <b>Direction:</b><br>
                        <input type="radio" name="reverse" value="normal" <?= $_GET['reverse'] == "normal" ? "checked" : "" ?>> Normal
                        <input type="radio" name="reverse" value="reverse" <?= $_GET['reverse'] == "reverse" ? "checked" : "" ?>> Reverse
                        <input type="radio" name="reverse" value="" <?= isset($_GET['reverse']) && $_GET['reverse'] == "" ? "checked" : "" ?>> Both
                    </div>
                </td>
                <td style='padding-top: 0.1em;padding-bottom: 0.1em;'>
                    <div class="records_form" style='text-align: left;'>
                        <b>Unique Players:</b><br>
                        <input type="radio" name="unique" value="true" <?= $_GET['unique'] == "true" ? "checked" : "" ?>> Yes
                        <input type="radio" name="unique" value="false" <?= $_GET['unique'] == "false" ? "checked" : "" ?>> No
                    </div>
                </td>
            </tr>
        </table>
    </form>
    <div class="marginauto box95 tablebox">
        <table>
            <tr>
                <th><div class="recordheading">Track<img class="headingicon" src="./media/track.png"></div></th>
                <th><div class="recordheading">Icon<img class="headingicon" src="./media/icon.png"></div></th>
                <th><div class="recordheading">Direction<img class="headingicon" src="./media/direction.png"></div></th>
                <th><div class="recordheading">Mode<img class="headingicon" src="./media/mode.png"></div></th>
                <th><div class="recordheading">Laps<img class="headingicon" src="./media/laps.png"></div></th>
                <th><div class="recordheading">Username<img class="headingicon" src="./media/username.png"></div></th>
                <th><div class="recordheading">Result<img class="headingicon" src="./media/result.png"></div></th>
                <?php if (empty($_GET['venue']) && $_GET['recent'] == "true"): ?>
                    <th><div class="recordheading">Date<img class="headingicon" src="./media/calendar.png"></div></th>
                <?php endif; ?>
                <?php if (empty($_GET['venue'])): ?>
                    <th><div class="recordheading">More</div></th>
                <?php endif; ?>
            </tr>
	
<?php

// Access to database?
if(!$dbConnect) {
	echo $dbConnect->lastErrorMsg();
	} else {

// Set Parameter
    $reverse = $_GET['reverse'];
    $mode = $_GET['mode'];
    $venue = $_GET['venue'] ?? '';
    $laps = $_GET['laps'];
    $unique = isset($_GET['unique']) && $_GET['unique'] == "true" ? 'true' : '';
    if (empty($venue)) {
        if($_GET['recent']!="true"){

// Display current Leader for all tracks, using normal modes and default laps if not set

            if(!isset($reverse)) $reverse = 'normal';
            if(!isset($mode)) $mode = 'normal';
            foreach ($track as $currentTrack) {
                $laps = $_GET['laps']; $laps = $laps ?: $currentTrack[2];
                $stmt = $dbConnect->prepare("
                    SELECT username, reverse, mode, result
                    FROM v1_server_config_results
                    WHERE venue = :venue "
                    . (empty($reverse) ? "" : "AND reverse = :reverse ")
                    . (empty($mode) ? "" : "AND mode = :mode ") .
                    "AND laps = :laps
                    ORDER BY result ASC LIMIT 1
                ");
                $stmt->bindValue(':venue', $currentTrack[0], SQLITE3_TEXT);
                if(!empty($reverse)) $stmt->bindValue(':reverse', $reverse, SQLITE3_TEXT);
                if(!empty($mode)) $stmt->bindValue(':mode', $mode, SQLITE3_TEXT);
                $stmt->bindValue(':laps', $laps, SQLITE3_INTEGER);
                $result = $stmt->execute();

                $record = $result->fetchArray(SQLITE3_ASSOC);

                if (isset($record['result'])) {
                    displayRow(
                        $currentTrack[1],
                        $currentTrack[0],
                        $reverse ?: $record['reverse'],
                        $mode ?: $record['mode'],
                        $laps,
                        $record['username'],
                        $record['result']
                    );
                }
            }
        } else {

// Display 25 latest Highscores over all tracks, respecting different modes and number of laps

            $stmt = $dbConnect->prepare("
                SELECT MAX(time), username, venue, reverse, mode, laps, MIN(result)
                FROM v1_server_config_results
                WHERE username NOT NULL "
                . (empty($reverse) ? "" : "AND reverse = :reverse ")
                . (empty($mode) ? "" : "AND mode = :mode ")
                . (empty($laps) ? "" : "AND laps = :laps ") .
                "GROUP BY venue, reverse, mode, laps
                ORDER BY MAX(time) DESC LIMIT 25
            ");
            if(!empty($reverse)) $stmt->bindValue(':reverse', $reverse, SQLITE3_TEXT);
            if(!empty($mode)) $stmt->bindValue(':mode', $mode, SQLITE3_TEXT);
            if(!empty($laps)) $stmt->bindValue(':laps', $laps, SQLITE3_INTEGER);
            $result = $stmt->execute();

            while ($record = $result->fetchArray(SQLITE3_ASSOC)) {
                $tracknr=array_search($record['venue'], array_column($track,0));
                if($tracknr!=false) {
                    displayRow(
                        $track[$tracknr][1],
                        $record['venue'],
                        $record['reverse'],
                        $record['mode'],
                        $record['laps'],
                        $record['username'],
                        $record['MIN(result)'],
                        $record['MAX(time)']
                    );
                }
            }
        }
    } else {

// Display Top 10 for specific track

        $tracknr=array_search($venue, array_column($track,0));
        $laps = $laps ?: $track[$tracknr][2];

        $stmt = $dbConnect->prepare("
            SELECT username, reverse, mode, "
            . (empty($unique) ? "result, ": "MIN(result), ") .
            "ROW_NUMBER() OVER(ORDER BY result ASC) as '#'
            FROM v1_server_config_results
            WHERE venue = :venue "
            . (empty($reverse) ? "" :"AND reverse = :reverse ")		
            . (empty($mode) ? "" :"AND mode = :mode ") .
            "AND laps = :laps "
            . (empty($unique) ? "": "GROUP BY username ") .
            "LIMIT 10
        ");
        $stmt->bindValue(':venue', $venue, SQLITE3_TEXT);
        if(!empty($reverse)) $stmt->bindValue(':reverse', $reverse, SQLITE3_TEXT);
        if(!empty($mode)) $stmt->bindValue(':mode', $mode, SQLITE3_TEXT);
        $stmt->bindValue(':laps', $laps, SQLITE3_INTEGER);
        $result = $stmt->execute();
	
        while ($record = $result->fetchArray(SQLITE3_ASSOC)) {
            displayRow(
            	$track[$tracknr][1],
                $venue,
                $record['reverse'],
                $record['mode'],
                $laps,
                $record['username'],            
                empty($unique)?$record['result']:$record['MIN(result)'],
                $record['#']
            );
        }
    }
}
?>
</table>
</div>
</body>
</html>
