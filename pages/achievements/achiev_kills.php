<?php

$killtype = (isset($_GET['killtype']) ? $_GET['killtype'] : "null");

$page_title = "First Kill Achievements";
$print_buffer .= "<table class=''><tr valign=top><td>";
$print_buffer .= "<h1>Choose a category</h1><ul style='text-align:left'>";
$print_buffer .= "<li><a href=?a=achiev_kills&killtype=rare id='rare'>First Rare Kills</a>";
$print_buffer .= "<li><a href=?a=achiev_kills&killtype=raid id='raid'>First Raid Kills</a>";
$print_buffer .= "</ul>";

if (isset($killtype) && $killtype != "null") {
	if ($killtype == "rare") {
		$query = "
			SELECT 
				n.id AS NPCID, 
				n.`name` AS NPCName, 
				cd.id AS CharID, 
				CASE
					WHEN cd.`name` LIKE '%-deleted-%'
						THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
					ELSE cd.`name` 
				END AS CharName,
				z.`short_name` AS ZoneSN,
				z.`long_name` AS ZoneLN,
				SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time'
			FROM 
				$data_buckets_table d
			INNER JOIN $npc_types_table n ON n.id = SUBSTRING(d.`key`, INSTR(d.`key`,'-')+1)
			INNER JOIN $character_table cd ON cd.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,']')-INSTR(d.`value`,':')-1)
			INNER JOIN $zones_table z ON z.short_name = SUBSTRING(d.`value`, INSTR(d.`value`,']')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,']')-1)
			WHERE d.`key` LIKE 'FirstRareKill%' 
			ORDER BY ZoneLN ASC, NPCName ASC 
		";
	}
	if ($killtype == "raid") {
		$query = "
			SELECT 
				n.id AS NPCID, 
				n.`name` AS NPCName, 
				cd.id AS CharID, 
				CASE
					WHEN cd.`name` LIKE '%-deleted-%'
						THEN SUBSTRING(cd.`name`, 1, INSTR(cd.`name`, '-deleted-')-1)
					ELSE cd.`name` 
				END AS CharName, 
				z.`short_name` AS ZoneSN,
				z.`long_name` AS ZoneLN,
				SUBSTRING(d.`value`, INSTR(d.`value`,'|')+1) AS 'Time'
			FROM 
				$data_buckets_table d
			INNER JOIN $npc_types_table n ON n.id = SUBSTRING(d.`key`, INSTR(d.`key`,'-')+1)
			INNER JOIN $character_table cd ON cd.id = SUBSTRING(d.`value`, INSTR(d.`value`,':')+1,INSTR(d.`value`,']')-INSTR(d.`value`,':')-1)
			INNER JOIN $zones_table z ON z.short_name = SUBSTRING(d.`value`, INSTR(d.`value`,']')+1,INSTR(d.`value`,'|')-INSTR(d.`value`,']')-1)
			WHERE d.`key` LIKE 'FirstRaidKill%' 
			ORDER BY ZoneLN ASC, NPCName ASC 
		";
	}
	$result = db_mysql_query($query) or message_die('achiev_kills.php', 'MYSQL_QUERY', $query, mysqli_error());
	
	if ($killtype == "rare") {
			$print_buffer .= '<h1>' . "Rare Kills" . '</h1>';
		}
	if ($killtype == "raid") {
			$print_buffer .= '<h1>' . "Raid Kills" . '</h1>';
	}
	$print_buffer .= "
		<table class='display_table datatable container_div'><tr>
		<td style='font-weight:bold' align=center>NPC</td>
		<td style='font-weight:bold' align=center>Player</td>
		<td style='font-weight:bold' align=center>Zone</td>
		<td style='font-weight:bold' align=right>Time</td>
	";
	while ($row = mysqli_fetch_array($result)) {
		$print_buffer .=
		"
			<tr>
				<td align=center><a href='?a=npc&id=" . $row["NPCID"] . "'>" . $row["NPCName"] . "</a></td>
				<td align=center><a href='/charbrowser/index.php?page=character&char=" . $row["CharID"] . "'>" . $row["CharName"] . "</a></td>
				<td align=center><a href='?a=zone&name=" . $row["ZoneSN"] . "'>" . $row["ZoneLN"] . "</a></td>
				<td align=right>" . $row["Time"] . "</td>
			</tr>
		";
	}
	$print_buffer .= "</table>";
	$print_buffer .= "</td><td width=0% nowrap>";
	$print_buffer .= "</td></tr></table>";
}
?>
