/* Used to show and hide the admin tabs for UPCP */
jQuery(document).ready(function() {
	jQuery(".upcp-option-tab").on('click', function() {
		var Label_ID = jQuery(this).attr('id');
		var Table_ID = Label_ID.substring(6);

		if (jQuery('#'+Table_ID).hasClass('upcp-hidden')) {
			jQuery('#'+Table_ID).removeClass('upcp-hidden');
			jQuery('#'+Label_ID).addClass('upcp-selected-options');
		}
		else {
			jQuery('#'+Table_ID).addClass('upcp-hidden');
			jQuery('#'+Label_ID).removeClass('upcp-selected-options');
		}
	});
});

function ShowTab(TabName) {
	jQuery(".OptionTab").each(function() {
		jQuery(this).addClass("HiddenTab");
		jQuery(this).removeClass("ActiveTab");
	});
	jQuery("#"+TabName).removeClass("HiddenTab");
	jQuery("#"+TabName).addClass("ActiveTab");
	
	jQuery(".nav-tab").each(function() {
		jQuery(this).removeClass("nav-tab-active");
	});
	jQuery("#"+TabName+"_Menu").addClass("nav-tab-active");
}

function Reload_PP_Page(Value) {
	var Layout = jQuery('#PP-type-select').val();
	window.location.href = "admin.php?page=UPCP-options&DisplayPage=ProductPage&CPP_Mobile=" + Layout;
}

function ShowToolTip(ToolTipID) {
	jQuery('#'+ToolTipID).css('display', 'block');
}

function HideToolTip(ToolTipID) {
	jQuery('#'+ToolTipID).css('display', 'none');
}


