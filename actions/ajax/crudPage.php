<?php
include("../../includes/config.php");

if(isset($_POST)){

    if($_POST['action'] == 1){

        // CREATE

        $data = array(
            "slug"              => $_POST['slug'],
            "Type"              => 1,
            "CMSTitle"          => $_POST['title'],
            "CMSContent"        => $_POST['content'],
            "MetaTitle"         => $_POST['metatitle'],
            "MetaDescription"   => $_POST['metadescription'],
            "Content"           => 'cms.php',
            "Header"            => 'header.php',
            "Footer"            => 'footer.php',
            "Language"          => 'en_GB',
            "IsCMS"             => 1,
            "ThemeID"           => 2,
            "datecreated"       => $db->now()
        );

        $db->insert('cms', $data);

        exit;

    }elseif($_POST['action'] == 2){

        // UPDATE

        if(isset($_POST['pageId'])){

            $data = array(
                "CMSTitle"          => $_POST['title'],
                "CMSContent"        => $_POST['content'],
                "MetaTitle"         => $_POST['metatitle'],
                "MetaDescription"   => $_POST['metadescription']
            );

            $db->where('Id',$_POST['pageId']);
            $db->update('cms', $data);

        }
        exit;

    }elseif($_POST['action'] == 3){

        // DELETE
        exit;

    }

}

?>