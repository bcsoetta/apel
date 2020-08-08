var row_id;

$('button.btn-show-detail').on('click', function(){
	row_id=$(this).attr('id');
	$.ajax({
		url: 'detailcatatannpd.php',
		type:'POST',
		data:{id:row_id},
		dataType: 'json',
		success: function(a) {
			$('#myModalLabel').html(a.judul);
			$('.modal-body').html(a.isi);
			$('#myModal').modal('show');
		}
	})
});

$('button.btn-delete-data').on('click', function(){
	if(confirm('Anda yakin menghapus data ini?')==true)
	row_id=$(this).attr('id');
	$.ajax({
		url: '?mod=65&del=true',
		type:'GET',
		data:{id:row_id},
	})
});