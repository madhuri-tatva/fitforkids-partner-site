<?php
include("../../includes/config.php");

$db->where('ParentId',$_SESSION['UserId']);
$db->where('is_partner',1);
$db->where('Active',1);
$partnerDetails = $db->get('users');

if(!empty($partnerDetails)){ ?>
	<ul class="list-children-inner">
		<?php foreach($partnerDetails as $partnerData){ 

			$from = new DateTime($partnerData['Age']);
			$to   = new DateTime('today');

			?>
			<li class="item"><?php echo $partnerData['Firstname'] . " " . $partnerData['Lastname']; ?><div class="right"><?php echo $from->diff($to)->y; ?> år <a class="allow-click md-trigger partner-list" data-modal="modal-partner" onclick="crudModalPartner(2,<?php echo $partnerData['Id']; ?>)">Ret</a></div></li>
		<?php } ?>
	</ul>

<?php } ?>


<script src="assets/js/modalEffects.js"></script>