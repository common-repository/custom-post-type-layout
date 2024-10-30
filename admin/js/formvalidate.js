jQuery(document).ready(function() {
$ = jQuery;
var layoutname	= $('.layout').val();
var dateformate = $('.publishdate').val();
var per_page	= $('.per_page').val();

if(layoutname == "grid" || layoutname =="masonry") {
	$(".gridcolumn").show();
} else {
 	$(".gridcolumn").hide();	
}
if(dateformate == "yes") {
	$(".dateformate").show();
} else {
 	$(".dateformate").hide();	
}

if(per_page >= 1) {
	$(".pagination").show();
} else {
	$(".pagination").hide();
}
jQuery("form#cbw_form").validate();
	$(document).on("change", '.layout', function(event) { // call ajax on layout
		var layoutname = this.value;
		if(layoutname == "grid" || layoutname =="masonry") {
	      $(".gridcolumn").show();
	     } else {
	     $(".gridcolumn").hide();	
	     }
	});
	$(document).on("change", '.publishdate', function(event) { // call ajax on layout
		var dateformate = this.value;
		if(dateformate == "yes") {
	      $(".dateformate").show();
	     } else {
	     $(".dateformate").hide();
	     }
	});
	$(document).on("keyup", '.per_page', function(event) { // call ajax on layout
		var per_page = this.value;
		if(per_page == "-1") {
	      $(".pagination").hide();
	      $(".selectpagination").val('');
	     } else {
	     $(".pagination").show();	
	     }
	});
});