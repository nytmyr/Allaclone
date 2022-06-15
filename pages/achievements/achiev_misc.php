<?php

$itemtype = (isset($_GET['itemtype']) ? $_GET['itemtype'] : "null");

$page_title = "Miscellaneous Achievements";
$print_buffer .= "<table class=''><tr valign=top><td>";
$print_buffer .= "<h1>Choose a category</h1><ul style='text-align:left'>";
$print_buffer .= "<li><a href=?a=achiev_misc&itemtype=death id='death'>Death Achievements</a>";
$print_buffer .= "<li><a href=?a=achiev_misc&itemtype=pvp id='death'>PVP Achievements</a>";
$print_buffer .= "<li><a href=?a=achiev_misc&itemtype=skills id='death'>Skill Achievements</a>";
$print_buffer .= "<li><a href=?a=achiev_misc&itemtype=tradeskills id='death'>Tradeskill Achievements</a>";
$print_buffer .= "<li><a href=?a=achiev_misc&itemtype=delevel id='death'>Delevel Achievements</a>";
$print_buffer .= "</ul>";

if (isset($itemtype) && $itemtype != "null") {
	if ($itemtype == "death") {
		$print_buffer .= '<h1>' . "Death Achievements" . '</h1>';
		$query = "
			SELECT 
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
			WHERE d.`key` LIKE 'FirstServerDeath'
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
		$columns = mysqli_num_fields($result);
		
		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=center>First Death</td>
			<td style='font-weight:bold' align=right>Date</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td align=right>" . $row["Time"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		
		$query = "
			SELECT 
				cd.id AS CharID,
				CASE
					WHEN cd.`name` LIKE '%-deleted-%'
						THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
					ELSE cd.`name` 
				END AS CharName,
				CAST(d.`value` AS INT) AS 'Count'
			FROM 
				$data_buckets_table d
			INNER JOIN $character_table cd ON cd.id = SUBSTRING(d.`key`, 12, 10)
			WHERE d.`key` LIKE 'DeathCount-%'
			ORDER BY Count DESC
			LIMIT 1
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=center>Most Deaths</td>
			<td style='font-weight:bold' align=center>Count</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td align=center>" . number_format($row["Count"]) . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		
		$query = "
			SELECT 
				CAST(SUBSTRING(d.`key`, INSTR(d.`key`,'Die')+3,INSTR(d.`key`,'Times')-INSTR(d.`key`,'Die')-3) AS INT) AS 'Count',
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
			WHERE d.`key` LIKE 'FirstToDie%'
			ORDER BY `Count` ASC
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=center>To Die x Times</td>
			<td style='font-weight:bold' align=center>Name</td>
			<td style='font-weight:bold' align=right>Date</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center>" . number_format($row["Count"]) . "</td>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td style='font-weight:bold' align=right>" . $row["Time"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		
		$query = "
			SELECT 
				cd.id AS CharID,
				CASE
					WHEN cd.`name` LIKE '%-deleted-%'
						THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
					ELSE cd.`name` 
				END AS CharName,
				CAST(SUBSTRING(d.`key`, INSTR(d.`key`,'#')+1) AS INT) AS 'Count',
				SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time'
			FROM 
				$data_buckets_table d
			INNER JOIN $character_table cd ON cd.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,':')-1)
			WHERE d.`key` LIKE 'ServerDeath#%'
			ORDER BY Count ASC
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=center>Server Death</td>
			<td style='font-weight:bold' align=center>Name</td>
			<td style='font-weight:bold' align=right>Date</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center>#" . number_format($row["Count"]) . "</td>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td style='font-weight:bold' align=right>" . $row["Time"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		
		$query = "
			SELECT 
				CAST(d.`value` AS INT) AS 'Count'
			FROM 
				$data_buckets_table d
			WHERE d.`key` LIKE 'ServerDeathCount'
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=center>Server Total Death Count</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center>" . number_format($row["Count"]) . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		
		$print_buffer .= "</td><td width=0% nowrap>";
		$print_buffer .= "</td></tr></table>";
	}
	
	if ($itemtype == "pvp") {
		$print_buffer .= '<h1>' . "PVP Achievements" . '</h1>';
		$query = "
			SELECT 
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
			WHERE d.`key` LIKE 'FirstPVPKill'
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
		$columns = mysqli_num_fields($result);
		
		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=center>First PVP Kill</td>
			<td style='font-weight:bold' align=right>Date</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td align=right>" . $row["Time"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		
		$query = "
			SELECT 
				cd.id AS CharID,
				CASE
					WHEN cd.`name` LIKE '%-deleted-%'
						THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
					ELSE cd.`name` 
				END AS CharName,
				CAST(d.`value` AS INT) AS 'Count'
			FROM 
				$data_buckets_table d
			INNER JOIN $character_table cd ON cd.id = SUBSTRING(d.`key`, 14, 10)
			WHERE d.`key` LIKE 'PVPKillCount%'
			ORDER BY Count DESC
			LIMIT 1
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=center>Most Kills</td>
			<td style='font-weight:bold' align=center>Count</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td align=center>" . number_format($row["Count"]) . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		
		$query = "
			SELECT 
				CAST(SUBSTRING(d.`key`, INSTR(d.`key`,'#')+1) AS INT) AS 'Count',
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
			WHERE d.`key` LIKE 'FirstPVPKillCount#%'
			ORDER BY `Count` ASC
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=center>To Get x Kills</td>
			<td style='font-weight:bold' align=center>Name</td>
			<td style='font-weight:bold' align=right>Date</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center>" . number_format($row["Count"]) . "</td>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td style='font-weight:bold' align=right>" . $row["Time"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		
		$query = "
			SELECT 
				cd.id AS CharID,
				CASE
					WHEN cd.`name` LIKE '%-deleted-%'
						THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
					ELSE cd.`name` 
				END AS CharName,
				CAST(SUBSTRING(d.`key`, INSTR(d.`key`,'#')+1) AS INT) AS 'Count',
				SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time'
			FROM 
				$data_buckets_table d
			INNER JOIN $character_table cd ON cd.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,':')-1)
			WHERE d.`key` LIKE 'ServerPVPKillCount#%'
			ORDER BY Count ASC
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=center>Server Kill</td>
			<td style='font-weight:bold' align=center>Name</td>
			<td style='font-weight:bold' align=right>Date</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center>#" . number_format($row["Count"]) . "</td>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td style='font-weight:bold' align=right>" . $row["Time"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		
		$query = "
			SELECT 
				CAST(d.`value` AS INT) AS 'Count'
			FROM 
				$data_buckets_table d
			WHERE d.`key` LIKE 'ServerPVPKillCount'
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());

		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=center>Server Total PVP Kill Count</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center>" . number_format($row["Count"]) . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		
		$print_buffer .= "</td><td width=0% nowrap>";
		$print_buffer .= "</td></tr></table>";
	}
	
	if ($itemtype == "skills") {
		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
		";
		
		$print_buffer .= '<h1>' . "First Maxed Skill Achievements" . '</h1>';
		$query = "
			SELECT 
				CASE
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 9
						THEN 'Bind Wound [210]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 16
						THEN 'Disarm [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 25
						THEN 'Feign Death [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 27
						THEN 'Forage [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 29
						THEN 'Hide [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 32
						THEN 'Mend [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 35
						THEN 'Pick Lock [210]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 39
						THEN 'Safe Fall [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 40
						THEN 'Sense Heading [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 42
						THEN 'Sneak [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 48
						THEN 'Pick Pockets [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 50
						THEN 'Swimming [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 51
						THEN 'Throwing [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 53
						THEN 'Tracking [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 55
						THEN 'Fishing [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 66
						THEN 'Alcohol Tolerance [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 67
						THEN 'Begging [200]'
					WHEN CAST(SUBSTRING(d.`key`, 15, 2) AS INT) = 71
						THEN 'Intimidation [200]'
					ELSE 'None'
				END AS SkillName,
				cd.id AS CharID,
					CASE
						WHEN cd.`name` LIKE '%-deleted-%'
							THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
						ELSE cd.`name` 
					END AS CharName,
				SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time'
			FROM data_buckets d
			INNER JOIN character_data cd ON cd.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,':')-1)
			WHERE d.`key` LIKE 'FirstSkillMax-%'
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
		$columns = mysqli_num_fields($result);

		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=left>". $row["SkillName"] . "</td>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td align=right>" . $row["Time"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		$print_buffer .= "</td><td width=0% nowrap>";
		$print_buffer .= "</td></tr></table>";
	}
	
	if ($itemtype == "tradeskills") {
		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
		";
		
		$print_buffer .= '<h1>' . "First Maxed Tradeskill Achievements" . '</h1>';
		$query = "
			SELECT 
				CASE
					WHEN CAST(SUBSTRING(d.`key`, 20, 2) AS INT) = 56
						THEN 'Make Poison [250]'
					WHEN CAST(SUBSTRING(d.`key`, 20, 2) AS INT) = 57
						THEN 'Tinkering [250]'
					WHEN CAST(SUBSTRING(d.`key`, 20, 2) AS INT) = 58
						THEN 'Research [250]'
					WHEN CAST(SUBSTRING(d.`key`, 20, 2) AS INT) = 59
						THEN 'Alchemy [250]'
					WHEN CAST(SUBSTRING(d.`key`, 20, 2) AS INT) = 60
						THEN 'Baking [250]'
					WHEN CAST(SUBSTRING(d.`key`, 20, 2) AS INT) = 61
						THEN 'Tailoring [250]'
					WHEN CAST(SUBSTRING(d.`key`, 20, 2) AS INT) = 63
						THEN 'Blacksmithing [250]'
					WHEN CAST(SUBSTRING(d.`key`, 20, 2) AS INT) = 64
						THEN 'Fletching [250]'
					WHEN CAST(SUBSTRING(d.`key`, 20, 2) AS INT) = 65
						THEN 'Brewing [250]'
					WHEN CAST(SUBSTRING(d.`key`, 20, 2) AS INT) = 68
						THEN 'Jewelry Making [250]'
					WHEN CAST(SUBSTRING(d.`key`, 20, 2) AS INT) = 69
						THEN 'Pottery [250]'
					ELSE 'None'
				END AS SkillName,
				cd.id AS CharID,
					CASE
						WHEN cd.`name` LIKE '%-deleted-%'
							THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
						ELSE cd.`name` 
					END AS CharName,
				SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time'
			FROM data_buckets d
			INNER JOIN character_data cd ON cd.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,':')-1)
			WHERE d.`key` LIKE 'FirstTradeskillMax-%'
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
		$columns = mysqli_num_fields($result);

		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=left>". $row["SkillName"] . "</td>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td align=right>" . $row["Time"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		$print_buffer .= "</td><td width=0% nowrap>";
		$print_buffer .= "</td></tr></table>";
	}
	
	if ($itemtype == "delevel") {
		$print_buffer .= '<h1>' . "Delevel Achievements" . '</h1>';
		$query = "
			SELECT 
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
			WHERE d.`key` LIKE 'ServerFirstDelevel'
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
		$columns = mysqli_num_fields($result);
		
		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=center>First Delevel</td>
			<td style='font-weight:bold' align=right>Date</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td align=right>" . $row["Time"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		
		$query = "
			SELECT 
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
			WHERE d.`key` LIKE 'ServerFirstDelevel65to10'
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
		$columns = mysqli_num_fields($result);
		
		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=center>First 65 to 10 Delevel</td>
			<td style='font-weight:bold' align=right>Date</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
					<td align=right>" . $row["Time"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		$print_buffer .= "</td><td width=0% nowrap>";
		$print_buffer .= "</td></tr></table>";
	}
}
?>
