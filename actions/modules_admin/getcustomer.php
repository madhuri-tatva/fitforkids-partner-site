<?php
include("../../includes/config.php");

//Language
putenv('LC_ALL='.$_SESSION['Language']);
setlocale(LC_ALL, $_SESSION['Language']);

bindtextdomain($_SESSION['Language'], "../../locale");
bind_textdomain_codeset($_SESSION['Language'], 'UTF-8'); 

textdomain($_SESSION['Language']);


$customerid = $_GET['id'];
$customerdata = customer_data_by_id($customerid);

$users = users_by_customer_id($customerid);

//echo $shift['Id'];

?>


<div class="meta-actions">
    <span class="close-sidecontent"><i class="angle-lefticon-"></i> <?php echo _('Tilbage til kalenderen'); ?></span>
    <h2><?php echo $customerdata['Company']; ?></h2>
</div>

<div class="container admin-sidecontent">

    <div id="list">


    <?php 

        foreach($users as $user){

            $db->where('UserId', $user['Id']);
            $db->orderBy('Id', 'DESC');
            $logs = $db->get('log');

            $logscount = count($logs);

            $userdata = user_data_by_id($user['Id']);

            ?>

        <div class="row">
            <span class="unexpand"><i class="icon-save"></i></span>
            <span class="item">
                <div class="item-header">
                    <span><h4><?php echo "ID:" . $userdata['Id'] . " - " . $userdata['Firstname'] . "</h4> " . $logscount . " " . _('actions'); ?></span>
                </div>

                <div class="item-content">
                    <ul>
                    <?php
                    foreach($logs as $log){

                        $action = getlogaction($log['Action']);

                        echo "<li><strong>" . $action . "</strong><span>" . $log['Timestamp'] . "</span></li>";
                    }
                    ?>
                    </ul>
                </div>
            </span>
        </div>

    <?php

        }

    ?>

    </div>

</div>

<script type="text/javascript">
$(".close-sidecontent").click(function(){
    $("#wrapper").removeClass("push");
    $("#sidecontent").removeClass("push");
});

jQuery(function($) {
  $('select.form-control').niceSelect();
});

$(".item").click(function(){
    $(this).addClass("expand");
    $(this).parents("div:eq(0)").find('.unexpand').addClass('active');
});

$(".unexpand").click(function(){

    $(this).parents("div:eq(0)").find('.item').each(function(){
        $(this).removeClass('expand');
    });

    $(this).removeClass("active");

});
</script>