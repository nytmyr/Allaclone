<?php
//$print_buffer .= "<div class = 'display_table container_div'>";
//$print_buffer .= "<h2 class='section_header'>Zones</h2>";
//$print_buffer .= "<ol>";
if (!isset($_GET["expansion"])) {
	$page_title = "Zones By Expansion";
	foreach ($expansion_zones as $expansion => $expansion_value) {
		$print_buffer .= "<li><a href = '?a=zone_expansions&expansion=" . $expansion . "'>" . $expansion_value . "</a></li>";
	}
} else {
	$expansion = $_GET["expansion"];
	$page_title = $expansion_zones[$expansion];
	$print_buffer .= "
		<table class=''><tr>
		<td style='font-weight:bold'>Zone</td>
		<td style='font-weight:bold'>ZEM</td>
	";
	$query = "SELECT `long_name`, `short_name`, `zone_exp_multiplier` FROM $zones_table WHERE `expansion` = '$expansion' AND `min_status` = '0' ORDER BY `long_name`";
	$result = db_mysql_query($query);
	while ($row = mysqli_fetch_array($result)) {
		$print_buffer .=
		"
		<tr>
			<td><a href='?a=zone&name=" . $row["short_name"] . "'>" . $row["long_name"] . "</a></td>
			<td>" . $row["zone_exp_multiplier"] * 100 . " %</td>
		</tr>
		";
	}
	$print_buffer .= "</table>";
	$print_buffer .= "</td><td width=0% nowrap>";
	$print_buffer .= "</td></tr></table>";
}
//$print_buffer .= "</ol>";
//$print_buffer .= "</div>";
?>