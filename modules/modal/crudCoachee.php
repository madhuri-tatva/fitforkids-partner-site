<?php
include("../../includes/config.php");
// Coachee
$db->where('special_page_access',2);
// $db->where("TeamId", 0,'!=');
$db->where("Type",0);
$db->where('ParentId', 0);
$db->orderBy('Firstname','ASC');
$coachee = $db->get('users');
$action = $_GET['action'];
	$coachid = 0;
	$coachName  = '';
	$coachee_ids = [];
	$existing_coachee = NULL;

if(isset($_GET['coachid'])){
	$coachid = $_GET['coachid'];
    $db->where('Id',$coachid);
	$coach = $db->getOne('users');
if(!empty($coach)){
	$coachName = $coach['Firstname'].'' .$coach['Lastname'];
	$coachee_ids = ($coach['coachee_ids']) ? explode(',',$coach['coachee_ids']) : '';
	$existing_coachee = $coach['coachee_ids'];
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
	<div class="col-md-6">
		<h3>Coach <?= $coachName; ?></h3>
	</div>
	<div class="col-md-6">
		<button id="btn-modal-save-coachee" class="btn-cta btn-save">Gem</button>
	</div>
</div>
<div class="section">
	<input type="hidden" name="teamAction" class="" value="<?php echo $action; ?>" />
	<input type="hidden" name="coachId" class="" value="<?php echo $coachid; ?>" />
	<div class="row row-list">
		<div class="col-md-12">
			<label>Coachee List</label>
			<div class="section-file">
				<table id="modal-coachee" class="list" data-coachee-ids="<?= $existing_coachee; ?>">
					<thead>
						<tr>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($coachee as $ch){ ?>
							<tr class="<?php if(!empty($coachee_ids) && in_array($ch['Id'],$coachee_ids)){echo 'selected active';} ?>" data-coachee-id="<?= $ch['Id']?>">
								<td><?= $ch['Firstname']." ".$ch['Lastname']; ?></td>
								<td class="right"><a class="md-trigger" onclick="modalChooseEmp(<?= $ch['Id']; ?>)">Vælg</a></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>

			</div>
		</div>
	</div>
</div>

<script>

	$('html').on('click','.success a.close.md-overlay',function(){
		$('.md-modal').removeClass('md-show');
		$('.md-content-inner').html('');
	});

	$('#modal-coachee').dataTable( {
		"pageLength": 10,
		"order": [],
		"columnDefs": [ {
			"targets"  : 'no-sort',
			"orderable": false,
		}]
	});

    function modalChooseEmp(id){
		// $('tr[data-coachee-id='+id+']').toggleClass('active');
		if($('tr[data-coachee-id='+id+']').hasClass('selected')){
      		console.log('Remove');
			$('tr[data-coachee-id='+id+']').removeClass('active');
			$('tr[data-coachee-id='+id+']').removeClass('selected');
			var coachee_ids = $('#modal-coachee').attr('data-coachee-ids');
			coachee_ids = coachee_ids.split(',');
			coachee_ids = jQuery.grep(coachee_ids, function(value) {
				return value != id;
			});
			var coachee_idsResult = coachee_ids.join();
			$('#modal-coachee').attr('data-coachee-ids',coachee_idsResult);
			// console.log(coachee_ids);
			// console.log(coachee_idsResult);
   		}else{
			$('tr[data-coachee-id='+id+']').addClass('active');
			$('tr[data-coachee-id='+id+']').addClass('selected');
			var coachee_ids = $('#modal-coachee').attr('data-coachee-ids');
			if(coachee_ids != ''){
				coachee_ids = coachee_ids.split(',');
			}else{
				coachee_ids = [];
			}
			coachee_ids.push(id);
			// console.log(coachee_ids);
			var coachee_idsResult = coachee_ids.join();
			$('#modal-coachee').attr('data-coachee-ids',coachee_idsResult);
			// console.log(coachee_ids);
			// console.log(coachee_idsResult);
   		 }
	}
</script>
<script>
	$('html').on('click','#btn-modal-save-coachee',function(){
	var action = 2;
	var coach_id = <?= $coachid?>;
	var coachee = '';

	coachee = $('#modal-coachee').attr('data-coachee-ids');
	$.ajax({
		url: "/actions/ajax/crudCoach.php",
		method: "POST",
		data: {
		'Action': action,
		'coach_id': coach_id,
		'coachee_ids': coachee
		},
		success: function(data){
			// console.log(data);
			window.location.reload();
		},
		error: function(error) {
			console.log('error');
		}
	});

	});

</script>
<?php } } ?>


