$(document).ready( function() {
	$("#txtEditor").Editor();                   

	$("#totext").click(function(){
	var isi=$(".Editor-editor").html();
    	$("#inputrespon").val(isi);
		$("#formsave").submit();
	})	

});