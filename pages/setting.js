function vssg2_submit()
{
	if(document.vssg2_form.vssg_path.value=="")
	{
		alert(vssg2_adminscripts.vssg_path);
		document.vssg2_form.vssg_path.focus();
		return false;
	}
	else if(document.vssg2_form.vssg_link.value=="")
	{
		alert(vssg2_adminscripts.vssg_link);
		document.vssg2_form.vssg_link.focus();
		return false;
	}
	else if(document.vssg2_form.vssg_target.value=="")
	{
		alert(vssg2_adminscripts.vssg_target);
		document.vssg2_form.vssg_target.focus();
		return false;
	}
	else if(document.vssg2_form.vssg_type.value=="")
	{
		alert(vssg2_adminscripts.vssg_type);
		document.vssg2_form.vssg_type.focus();
		return false;
	}
	else if(document.vssg2_form.vssg_status.value=="")
	{
		alert(vssg2_adminscripts.vssg_status);
		document.vssg2_form.vssg_status.focus();
		return false;
	}
	else if(document.vssg2_form.vssg_order.value=="")
	{
		alert(vssg2_adminscripts.vssg_order);
		document.vssg2_form.vssg_order.focus();
		return false;
	}
	else if(isNaN(document.vssg2_form.vssg_order.value))
	{
		alert(vssg2_adminscripts.vssg_order);
		document.vssg2_form.vssg_order.focus();
		return false;
	}
}

function vssg2_delete(id)
{
	if(confirm(vssg2_adminscripts.vssg_delete))
	{
		document.frm_vssg2_display.action="options-general.php?page=vertical-scroll-slideshow-gallery-v2&ac=del&did="+id;
		document.frm_vssg2_display.submit();
	}
}	

function vssg2_redirect()
{
	window.location = "options-general.php?page=vertical-scroll-slideshow-gallery-v2";
}

function vssg2_help()
{
	window.open("http://www.gopiplus.com/work/2010/07/18/vertical-scroll-slideshow-gallery-v2/");
}