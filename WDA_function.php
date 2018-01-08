<?php
//minimize content for show
function WDA_minimize_text_Content($content,$size){
	if(strlen($content) < $size){
		return $content;
	}
	return substr($content,0,$size)."...";			
}
//update value of column for edit
add_action('wp_ajax_nopriv_WDA_update_Column_of_Table', 'WDA_update_Column_of_Table' );
add_action('wp_ajax_WDA_update_Column_of_Table', 'WDA_update_Column_of_Table' );
function WDA_update_Column_of_Table(){  
    global $wpdb;
	$request=$_POST;
	$update_array=array();
	$update_array[$request[WDA_operation_label]]=stripslashes($request['WDA_new_content']);
	$where_update_array=array();
	$where_update_array[$request['WDA_primary_label']]=$request['WDA_primary_value'];
	$result=array('status'=>false);
	if($wpdb->update($request['WDA_table_name'],$update_array,$where_update_array)){
		$result=array('status'=>true,'content'=>WDA_minimize_text_Content($update_array[$request[WDA_operation_label]],50));
	}else{
		$result['error_message']=$wpdb->last_error;
	}
	echo json_encode($result);
	die();
}
//get value for textarea for edit
add_action('wp_ajax_nopriv_WDA_Get_Column_of_Table', 'WDA_Get_Column_of_Table' );
add_action('wp_ajax_WDA_Get_Column_of_Table', 'WDA_Get_Column_of_Table' );
function WDA_Get_Column_of_Table(){
	global $wpdb;
	$request=$_POST;
	$result=array('status'=>false);
	$WDA_operation_label=$request['WDA_operation_label'];
	$content=$wpdb->get_row("SELECT ".$request['WDA_operation_label']." FROM ".$request['WDA_table_name']." where ".$request['WDA_primary_label']."='".$request['WDA_primary_value']."'");
	if($wpdb->num_rows==1){
		$result=array('status'=>true,'content'=>$content->$WDA_operation_label);
	}
	echo json_encode($result);
	die();
}

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
?>