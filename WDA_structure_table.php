<div class="wrap">
<?php
	global $wpdb;
	$WDA_get_TABLE_ALL_LAble=WDA_get_TABLE_ALL_LAble($_GET['WDA_table']);
?>
	<table class="WDA-tables-list">
		<tr>
			<th>#</th>
			<th>Name</th>
			<th>Type</th>
			<th>Collation</th>
			<th>Null</th>
			<th>Default</th>
			<th>Comments</th>
			<th>Extra</th>
			<th>Action</th>
		</tr>
		<?php
		$WDA_col_num=1;
		foreach($WDA_get_TABLE_ALL_LAble as $WDA_LAble){
		?>
		<tr>
			<td><?= $WDA_col_num ?></td>
			<td><?= $WDA_LAble->COLUMN_NAME ?></td>
			<td><?= $WDA_LAble->COLUMN_TYPE ?></td>
			<td><?= $WDA_LAble->COLLATION_NAME ?></td>
			<td><?= $WDA_LAble->IS_NULLABLE ?></td>
			<td><?= $WDA_LAble->COLUMN_DEFAULT ?></td>
			<td><?= $WDA_LAble->COLUMN_COMMENT ?></td>
			<td><?= $WDA_LAble->EXTRA ?></td>
			<td>Action</td>
		</tr>
		<?php
			$WDA_col_num++;
		}
		?>
</div>