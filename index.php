<?php 

require_once("includes/config.php");

define('BASE_URL', baseURL());
define('MC_ROOT', dirname(__FILE__)); // Set relative path to Index.php

$_SESSION['SiteLanguage'] = 'en_GB';

//Routing Start
if(isset($_REQUEST['s'])){

    $slug = strip_tags(stripslashes($_REQUEST['s']));

    $db->where('slug', $slug);
    $cms = $db->getOne('cms');

    $id = $cms['Id'];
    $IsCMS = $cms['IsCMS'];

    if($IsCMS == 1){
        $url = $_SERVER['REQUEST_URI'];

        $parent = strip_tags(stripslashes($_REQUEST['s']));
        $child = substr($url, strrpos($url, '/') + 1);

        $subparent_string = substr(strstr(substr(strstr($url, '/'),1),'/'),1);
        $subparent = substr($subparent_string, 0, strrpos($subparent_string, '/'));

        if($parent != "shop") {

            if(!empty($subparent)){

                $s = $parent . "/" . $subparent . "/" . $child;

            }else{

                if($parent == $child){
                    $s = $parent;
                }else{
                    $s = $parent . "/" . $child;
                }

            }

        } else {
            $s = $parent;
        }
    }else{
        $s = strip_tags(stripslashes($_REQUEST['s']));
    }

    if(isset($_REQUEST['us'])){ 
        $us = strip_tags(stripslashes($_REQUEST['us']));
    }
    if(isset($_REQUEST['us2'])){ 
        $us2 = strip_tags(stripslashes($_REQUEST['us2']));
    }
    if(isset($_REQUEST['us3'])){ 
        $us3 = strip_tags(stripslashes($_REQUEST['us3'])); 
    }


}else{

    $s = "frontpage";

    $db->where('slug', $s);
    $cms = $db->getOne('cms');

}


    $db->join("themes t", "c.ThemeID=t.Id", "LEFT");
    $db->where("slug", $s);
    $routeinfo          = $db->getOne('cms c');


    $Type               = $routeinfo['Type'];
    $Header             = $routeinfo['Header'];
    $Footer             = $routeinfo['Footer'];
    $Content            = $routeinfo['Content'];
    $ParentID           = $routeinfo['ParentID'];
    $MetaTitle          = $routeinfo['MetaTitle'];
    $MetaDescription    = $routeinfo['MetaDescription'];
    $BodyClass          = $routeinfo['BodyClass'];
    $CMSTemplate        = $routeinfo['CMSTemplate'];
    $CMSTitle           = $routeinfo['CMSTitle'];
    $CMSContent         = $routeinfo['CMSContent'];
    $CMSHeaderType      = $routeinfo['CMSHeaderType'];
    $CMSLanguage        = $routeinfo['Language'];
    $RobotsUnfollow     = $routeinfo['RobotsUnfollow'];
    $Path               = $routeinfo['Path'];
    $Path               .= "/";
    $IsCMS              = $routeinfo['IsCMS'];
    $Level              = $routeinfo['Level'];
    $Premium            = $routeinfo['Premium'];
    $PageAdmin          = $routeinfo['Admin'];
    $_SESSION['IsCMS']  = $routeinfo['IsCMS'];
    $JS1                = $routeinfo['JS1'];
    $JS2                = $routeinfo['JS2'];
    $JS3                = $routeinfo['JS3'];
    $JS4                = $routeinfo['JS4'];
    $SideContent        = $routeinfo['SideContent'];

    $meta_title         = $MetaTitle;
    $meta_keywords      = $MetaTitle;
    $meta_desc          = $MetaDescription;
    $og_image           = "";




    if(empty($routeinfo)){
        require MC_ROOT."/views/404.php";
    }


    if($Type == 1){

        if($_SESSION['SiteLanguage'] != $CMSLanguage){
            //echo $_SESSION['SiteLanguage'];
            //echo "--";
            //echo $CMSLanguage;
            //echo $domian_url;
            require MC_ROOT."/views/404.php";

        }else{

            if($Header){
              require MC_ROOT."/views/" . $Path . $Header;
            }

            if($Content){
              require MC_ROOT."/views/" . $Path . $Content;
            }

            if($Footer){
              require MC_ROOT."/views/" . $Path . $Footer;
            }

        }

    }else{

        if($Header){
          require MC_ROOT."/views/" . $Path . $Header;
        }

        if($Content){
          require MC_ROOT."/views/" . $Path . $Content;
        }

        if($Footer){
          require MC_ROOT."/views/" . $Path . $Footer;
        }
    }

//Routing End


//Update SESSION with current page :-)
$_SESSION['backToThisPage'] = curPageURL();
$_SESSION['loggedinfrom'] = curPageURL();

/*echo $s;
echo $child;
echo $parent;*/

?>


    </body>
</html>


<?php 

//echo doubleSalt('test');

?>