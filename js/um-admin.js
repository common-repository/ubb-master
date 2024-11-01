/**
 * 
 */

var um_editing_ubb_id = -1;

function um_cancel_inline_edit() {
	if(um_editing_ubb_id == -1) return;
	
	var ubb_list = document.getElementById('ubb-list');
	var ubb_row = document.getElementById('ubb-' + um_editing_ubb_id);
	var ubb_edit = document.getElementById('ubb-edit-' + um_editing_ubb_id);
	ubb_list.deleteRow(ubb_edit.rowIndex - 1);
	
	if(ubb_row) {
		ubb_row.className = '';
	}
	
	um_editing_ubb_id = -1;
}

function um_insert_inline_edit(ubb) {
	// Get ubb list and ubb display row
	var ubb_list = document.getElementById('ubb-list');
	
	var ubb_edit;
	
	// Insert inline edit here
	if(ubb['id'] == 0) {
		ubb_edit = ubb_list.insertRow(-1);
	} else {
		var ubb_row = document.getElementById('ubb-' + ubb['id']);
		ubb_edit = ubb_list.insertRow(ubb_row.rowIndex);
	}
	
	ubb_edit.innerHTML = document.getElementById('ubb-edit').innerHTML;
	ubb_edit.id = 'ubb-edit-' + ubb['id'];
	
	um_editing_ubb_id = ubb['id'];
	
	// Update form
	var ubb_edit_form = document.getElementById('ubb-edit-form');
    for(var i = 0;i < ubb_edit_form.length; i++){
    	if(ubb_edit_form.elements[i].name == "ubb-id"){  
            ubb_edit_form.elements[i].value = ubb['id'];  
        } else if(ubb_edit_form.elements[i].name == "ubb-name"){  
            ubb_edit_form.elements[i].value = ubb['name'];  
        } else if(ubb_edit_form.elements[i].name == "ubb-format"){  
            ubb_edit_form.elements[i].value = ubb['format'];  
        } else if(ubb_edit_form.elements[i].name == "ubb-enable-in-post"){  
            ubb_edit_form.elements[i].checked = ubb['enable_in_post'];  
        } else if(ubb_edit_form.elements[i].name == "ubb-enable-in-excerpt"){  
            ubb_edit_form.elements[i].checked = ubb['enable_in_excerpt'];  
        } else if(ubb_edit_form.elements[i].name == "ubb-enable-in-comment"){  
            ubb_edit_form.elements[i].checked = ubb['enable_in_comment'];  
        }
    }
    
	// Show ubb edit row
	ubb_edit.className = '';
	
	// Hide ubb display row
	if(ubb_row) {
		ubb_row.className = 'hidden';
	}
}

function um_inline_edit_ubb(ubb_id) {
	if(um_editing_ubb_id != -1) {
		um_cancel_inline_edit();
	}
	
	var ubb_info = document.getElementById('ubb-info-' + ubb_id).innerHTML;
	var ubb_info_list = ubb_info.split(":");
	var ubb = {
		'id' : ubb_id,
		'name' : jQuery.base64Decode(ubb_info_list[0]),
		'format' : jQuery.base64Decode(ubb_info_list[1]),
		'enable_in_post' : jQuery.base64Decode(ubb_info_list[2]),
		'enable_in_excerpt' : jQuery.base64Decode(ubb_info_list[3]),
		'enable_in_comment' : jQuery.base64Decode(ubb_info_list[4]),
	};
	
	um_insert_inline_edit(ubb);
}

function um_add_ubb() {
	if(um_editing_ubb_id != -1) {
		um_cancel_inline_edit();
	}
	
	var ubb = {
		'id' : 0,
		'name' : '',
		'format' : '!{content}',
		'enable_in_post' : 1,
		'enable_in_excerpt' : 1,
		'enable_in_comment' : 0,
	};
	
	um_insert_inline_edit(ubb);
}

function um_hide_guide() {
	document.getElementById('um-note').className = "tool-box ";
	document.getElementById('um-guide').className = "tool-box hidden";
}

function um_show_guide() {
	document.getElementById('um-guide').className = "tool-box ";
	document.getElementById('um-note').className = "tool-box hidden";
}