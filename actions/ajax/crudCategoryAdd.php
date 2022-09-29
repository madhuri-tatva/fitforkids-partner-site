<?php
include("../../includes/config.php");

if(isset($_POST)){

    $section = $_POST['section'];
    $category = $_POST['categoryId'];
    $product = $_POST['productId'];

    $data = array(
        'Section' => $section,
        'Category' => $category
    );

    $db->where('Id',$product);
    $db->update('products',$data);

}

?>