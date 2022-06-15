<?php
$page_title = "Current Hot Zones";
$print_buffer .= "<table class=''><tr valign=top><td>";
$query = "
			SELECT 
			short_name, long_name, hotzone_range 
			FROM zone 
			WHERE hotzone != 0 
			ORDER BY hotzone_range
		";
		$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
		$columns = mysqli_num_fields($result);
		
		$print_buffer .= 
		"
			<table class='display_table datatable container_div'><tr>
			<td style='font-weight:bold' align=left>Zone Name</td>
			<td style='font-weight:bold' align=center>Level Range</td>
		";
		while ($row = mysqli_fetch_array($result)) {
			$print_buffer .=
			"
				<tr>
					<td><a href='?a=zone&name=" . $row["short_name"] . "''>" . $row["long_name"] . "</a></td>
					<td align=center>" . $row["hotzone_range"] . "</td>
				</tr>
			";
		}
		$print_buffer .= "</table>";
		$print_buffer .= "</td><td width=0% nowrap>";
		$print_buffer .= "</td></tr></table>";
?>