jQuery(document).ready(function(){
	/*create prototype for trim*/
	String.prototype.rtrim = function (s) {
		if (s == undefined)
			s = '\\s';
		return this.replace(new RegExp("[" + s + "]*$"), '');
	};
	
	String.prototype.ltrim = function (s) {
		if (s == undefined)
			s = '\\s';
		return this.replace(new RegExp("^[" + s + "]*"), '');
	};
	/*end prototype for trim*/
	
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
	
	function WDA_BETWEEN_field_popup(){
		location.href='#WDA_BETWEEN_field_popup';
	}
	
	var element_index=0;
	jQuery("select[name^=WDA_search_oprator]").change(function(){
		if(jQuery(this).val()=="IN" || jQuery(this).val()=="NOT IN"){
			WDA_Array_field_popup();
		}else if(jQuery(this).val()=="BETWEEN" || jQuery(this).val()=="NOT BETWEEN"){
			WDA_BETWEEN_field_popup();
		}
		element_index=jQuery(this).attr('data-index');
	});
	
	jQuery('.WDA_red-add-button').click(function(e){
		e.preventDefault();
		jQuery(this).before( jQuery('<br/><input type="text" name="WDA_array_text_box_input[]" />') );
	});	
	
	jQuery('#WDA_add-for-array-input').click(function(){
		var value_str='';
		jQuery('input[name^=WDA_array_text_box_input]').each(function(){
			value_str+=jQuery(this).val()+',';
		});
		value_str=value_str.rtrim(',');
		jQuery('input[name^=WDA_search_right_Value][data-index='+element_index+']').val(value_str);
		var k_counter=0;
		jQuery('input[name^=WDA_array_text_box_input]').each(function(){
			if(k_counter==0)
				jQuery(this).val('');
			else
				jQuery(this).remove();
			k_counter++;
		});
		jQuery('.group-textbox-array').find('br').remove();
		location.href='#';		
	});
	
	jQuery('#WDA_BETWEEN_field_popup-input').click(function(){
		var value_str='';
		value_str=jQuery('input[name=WDA_BETWEEN_field_popup_min_Value]').val()+","+jQuery('input[name=WDA_BETWEEN_field_popup_max_Value]').val();
		jQuery('input[name^=WDA_search_right_Value][data-index='+element_index+']').val(value_str);
		jQuery('input[name=WDA_BETWEEN_field_popup_min_Value]').val('');
		jQuery('input[name=WDA_BETWEEN_field_popup_max_Value]').val('');
		location.href='#';
	});
	
	jQuery('.WDA_truncate_database_table').click(function(e){
		e.preventDefault();
		if(confirm('You are about to TRUNCATE a complete table! Do you really want to execute "TRUNCATE ` '+jQuery(this).attr('data-table_name')+' `"?')){
			jQuery.ajax({
				url:WDA_ajaxurl,
				type:'post',
				data:{action:'WDA_truncate_database_table',table:jQuery(this).attr('data-table_name')},
				dataType:'json',
				success:function(result){
					if(result.status==true){
						WDA_alert("success","Successfully Truncate:",result.message);
					}else{
						WDA_alert("error","Error :",result.message);
					}
				}
			});
		}
	});
	
	jQuery('.WDA_delete_database_table').click(function(e){
		e.preventDefault();
		if(confirm('You are about to DESTROY a complete table! Do you really want to execute "DROP TABLE ` '+jQuery(this).attr('data-table_name')+' `"?')){
			jQuery.ajax({
				url:WDA_ajaxurl,
				type:'post',
				data:{action:'WDA_delete_database_table',table:jQuery(this).attr('data-table_name')},
				dataType:'json',
				success:function(result){
					if(result.status==true){
						location.reload();
					}else{
						WDA_alert("error","Error :",result.message);
					}
				}
			});
		}
	});
	
	jQuery('#WDA_add_row_number_btn').click(function(){
		var num_rows=jQuery('input[name=WDA_add_row_number]').val();
		if(num_rows>0){
			jQuery.ajax({
				url:WDA_ajaxurl,
				data:{action:'WDA_create_table_rows',num_rows:num_rows},
				type:'post',
				success:function(response){
					jQuery('#WDA-insert-new-field').append(response);
					jQuery('input[name=WDA_add_row_number]').val(1);
				}
			});
		}
	});
});