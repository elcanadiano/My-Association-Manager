$(function() {
	$('#date').datepicker({
		dateFormat: 'yy-mm-dd'
	});
	$('#lid, #sid, #fid').change(function() {
		var lid = $('#lid :selected').val();
		var sid = $('#sid :selected').val();
		var fid = $('#fid :selected').val();

		if (lid && sid && fid) {
			$.ajax({
				url:'/admin/matches/action_get_teams_for_league/' + sid + '/' + lid,
				dataType:'html',
				success: function(data) {
					$('#htid, #atid').html(data).prop('disabled', false);
					$('.btn-submit').removeClass('disabled');
				}
			});
		}
	});
});