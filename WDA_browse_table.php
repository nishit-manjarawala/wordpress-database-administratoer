<div class="wrap">
	<?php
		global $wpdb;
		$WDA_Query="SELECT * FROM `".$_GET['WDA_table']."`";
		
		//minimize content for show
		function WDA_minimize_text_Content($content,$size){
			if(strlen($content) < $size){
				return $content;
			}
			return substr($content,0,$size)."...";			
		}
		
		$sort="ASC";
		//for update parameters of url
		function WDA_addOrUpdateUrlParam($name, $value,$sort_type=""){
			$params = $_GET;
			unset($params[$name]);
			$params[$name] = $value;
			if($sort_type!=""){
				$params['sort_type'] = $sort_type;
			}
			return basename($_SERVER['PHP_SELF']).'?'.http_build_query($params);
		}
		
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
	?>
	<a class='WDA_btn-back' href="<?= admin_url( 'options.php?page=WDA' ) ?>"><img src="<?= plugins_url('images/btn-back.png', __FILE__) ?>" style="width:50px;" /></a>
	<div id="WDA_print_query" class="notify notify-yellow"><?= $WDA_Query ?></div>
	
	<div>		
		<select name="WDA_items_per_page" onchange="location.href=this.value">
			<option <?= ($items_per_page==50 ? "selected"  :"" ) ?> value="<?= WDA_addOrUpdateUrlParam("WDA_items_per_page", 50) ?>">50</option>
			<option <?= ($items_per_page==100 ? "selected"  :"" ) ?> value="<?= WDA_addOrUpdateUrlParam("WDA_items_per_page", 100) ?>">100</option>
			<option <?= ($items_per_page==300 ? "selected"  :"" ) ?> value="<?= WDA_addOrUpdateUrlParam("WDA_items_per_page", 300) ?>">300</option>
			<option <?= ($items_per_page==500 ? "selected"  :"" ) ?> value="<?= WDA_addOrUpdateUrlParam("WDA_items_per_page", 500) ?>">500</option>
			<option <?= ($items_per_page==-1 ? "selected"  :"" ) ?> value="<?= WDA_addOrUpdateUrlParam("WDA_items_per_page", -1) ?>">ALL</option>
		</select>
	</div>
	
	<table class="WDA-tables-list">
		<tr>
			<th>Options</th>
			<?php
				$WDA_Columns_Labels=$wpdb->get_results("SELECT * FROM INFORMATION_SCHEMA.COLUMNS where TABLE_NAME='".$_GET['WDA_table']."'");
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
			$WDA_Query_Final_execute=$WDA_Query.$WDAorderby_Query.$WDA_Limit_Query;
			$WDA_Query_Rows=$wpdb->get_results($WDA_Query_Final_execute, OBJECT);
			echo"<script>document.getElementById('WDA_print_query').innerHTML='".$WDA_Query_Final_execute."';</script>";
			foreach($WDA_Query_Rows as $WDA_Query_Row){
		?>
			<tr>
				<td>option</td>
				<?php
					foreach($WDA_Coulumn_Label_Array as $WDA_Coulumn_Label){
						echo"<td>".WDA_minimize_text_Content($WDA_Query_Row->$WDA_Coulumn_Label,50)."</td>";
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
				'prev_text' => __('&laquo;'),
				'next_text' => __('&raquo;'),
				'total' => ceil($total / $items_per_page),
				'current' => $page
			));
			echo"</tr></td>";
		?>
	</table>
</div>