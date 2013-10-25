$(".imagelist").sortable({axis:'y'});
$(".remove-btn").click(function() {
	if(!$(this).closest("li:not(:only-child)").remove().size()) {
		alert("You may not have fewer than one.");
	}
});
$("input:file").change(function() {
	var msg = '<em>Image selected.</em>';
	if(!/\.(jpg|jpeg|gif|png)$/.test($(this).val())) {
		msg = '<em>Error: invalid format.</em>'
	}
	$(this).closest("li")
		.find("em, img").remove().end()
		.find("figure").prepend(msg);
});
$(".add-btn").click(function() { 
	$(".imagelist").append($(".imagelist > li:first-child").clone(true)
		.find("input:text, input[type=hidden]").val("").end()
		.find("input:checkbox").prop("checked",false).end()
		.find("em, img").remove().end()
		.find("figure").prepend('<em>Choose an image&hellip;</em>').end());
});
var justSaved = false;
$("form").submit(function() {
	// Make sure all checkboxes are submitted, not just checked ones
	$(this).find("input:checkbox").each(function() {
		if(!this.checked) {
			$(this).attr("value","off");
			this.checked = true;
		} else { $(this).attr("value","on");}
		justSaved = true;
	})
})

window.onbeforeunload = function() {
	if(!justSaved) {
		justSaved = false;
		return "Before exiting, check that you have saved your work!";
	}

}