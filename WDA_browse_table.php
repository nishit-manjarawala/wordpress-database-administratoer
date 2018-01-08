<div class="wrap">
	<?php
		global $wpdb;
		$WDA_Query="SELECT * FROM `".$_GET['WDA_table']."`";		
		$sort="ASC";		
		
		/*pagination code logic*/
		$items_per_page = 50;
		if(isset($_GET['WDA_items_per_page'])){
			$items_per_page = $_GET['WDA_items_per_page'];
		}
		
		$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
		$offset = ( $page * $items_per_page ) - $items_per_page;

		$total_query = "SELECT COUNT(1) FROM (${WDA_Query}) AS combined_table";
		$total = $wpdb->get_var( $total_query );
		
		$WDA_Limit_Query='';
		if($items_per_page!=-1){
			$WDA_Limit_Query=' LIMIT '. $offset.', '. $items_per_page;
		}
		/*end pagination code logic*/
		
		/*Order By*/
		$WDAorderby_Query="";
		if(isset($_GET["sort_field"]) && isset($_GET["sort_type"])){
			$WDAorderby_Query=" ORDER BY ".$_GET["sort_field"]." ".$_GET["sort_type"]." ";
		}
		/*End order by*/
		
		/*Get Primary key of table*/
		$WDA_get_Primery_Key=$wpdb->get_row("SHOW INDEX FROM ".$_GET['WDA_table']." WHERE Key_name = 'PRIMARY'");		
		$WDA_Primery_key_name=$WDA_get_Primery_Key->Column_name;
		/**/
	?>
	<a class='WDA_btn-back' href="<?= admin_url( 'options.php?page=WDA' ) ?>"><img src="<?= plugins_url('images/btn-back.png', __FILE__) ?>" style="width:50px;" /></a>
	<div id="WDA_print_query" class="notify notify-yellow"><?= $WDA_Query ?></div>
	
	<div>		
		Number of rows: <select name="WDA_items_per_page" onchange="location.href=this.value">
			<option <?= ($items_per_page==50 ? "selected"  :"" ) ?> value="<?= WDA_addOrUpdateUrlParam("WDA_items_per_page", 50) ?>">50</option>
			<option <?= ($items_per_page==100 ? "selected"  :"" ) ?> value="<?= WDA_addOrUpdateUrlParam("WDA_items_per_page", 100) ?>">100</option>
			<option <?= ($items_per_page==300 ? "selected"  :"" ) ?> value="<?= WDA_addOrUpdateUrlParam("WDA_items_per_page", 300) ?>">300</option>
			<option <?= ($items_per_page==500 ? "selected"  :"" ) ?> value="<?= WDA_addOrUpdateUrlParam("WDA_items_per_page", 500) ?>">500</option>
			<option <?= ($items_per_page==-1 ? "selected"  :"" ) ?> value="<?= WDA_addOrUpdateUrlParam("WDA_items_per_page", -1) ?>">ALL</option>
		</select>
		
		| 
		
		Filter rows: <input type='text' name="WDA_search_all_column" id="WDA_search_all_column" /><input id="WDA_search_all_column_submit" type="submit" value="Search" />
	</div>
	
	<table class="WDA-tables-list">
		<tr>
			<th>Options</th>
			<?php
				$WDA_Columns_Labels=$wpdb->get_results("SELECT DISTINCT(COLUMN_NAME) FROM INFORMATION_SCHEMA.COLUMNS where TABLE_NAME='".$_GET['WDA_table']."'");
				$WDA_Coulumn_Label_Array=array();
				foreach($WDA_Columns_Labels as $WDA_Columns_Label){
					if(isset($_GET["sort_field"]) && isset($_GET["sort_type"]) && $_GET["sort_field"]==$WDA_Columns_Label->COLUMN_NAME && $_GET["sort_type"]=="ASC"){
						$sort="DESC";
					}else{
						$sort="ASC";
					}
					echo"<th><a href='".WDA_addOrUpdateUrlParam("sort_field", $WDA_Columns_Label->COLUMN_NAME,$sort)."'>".$WDA_Columns_Label->COLUMN_NAME."</a></th>";
					array_push($WDA_Coulumn_Label_Array,$WDA_Columns_Label->COLUMN_NAME);
				}
			?>
		</tr>
		<?php
			/*search in all column*/
			$WDA_search_Query='';
			if(isset($_GET['WDA_search']) && !empty($_GET['WDA_search'])){
				$WDA_search_Query=" WHERE ";
				$WDA_counter=0;
				foreach($WDA_Coulumn_Label_Array as $WDA_Coulumn_Label){
					if($WDA_counter==0)
						$WDA_search_Query.=" ".$WDA_Coulumn_Label." LIKE '%".$_GET['WDA_search']."%' ";
					else
						$WDA_search_Query.=" OR ".$WDA_Coulumn_Label." LIKE '%".$_GET['WDA_search']."%' ";
					$WDA_counter++;
				}
			}
			/*end search in all column*/
		
		
			$WDA_Query_Final_execute=$WDA_Query.$WDA_search_Query.$WDAorderby_Query.$WDA_Limit_Query;
			$WDA_Query_Rows=$wpdb->get_results($WDA_Query_Final_execute, OBJECT);
			?>
			<script>document.getElementById('WDA_print_query').innerHTML="<?= $WDA_Query_Final_execute ?>";</script>
			<?php
			foreach($WDA_Query_Rows as $WDA_Query_Row){
		?>
			<tr>
				<td>option</td>
				<?php
					foreach($WDA_Coulumn_Label_Array as $WDA_Coulumn_Label){
						echo"<td>";
						echo"<label class='WDA-Editable-Label WDA-Editable-Label-".$WDA_Coulumn_Label."-".$WDA_Query_Row->$WDA_Primery_key_name."' data-table-name='".$_GET['WDA_table']."' data-primary-label='".$WDA_Primery_key_name."' data-primary-value='".$WDA_Query_Row->$WDA_Primery_key_name."' data-operation-label='".$WDA_Coulumn_Label."' >".htmlspecialchars(WDA_minimize_text_Content($WDA_Query_Row->$WDA_Coulumn_Label,50))."</label>";
						echo"<textarea class='WDA-Editable-Text WDA-Editable-Text-".$WDA_Coulumn_Label."-".$WDA_Query_Row->$WDA_Primery_key_name."' style='display:none;' data-table-name='".$_GET['WDA_table']."' data-primary-label='".$WDA_Primery_key_name."' data-primary-value='".$WDA_Query_Row->$WDA_Primery_key_name."' data-operation-label='".$WDA_Coulumn_Label."'></textarea>";
						echo"</td>";
					}
				?>
			</tr>
		<?php
			}
			$colspan= count($WDA_Coulumn_Label_Array) + 1; 
			echo"<tr><td colspan='".$colspan."'>";
			echo paginate_links( array(
				'base' => add_query_arg( 'cpage', '%#%' ),
				'format' => '',
				'prev_text' => __('« Previous'),
				'next_text' => __('Next »'),
				'total' => ceil($total / $items_per_page),
				'current' => $page
			));
			echo"</tr></td>";
		?>
	</table>	
</div>