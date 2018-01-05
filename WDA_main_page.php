<div class="wrap">
	<table class="WDA-tables-list">
		<tr>
			<th>Table</th>
			<th>Action</th>
			<th>Rows</th>
			<th>Type</th>
			<th>Collation</th>
			<th>Size</th>
			<th>Overhead</th>
		</tr>
	<?php
	global $wpdb;
		$WDA_database_table_list=$wpdb->get_results("SHOW TABLES");
		$WDA_total_table=$wpdb->num_rows;
		$WDA_total_rows=0;
		foreach($WDA_database_table_list as $WDA_database_table){
			echo"<tr>";
				echo"<td>".$WDA_database_table->Tables_in_plugin."</td>";
				echo"<td> <a class='table-action-a' href='#'>Browse</a> <a class='table-action-a' href='#'>Structure</a> <a class='table-action-a' href='#'>Search</a> <a class='table-action-a' href='#'>Insert</a> <a class='table-action-a' href='#'>Empty</a> <a class='table-action-a' href='#'>Drop</a></td>";
				$WDA_count_row_of_table=$wpdb->get_row("SELECT COUNT(*) as cnt FROM ".$WDA_database_table->Tables_in_plugin);
				$WDA_total_rows+=$WDA_count_row_of_table->cnt;
				echo"<td>".$WDA_count_row_of_table->cnt."</td>";
				echo"<td>InnoDB</td>";
				echo"<td>utf8mb4_unicode_ci</td>";
				echo"<td>0 Kib</td>";
				echo"<td>- Kib</td>";
			echo"</tr>";
		}
	?>
		<tr>
			<th><?= $WDA_total_table ?> tables</th>
			<th>Sum</th>
			<th><?= $WDA_total_rows ?></th>
			<th>Type</th>
			<th>Collation</th>
			<th>Size</th>
			<th>Overhead</th>
		</tr>
	</table>
</div>