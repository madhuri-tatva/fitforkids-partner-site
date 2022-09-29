<?php 
include("../../includes/config.php");

if($domian_url == 'www.plangy.com'){
    putenv('LC_ALL=en_GB');
    setlocale(LC_ALL, 'en_GB');

    bindtextdomain('en_GB', "../../locale");
    bind_textdomain_codeset('en_GB', 'UTF-8'); 

    textdomain('en_GB');
}elseif($domian_url == 'www.plangy.dk'){
    putenv('LC_ALL=da_DK');
    setlocale(LC_ALL, 'da_DK');

    bindtextdomain('da_DK', "../../locale");
    bind_textdomain_codeset('da_DK', 'UTF-8'); 

    textdomain('da_DK');
}

//$new_employee = '<input type="text" name[]="employee" placeholder="' . _('First name') . '" />';
$new_employee = "<div class='form-group item-employee'><div class='md-col-12'>";
$new_employee .= "<input type='text' name='employee[]' placeholder='". _('Employees first name') ."' />";
$new_employee .= "<span class='btn-remove'><img src='/assets/img/icon-cancel.png' /></span>";
$new_employee .= "</div></div>";


if(!empty($_SESSION['plan'])){

    if($_SESSION['plan'] == 'Pro'){ ?>

    <script type="text/javascript">
    fbq('track', 'CompleteRegistration', {
        content_name: 'Pro lead',
    });
    gtag('event', 'conversion', {'send_to': 'AW-800884658/kEQQCKm4jocBELKP8v0C'});
    </script>

    <?php }elseif($_SESSION['plan'] == 'Freemium'){ ?>

    <script type="text/javascript">
    fbq('track', 'CompleteRegistration', {
        content_name: 'Freemium lead',
    });
    gtag('event', 'conversion', {'send_to': 'AW-800884658/f0tqCPyd_4YBELKP8v0C'});
    </script>

    <?php }

}
?>

<div class="form-inner">

	<h2><?php echo _('Add employees'); ?></h2>
	<p><?php echo _('Add by first name. Additional information as last name, e-mail can be added later.'); ?></p>

	<form id="register-employees" method="post" action="">

		<div id="list-add">
			<div class='form-group item-employee'><div class='md-col-12'><em class="owner"><?php echo _('You'); ?></em>
			<input type='text' name='' value="<?php echo $_SESSION['Firstname']; ?>" disabled />
			<span class='btn-remove'><img src='/assets/img/icon-cancel.png' /></span>
			</div></div>

			<?php echo $new_employee ?>
		</div>

		<div class="md-col-12">
			<span id="addMore" class="btn-secondary"><?php echo _('Add employee'); ?></span>
		</div>

	</form>

	<div class="divider"></div>
	<span id="submit" class="btn-cta"><?php echo _('Finish'); ?></span>


</div>

<script type="text/javascript">
    var emp_max = <?php echo $_SESSION['UserCapacity']; ?>;
</script>

<script type="text/javascript">

$('#submit').click( function() {

	var errors = 0;

    $("#register-employees").find(".item-employee input").each(function(){
        var inputvalue = $(this).val();
        console.log(inputvalue);

        if(inputvalue == ''){
        	$(this).addClass('error');
        	errors += 1;
        }
    });


    if(errors >= 1){
        console.log('Great error!' + errors);
    }else{
        console.log('Great success!' + errors);
	 	var data = $('#register-employees').serialize();

	    $.ajax({ 
	        data: data, 
	        type: 'post',
	        url: '/actions/register_createemployees.php',
	        success: function() {
	            //reg_continue('setup');
                reg_continue('success');
	        }
	    });
    }


    return false; 
});


$("#addMore").click(function(e) {
    e.preventDefault();
    $("#list-add").append("<?php echo $new_employee; ?>");
    $("#addMore").removeClass('hide');

    var count = 0;  
    $("#register-employees").find(".item-employee").each(function(){
        count ++;  
    });

    if(count >= emp_max){
    	$("#addMore").addClass('hide');
    }

	$('.btn-remove').click( function() {
	    $(this).parents("div:eq(1)").remove();

		$("#addMore").removeClass('hide');

	    var count = 0;  
	    $("#register-employees").find(".item-employee").each(function(){
	        count ++;  
	    });

	    if(count >= emp_max){
	    	$("#addMore").addClass('hide');
	    }

	    console.log(count);

	}); 

});

$('.btn-remove').click( function() {
    $(this).parents("div:eq(1)").remove();

	$("#addMore").removeClass('hide');

    var count = 0;  
    $("#register-employees").find(".item-employee").each(function(){
        count ++;  
    });

    if(count >= emp_max){
    	$("#addMore").addClass('hide');
    }

    console.log(count);

}); 


/*
$('#submit').click( function() {

	 document.getElementById("register-employees").submit();

});*/
</script>
