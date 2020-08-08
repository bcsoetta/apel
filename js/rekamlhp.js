$(document).ready( function() {
	$("#txtEditor").Editor();                   

	$("#totext").click(function(){
	var isi=$(".Editor-editor").html();
    	$("#inputperiksa").val(isi);
		$("#formsave").submit();
	})	

});