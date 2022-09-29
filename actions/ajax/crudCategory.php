<?php
include("../../includes/config.php");
if(isset($_POST)){

    if($_POST['action'] == 1){

        // CREATE

        if($_POST['parentCategory'] == '' or $_POST['parentCategory'] == 0){
            $level = 1;
        }else{
            $level = 2;
        }

        $data = array(
            "Name"              => $_POST['name'],
            "Description"       => $_POST['description'],
            "ParentCategory"    => $_POST['parentCategory'],
            "Active"            => $_POST['active'],
            "Section"           => $_POST['section'],
            "Level"             => $level,
            "CreateDate"        => $db->now()
        );

        $db->insert('categories', $data);

        $categoryId = $db->getInsertId();

        $dataMenu = array(
            "Category" => $categoryId,
            "Categories" => $_POST['menu'],
            "Highlights" => $_POST['menuhighlight'],
            "CreateDate" => $db->now()
        );

        $db->insert('category_nav', $dataMenu);

        exit;

    }elseif($_POST['action'] == 2){

        // UPDATE

        if($_POST['parentCategory'] == '' or $_POST['parentCategory'] == 0){
            $level = 1;
        }else{
            $level = 2;
        }

        if(isset($_POST['categoryId'])){

            $data = array(
                "Name"              => $_POST['name'],
                "Description"       => $_POST['description'],
                "ParentCategory"    => $_POST['parentCategory'],
                "Active"            => $_POST['active'],
                "Section"           => $_POST['section'],
                "Level"             => $level
            );

            $db->where('Id',$_POST['categoryId']);
            $db->update('categories', $data);


            $db->where('Category',$_POST['categoryId']);
            $hasEntry = $db->has('category_nav');

            $dataMenu = array(
                "Category" => $_POST['categoryId'],
                "Categories" => $_POST['menu'],
                "Highlights" => $_POST['menuhighlight'],
                "CreateDate" => $db->now()
            );

            if($hasEntry == true){

                $db->where('Category',$_POST['categoryId']);
                $db->update('category_nav', $dataMenu);

            }else{

                $db->insert('category_nav', $dataMenu);

            }

        }

        exit;

    }elseif($_POST['action'] == 3){

        // DELETE
        exit;

    }elseif($_POST['action'] == 4){

        // UPDATE

        if(isset($_POST['categoryId'])){
           
            $dataMenu = array(
                "Category" => $_POST['categoryId'],
                "Categories" => $_POST['menu'],
                "Highlights" => $_POST['menuhighlight'],
                "CreateDate" => $db->now()
            );

            $db->where('Category',$_POST['categoryId']);
            $db->update('category_nav', $dataMenu);


        }

        exit;

    }elseif($_POST['action'] == 5){

        // UPDATE

        if(isset($_POST['category_id'])){
           
            $dataCat = array(
                "Name" => $_POST['category_name']
            );

            $db->where('Id',$_POST['category_id']);
            $db->update('categories', $dataCat);


        }

        exit;

    }

}

?>