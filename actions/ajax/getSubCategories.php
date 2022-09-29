<?php
include("../../includes/config.php");

$parentCategory = $_GET['parent'];

$db->where('ParentCategory',$parentCategory);
$categories = $db->get('categories');
?>

<label>Underkategori</label>
<select>
	<option value="0">Vælg underkategori</option>
	<?php foreach($categories as $category){ ?>
		<option value="<?php echo $category['Id']; ?>"><?php echo $category['Name']; ?></option>
	<?php } ?>
</select>


<script>
$('select').niceSelect();
</script>