var row_id;

$('button.btn-show-detail').on('click', function(){
	row_id=$(this).attr('id');
	$.ajax({
		url: 'prosesdetail.php',
		type:'POST',
		data:{id:row_id},
		dataType: 'json',
		success: function(a) {
			$('#myModalLabel').html(a.judul);
			$('.modal-body').html(a.isi);
			$('#myModal').modal('show');
		}
	})
})