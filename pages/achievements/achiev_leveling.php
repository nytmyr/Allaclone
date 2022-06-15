<?php

$level = (isset($_GET['level']) ? $_GET['level'] : "null");

#if (!is_numeric($level)) {
#    header("Location: ?a=achievements");
#    exit();
#}


$page_title = "Leveling Achievements";
$print_buffer .= "<table class=''><tr valign=top><td>";
$print_buffer .= "<h1>Choose a category</h1><ul style='text-align:left'>";
$print_buffer .= "<li><a href=?a=achiev_leveling&level=player id='playerlevel'>First Player to Level</a>";
$print_buffer .= "<li><a href=?a=achiev_leveling&level=class  id='classlevel'>First Class to Level</a>";
$print_buffer .= "<li><a href=?a=achiev_leveling&level=race  id='racelevel'>First Race to Level</a>";
$print_buffer .= "<li><a href=?a=achiev_leveling&level=nodeath id='nodeath'>First Player to Level Without Dying</a>";
$print_buffer .= "</ul>";

#if (isset($level) && ($level == "player" || $level == "class" || $level == "race")) {
if (isset($level) && $level != "null") {
	if($level == "player") {
		$Query = "
		SELECT 
			CAST(SUBSTRING(d.`key`, INSTR(d.`key`,'-')+1) AS INT) AS 'Level',
			cd.id AS CharID,
			CASE
				WHEN cd.`name` LIKE '%-deleted-%'
					THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
				ELSE cd.`name` 
			END AS CharName,
			SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time'
		FROM 
			$data_buckets_table d
		INNER JOIN $character_table cd ON cd.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,':')-1)
		WHERE d.`key` LIKE 'FirstLevel%' AND d.`key` NOT LIKE '%]%' AND d.`key` NOT LIKE '%[%'
		ORDER BY `Level` ASC";
	}
	if ($level == "class") {
		$Query = "
		SELECT 
			CAST(SUBSTRING(d.`key`, INSTR(d.`key`,'-')+1,INSTR(d.`key`,']')-INSTR(d.`key`,'-')-1) AS INT) AS 'Level',
			SUBSTRING(d.`key`, INSTR(d.`key`,']')+1) AS 'Class',
			cd.id AS CharID,
			CASE
				WHEN cd.`name` LIKE '%-deleted-%'
					THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
				ELSE cd.`name` 
			END AS CharName,
			SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time'
		FROM 
			$data_buckets_table d
		INNER JOIN $character_table cd ON cd.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,':')-1)
		WHERE d.`key` LIKE 'FirstLevel%' AND d.`key` LIKE '%]%'
		ORDER BY Class, `Level` ASC";
	}
	if ($level == "race") {
		$Query = "
		SELECT 
			CAST(SUBSTRING(d.`key`, INSTR(d.`key`,'-')+1,INSTR(d.`key`,'[')-INSTR(d.`key`,'-')-1) AS INT) AS 'Level',
			SUBSTRING(d.`key`, INSTR(d.`key`,'[')+1) AS 'Race',
			cd.id AS CharID,
			CASE
				WHEN cd.`name` LIKE '%-deleted-%'
					THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
				ELSE cd.`name` 
			END AS CharName,
			SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time'
		FROM 
			$data_buckets_table d
		INNER JOIN $character_table cd ON cd.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,':')-1)
		WHERE d.`key` LIKE 'FirstLevel%' AND d.`key` LIKE '%[%'
		ORDER BY Race, `Level` ASC";
	}
	if ($level == "nodeath") {
		$Query = "
		SELECT 
			CAST(SUBSTRING(d.`key`, INSTR(d.`key`,'First')+7,INSTR(d.`key`,'No')-INSTR(d.`key`,'First')-5) AS INT) AS 'Level',
			cd.id AS CharID,
			CASE
				WHEN cd.`name` LIKE '%-deleted-%'
					THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
				ELSE cd.`name` 
			END AS CharName,
			SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time'
		FROM 
			$data_buckets_table d
		INNER JOIN $character_table cd ON cd.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,':')-1)
		WHERE d.`key` LIKE '%NoDeath%'
		ORDER BY `Level` ASC";
	}
    $result = db_mysql_query($Query);
    if (!$result) {
        $print_buffer .= 'Could not run query: ' . mysqli_error();
        exit;
    }

	if ($level == "player") {
		$print_buffer .= '<h1>' . "First Player to Level" . '</h1>';
	}
	if ($level == "class") {
		$print_buffer .= '<h1>' . "First Class to Level" . '</h1>';
	}
	if ($level == "race") {
		$print_buffer .= '<h1>' . "First Race to Level" . '</h1>';
	}
	if ($level == "nodeath") {
		$print_buffer .= '<h1>' . "First Without Dying" . '</h1>';
	}
    $print_buffer .= "<table class='display_table datatable container_div'><tr>";
    $print_buffer .= "<td style='font-weight:bold' align=center>Level</td>";
	if ($level == "class") {
		$print_buffer .= "<td style='font-weight:bold' align=center>Class</td>";		
	}
	if ($level == "race") {
		$print_buffer .= "<td style='font-weight:bold' align=center>Race</td>";		
	}
    $print_buffer .= "<td style='font-weight:bold' align=center>Character</td>";
    $print_buffer .= "<td style='font-weight:bold' align=right>Date</td>";

    while ($row = mysqli_fetch_array($result)) {
        $print_buffer .= "<tr>";
		$print_buffer .= "<td align=center>" . number_format($row["Level"]) . "</td>";
		if ($level == "class") {
			$print_buffer .= "<td align=center>" . $row["Class"] . "</td>";	
		}
		if ($level == "race") {
			$print_buffer .= "<td align=center>" . $row["Race"] . "</td>";	
		}
		$print_buffer .= "<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>";
		$print_buffer .= "<td style='font-weight:bold' align=right>" . $row["Time"] . "</td>";
        $print_buffer .= "</tr>";

    }
    $print_buffer .= "</table>";
	$print_buffer .= "</td><td width=0% nowrap>";
	$print_buffer .= "</td></tr></table>";
}
?>