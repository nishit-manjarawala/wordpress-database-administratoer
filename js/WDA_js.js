jQuery(document).ready(function(){
	var WDA_ajaxurl=WDA_ajax_post_ajax.WDA_ajaxurl;
	var text_area_val='';
	jQuery('.WDA-Editable-Label').dblclick(function(){
		var WDA_table_name = jQuery(this).attr('data-table-name');
		var WDA_primary_label = jQuery(this).attr('data-primary-label');
		var WDA_primary_value = jQuery(this).attr('data-primary-value');
		var WDA_operation_label = jQuery(this).attr('data-operation-label');
		if(WDA_table_name!='' && WDA_primary_label!='' && WDA_primary_value!='' && WDA_operation_label!=''){
			jQuery.ajax({
				url:WDA_ajaxurl,
				type:'post',
				data:{action:'WDA_Get_Column_of_Table',WDA_table_name:WDA_table_name,WDA_primary_label:WDA_primary_label,WDA_primary_value:WDA_primary_value,WDA_operation_label:WDA_operation_label},
				dataType:'json',
				success:function(response){
					console.log(response);
					if(response.status==true){
						text_area_val=response.content;
						jQuery('.WDA-Editable-Text-'+WDA_operation_label+'-'+WDA_primary_value).val(text_area_val);						
						jQuery('.WDA-Editable-Label-'+WDA_operation_label+'-'+WDA_primary_value).css('display','none');
						jQuery('.WDA-Editable-Text-'+WDA_operation_label+'-'+WDA_primary_value).css('display','block').focus();						
					}else{
						WDA_alert("error","Error :","Something Wrong");
					}
				}
			});
			
		}else{
			WDA_alert("error","Error :","Current selection does not contain a unique column. Grid edit, checkbox, Edit, Copy and Delete features are not available.");
		}
	});
	
	jQuery('.WDA-Editable-Text').focusout(function(){
		var WDA_table_name = jQuery(this).attr('data-table-name');
		var WDA_primary_label = jQuery(this).attr('data-primary-label');
		var WDA_primary_value = jQuery(this).attr('data-primary-value');
		var WDA_operation_label = jQuery(this).attr('data-operation-label');
		if(text_area_val==jQuery(this).val()){
			jQuery('.WDA-Editable-Label-'+WDA_operation_label+'-'+WDA_primary_value).css('display','block');
			jQuery('.WDA-Editable-Text-'+WDA_operation_label+'-'+WDA_primary_value).css('display','none');
		}else{
			var WDA_new_content=jQuery(this).val();
			jQuery.ajax({
				url:WDA_ajaxurl,
				type:'post',
				data:{action:'WDA_update_Column_of_Table',WDA_table_name:WDA_table_name,WDA_primary_label:WDA_primary_label,WDA_primary_value:WDA_primary_value,WDA_operation_label:WDA_operation_label,WDA_new_content:WDA_new_content},
				dataType:'json',
				success:function(response){
					if(response.status==true){
						jQuery('.WDA-Editable-Label-'+WDA_operation_label+'-'+WDA_primary_value).text(response.content);
						jQuery('.WDA-Editable-Label-'+WDA_operation_label+'-'+WDA_primary_value).css('display','block');
						jQuery('.WDA-Editable-Text-'+WDA_operation_label+'-'+WDA_primary_value).css('display','none');
						WDA_alert("success","Successfully Updated","Your Content successfully updated");
					}else{
						jQuery('.WDA-Editable-Label-'+WDA_operation_label+'-'+WDA_primary_value).css('display','block');
						jQuery('.WDA-Editable-Text-'+WDA_operation_label+'-'+WDA_primary_value).css('display','none');
						WDA_alert("error","Error :",response.error_message);
					}
				}
			});
		}
	});
	
	/****search on all column****/
	jQuery('#WDA_search_all_column_submit').click(function(){
		WDA_search_all_column();
	});
	
	jQuery("#WDA_search_all_column").keyup(function(event) {
		if (event.keyCode === 13) {
			WDA_search_all_column();
		}
	});
	
	function WDA_search_all_column(){
		var queryParameters = {}, queryString = location.search.substring(1),re = /([^&=]+)=([^&]*)/g, m;
		while (m = re.exec(queryString)) {
			queryParameters[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
		}
		queryParameters['WDA_search'] = jQuery('#WDA_search_all_column').val();
		queryParameters['cpage'] = 1;//when searching it create page no 1
		location.search = jQuery.param(queryParameters);
	}
	/****end search on all column****/
	
	function WDA_alert(type,title,message){
		if(type=='success'){
			jQuery('#WDA_popup h2').css('color','green');
		}else if(type=='error'){
			jQuery('#WDA_popup h2').css('color','red');
		}		
		jQuery('#WDA_popup h2').html(title);
		jQuery('#WDA_popup .content').html(message);		
		location.href='#WDA_popup';
	}
	
	function WDA_Array_field_popup(){
		location.href='#WDA_Array_field_popup';
	}
	
	jQuery("select[name^=WDA_search_oprator]").change(function(){
		if(jQuery(this).val()=="IN" || jQuery(this).val()=="NOT IN"){
			WDA_Array_field_popup();
		}
	});
	
	jQuery('.WDA_red-add-button').click(function(e){
		e.preventDefault();
		jQuery(this).before( jQuery('<br/><input type="text" name="WDA_array_text_box_input[]" />') );
	});
	
	jQuery('#WDA_add-for-array-input').click(function(){
		
	});
});