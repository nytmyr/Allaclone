<?php

$page_title = "Shard Vendored Items";
$print_buffer .= "<table class=''><tr valign=top><td>";

$query = "
	SELECT 
		i.`id` AS ItemID,
		i.`Name` AS ItemName,
		m.`alt_currency_cost` AS ShardCost
	FROM $items_table i
	INNER JOIN $merchant_list_table m ON m.`item` = i.`id`
	WHERE m.`item` > 599999
	AND m.`probability` > 0
	ORDER BY ShardCost
	";
	
$result = db_mysql_query($query) or message_die('achiev_items.php', 'MYSQL_QUERY', $query, mysqli_error());
$print_buffer .= "
	<table class='display_table datatable container_div'><tr>
       <td style='font-weight:bold'>Item</td>
       <td style='font-weight:bold'>Shsrd Cost</td>
";
while ($row = mysqli_fetch_array($result)) {
	$print_buffer .=
	"
		<tr>
			<td align=center><a href='?a=item&id=" . $row["ItemID"] . "'>" . $row["ItemName"] . "</a></td>
			<td align=center>" . number_format($row["ShardCost"]) . "</td>
		</tr>
	";
}
$print_buffer .= "</table>";
$print_buffer .= "</td><td width=0% nowrap>";
$print_buffer .= "</td></tr></table>";