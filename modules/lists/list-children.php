<?php
include("../../includes/config.php");

// $db->where('ParentId',$_SESSION['UserId']);
// $db->where('is_partner',0);
// $childrenData = $db->get('users');

$PartnerId = $_SESSION['UserId'];

$childrenData = $db->rawQuery("SELECT * FROM pg_users WHERE is_partner = 0 AND (ParentId = ".$PartnerId." OR FIND_IN_SET($PartnerId,ParentId_2) > 0)");

if(!empty($childrenData)){ ?>
	<ul class="list-children-inner">
		<?php foreach($childrenData as $childData){ 

			$from = new DateTime($childData['Age']);
			$to   = new DateTime('today');

			?>
			<li class="item"><?php echo $childData['Firstname'] . " " . $childData['Lastname']; ?><div class="right"><?php echo $from->diff($to)->y; ?> år <a class="allow-click md-trigger child-list" data-modal="modal-child" onclick="crudModalChild(2,<?php echo $childData['Id']; ?>)">Ret</a></div></li>
		<?php } ?>
	</ul>

<?php } ?>


<script src="assets/js/modalEffects.js"></script>