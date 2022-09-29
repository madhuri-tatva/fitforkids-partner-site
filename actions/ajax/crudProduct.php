<?php
include("../../includes/config.php");

if(isset($_POST)){

    if($_POST['Action'] == 1){

        // CREATE

        $data = array(
            "Name"              => $_POST['Title'],
            "Description"       => $_POST['Description'],
            "Section"           => $_POST['Section'],
            "Category"          => $_POST['Category'],
            "Subcategory"       => $_POST['Subcategory'],
            "Medias"            => $_POST['Media'],
            "Thumbnail"         => $_POST['Thumbnail'],
            "CreateDate"        => $db->now()
        );

        $db->insert('products', $data);

        exit;

    }elseif($_POST['Action'] == 2){

        // UPDATE

        $data = array(
            "Name"              => $_POST['Title'],
            "Description"       => $_POST['Description'],
            "Section"           => $_POST['Section'],
            "Category"          => $_POST['Category'],
            "Subcategory"       => $_POST['Subcategory'],
            "Medias"            => $_POST['Media'],
            "Thumbnail"         => $_POST['Thumbnail']
        );

        $db->where('Id',$_POST['Id']);
        $db->update('products', $data);
        exit;

    }elseif($_POST['Action'] == 3){

        // DELETE
        exit;

    }

}

?>