$('#alert-message button.close').click(function() {
	$('#alert-message').slideUp(200);
});

$('form .btn-submit').click(function() {
	$.ajax({
		url:$(this).parent().attr('action'),
		type:'POST',
		dataType:'json',
		data:$(this).parent().serialize(),
		success:function(data) {
			if (data.status) {
				$('#alert-message').attr('class', 'alert alert-dismissable alert-' + data.status);
				$('#alert-message span.message').html(data.message);
				$('#alert-message').slideDown(200);
			}
		},
		error:function(jqXHR,textStatus,errorThrown) {
			$('#alert-message').attr('class', 'alert alert-dismdata.messageissable alert-danger');
			$('#alert-message span.message').html(jqXHR.responseText);
			$('#alert-message').slideDown(200);
		}
	});

	/*alert($(this).parent().serialize());
	alert($(this).parent().attr('action'));*/
});