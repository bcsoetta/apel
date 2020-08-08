var row_id;

$('p.detail').on('click', function(){
	row_id=$(this).attr('id').split('|');
	$.ajax({
		url: 'detailpdtttime.php',
		type:'POST',
		data:{ta:row_id[0],tr:row_id[1],pt:row_id[2]},
		dataType: 'json',
		success: function(a) {
			$('#myModalLabel').html(a.judul);
			$('.modal-body').html(a.isi);
			$('#myModal').modal('show');
		}
	})
})