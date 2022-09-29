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

if(!empty($_SESSION['plan'])){
	$iniplan = $_SESSION['plan'];
}else{
	$iniplan = 'Plan5';
}
?>

<style>
.hide {
	display: none;
}
</style>

<div class="form-inner">

	<div class="choose-plan">
		<div class="choose-plan-box">
			<input type="button" class="chosen" value="<?php echo $iniplan; ?>" />
			<!--<input type="button" class="<?php if($iniplan == 'Freemium'){ echo 'chosen';} ?>" value="Freemium" />
			<input type="button" class="<?php if($iniplan == 'Pro'){ echo 'chosen';} ?>" value="Pro" />-->
		</div>
		<div class="choose-plan-caption">
			<span class="caption-pro <?php if($iniplan != 'Pro'){ echo 'hide';} ?>"><?php echo _('All features included and personal assistance.'); ?><br><?php echo _('Try 30 days free.'); ?> <em><?php echo _('Recommended'); ?></em></span>
			<span class="caption-freemium <?php if($iniplan != 'Freemium'){ echo 'hide';} ?>"><?php echo _('Limited features.'); ?><br><?php echo _('Forever free.'); ?></span>
		</div>
	</div>

	<form id="register-base" method="post" action="" class="signup-step">
	    <div class="form-group">
	    	<div class="md-col-6">
	        	<input type="text" class="form-control" name="firstname" id="firstname" class="" placeholder="<?php echo _('First name'); ?>"  required="" autocomplete="false" />
	        	<em class="required">*</em>
	        </div>

	    	<div class="md-col-6">
	        	<input type="text" class="form-control" name="lastname" id="lastname" class="" placeholder="<?php echo _('Last name'); ?>" required="" autocomplete="false" />
	        	<em class="required">*</em>
	        </div>
	    </div>

	    <div class="form-group">
	    	<div class="md-col-6">
	        	<input type="text" class="form-control" name="email" id="email" class="" placeholder="<?php echo _('E-mail'); ?>"  required="" autocomplete="false" value="<?php if(!empty($_SESSION['Visitor']['Email'])){ echo $_SESSION['Visitor']['Email']; } ?>" />
	        	<em class="required">*</em>
	        </div>

	    	<div class="md-col-6">
	        	<input type="text" class="form-control" name="phone" id="phone" class="" placeholder="<?php echo _('Phone'); ?>" required="" autocomplete="false" />
	        </div>
	    </div>

	    <div class="form-group">
	    	<div class="md-col-6">
	        	<input type="text" class="form-control" name="company" id="company" class="" placeholder="<?php echo _('Company'); ?>" required="" autocomplete="false" />
	        	<em class="required">*</em>
	        </div>

	    	<div class="md-col-6">
		        <input type="password" class="form-control" name="password" id="password" class="" placeholder="<?php echo _('Password'); ?>"  required="" />
		        <em class="required">*</em>

		        <input type="hidden" name="country" id="country" value="Denmark" />
	        </div>
	    </div>

	    <div class="form-group">
	    	<div class="md-col-12">
	    		<a id="submit" class="btn-cta"><?php echo _('Continue'); ?></a>
	        </div>
	    </div>
	</form>

</div>

<script type="text/javascript">

	$('.choose-plan-box input').click( function() {
		var plan = $(this).val();

		$(this).parents("div:eq(0)").find('input').each(function(){
			$(this).removeClass('chosen');
		});
		$(this).parents("div:eq(1)").find('span').each(function(){
			$(this).removeClass('hide');
		});
		$(this).addClass('chosen');
		if(plan == 'Pro'){
			$('.caption-freemium').addClass('hide');
			$('.caption-pro').removeClass('hide');
		}
		if(plan == 'Freemium'){
			$('.caption-pro').addClass('hide');
			$('.caption-freemium').removeClass('hide');
		}
	});

	function addAlert(type,message) {

	  $("#alert").removeClass('alert');
	  $("#alert").removeClass('suc');
	  $("#alert").removeClass('war');
	  $("#alert").removeClass('err');

	  var cls = type;
	  if(type == 1){
	      cls = 'alert suc';
	  }
	  if(type == 2){
	      cls = 'alert war';
	  }
	  if(type == 3){
	      cls = 'alert err';
	  }

	  $("#alert").html("<div>"+message+"</div>");
	  $("#alert").addClass(cls);

	}

	$('#submit').click( function() {

	  addAlert(0,"");
	  var errors = 0;

	  var reg_firstname = $('#firstname').val();
	  var reg_lastname = $('#lastname').val();
	  var reg_email = $('#email').val();
	  var reg_password = $('#password').val();

	  var reg_company = $('#company').val();
	  var reg_phone = $('#phone').val();
	  var reg_country = $('#country').val();

	  var plan = $('.choose-plan-box input.chosen').val();


	  $('#register-base').find('input').each(function(){
	  	  $(this).removeClass('error_1');
	  });

	  if(reg_firstname == ''){
	      $('#firstname').addClass('error_1');
	      errors += 1;
	  }

	  if(reg_lastname == ''){
	      $('#lastname').addClass('error_1');
	      errors += 1;
	  }

	  if(reg_email == ''){
	      $('#email').addClass('error_1');
	      errors += 1;
	  }

	  if(reg_password == ''){
	      $('#password').addClass('error_1');
	      errors += 1;
	  }

	  if(reg_company == ''){
	      $('#company').addClass('error_1');
	      errors += 1;
	  }




	  if(errors >= 1){
	      console.log('Great error!' + errors);
	      addAlert(3,"<?php echo _('Woops! Looks like you forgot some fields.'); ?>");
	  }else{

		$.get("/actions/ajax/checkmail.php?email=" + reg_email, function(data) {
		  var data_from_ajax = data;

		  if(data_from_ajax == 1){
		  	  addAlert(3,"<?php echo _('You look a bit familiar. This e-mail is already registered at Plangy. Please use another e-mail address.'); ?>");

		      $('#email').addClass('error_1');
		      errors += 1;
		  }else{

		    $.ajax({
		      method: 'get',
		      url: '/actions/register_createcustomer.php',
		      data: {
		        'firstname': reg_firstname,
		        'lastname': reg_lastname,
		        'phone': reg_phone,
		        'email': reg_email,
		        'company': reg_company,
		        'password': reg_password,
		        'country': reg_country,
		        'plan': plan
		      },
		      success: function() {
		          reg_continue('employees');
		      }
		    });
	        console.log('Great success!' + errors);

		  }

		});

	  }



	});

	$('html').bind('keypress', function(e){
	   if(e.keyCode == 13)
	   {
	      return false;
	   }
	});


</script>

