<?php
include("../../includes/config.php");
// echo "<pre>";print_r($_POST);
if (isset($_GET['action'])) {
	$action = $_GET['action'];
}else{
	$action = 0;
}
if (isset($_GET['productid'])) {
	$productid = $_GET['productid'];
}else{
	$productid = 0;
}

$medias = $db->get('media');

// Sort media
$mediaSorted = array();
foreach($medias as $media){

	if($media['Type'] == 'jpg' or $media['Type'] == 'jpeg' or $media['Type'] == 'png'){
		$mediaSorted['Images'][] = $media;
	}else{
		$mediaSorted['Medias'][] = $media;
	}

}

$productTitle = '';
$productSection = (isset($_GET['section'])) ? $_GET['section'] : 0;
$productCategory = (isset($_GET['categoryid'])) ? $_GET['categoryid'] : 0;
$productSubcategory = 0;
$productDescription = '';
$productMedias = array();
$productThumbnail = 0;


if($action == 1){

	// Create

}elseif($action == 2){

	// Update
	$db->where('Id',$productid);
	$product = $db->getOne('products');

	$productTitle = $product['Name'];
	$productMedias = $product['Medias'];

	$productMedias = explode(',',$productMedias);

	$productThumbnail = $product['Thumbnail'];

}elseif($action == 3){

	// Delete

}

$allCategories = $db->get('categories');
$allCategoriesLevel1Sorted = array();
$allCategoriesLevel2Sorted = array();

foreach($allCategories as $category){

	if($category['Level'] == 1){
		$allCategoriesLevel1Sorted[$category['Id']] = $category; 
	}else{
		$allCategoriesLevel2Sorted[$category['Id']] = $category; 
	}

}



	## Total number of records without filtering

	$totalRecords = count($mediaSorted['Medias']);

	$data_doc = array();

	foreach ($mediaSorted['Medias'] as $k => $val) {
		$data_doc[] = array( 
	      "media_id"=>$val['Id'],
	      "doc_name"=>$val['FileName'],
	      "img"=>"",
	      "Vælg"=>"Vælg"
	   );
	}

	## Response
	$response_doc = array(
	  "iTotalRecords" => $totalRecords,
	  "iTotalDisplayRecords" => 5,
	  "aaData" => $data_doc
	);
	// echo "<pre>";print_r($response);exit;
	echo json_encode($response_doc);


?>



