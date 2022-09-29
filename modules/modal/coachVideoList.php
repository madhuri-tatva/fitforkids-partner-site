<?php
include("../../includes/config.php");
$action = $_GET['action'];
$from = $_GET['from'];
$userid = $_SESSION['UserId'];
$coachee_id = $_GET['coachee_id'];
$coachid = $_GET['coachid'];
if($action==1 && $from >0 && $userid >0 & $coachid >=0 && $coachee_id >0){
    //from 1 = Coach, 2 = Coachee
    $db->where('video_from',$from);
    $db->where('user_id', $userid);
    $db->where('coach_id',$coachid);
    $db->where('coachee_id',$coachee_id);
    $video_data = $db->get('training_videos');
?>
<style>
	.md-content .section{
		max-height:calc(100vh - 160px);
		overflow-x:hidden;
	}
	#modal-team .dataTables_filter{
		top : -60px;
	}
</style>
<div class="modal-header">
	<div class="col-md-11">
        <h3>Video List </h3>
	</div>
    <div class="col-md-2">
        <button class="md-close close_icon"></button>
    </div>
</div>
<div class="section">
	<div class="row">
		<div class="col-md-12">
			<!-- <label>Video List</label> -->
			<div class="section-file">
				<table id="modal-videos" class="list">
					<thead>
						<tr>
							<th></th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($video_data as $v){ ?>
							<tr>
								<td width="50%">
                                    <video style="width:60%;">
                                        <source type="video/mp4" src="<?= $basehref . 'uploads/coachvidoes/'.$v['video_file']; ?>">
                                    </video></td>
                                <td width="40%"><?php substr($v['video_file'],0,80)?> <?= date('d-m-Y H:i',strtotime($v['created_at']))?></td>
								<td width="10%" style="text-align:center;"><a class="md-trigger" onclick="delete_video(<?= $v['id']; ?>)">Slet</a></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>

			</div>
		</div>
	</div>
</div>
<script>
	$('html').on('click','.success a.close, .close_icon',function(){
		$('.md-modal').removeClass('md-show');
		$('.md-content-inner').html('');
	});
	$('#modal-videos').dataTable( {
		"pageLength": 10,
		"order": [],
		"columnDefs": [ {
			"targets"  : 'no-sort',
			"orderable": false,
		}]
	});
</script>
<script>
	function delete_video(video_id){
        var action = 3;
        var coach_id = <?= $coachid?>;
        var coachee_id = <?= $coachee_id?>;
        $.ajax({
            url: "/actions/ajax/crudCoach.php",
            method: "POST",
            data: {
                    'Action': action,
                    'coach_id': coach_id,
                    'coachee_id': coachee_id,
                    'video_id': video_id,
                },
            success: function(data){
                window.location.reload();
            },
            error: function(error) {
                console.log('error');
            }
        });

    }

</script>

<?php } ?>



