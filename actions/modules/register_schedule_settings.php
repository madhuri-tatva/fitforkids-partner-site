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

?>

<div class="form-inner">

	<h2><?php echo _('Create your first work schedule'); ?></h2>
	<p><?php echo _('Choose which period and roll.'); ?></p>

	<form action="" id="preschedule" method="post">
	  <?php 
	      $current_week = date('W'); 
	      $current_week = (int)$current_week;

	      $suggested_from_week = $current_week;
	      $suggested_to_week = $current_week + 3;
	  ?>

	    <input type="hidden" name="DepartmentId" value="<?php echo $_SESSION['CurrentDepartment']; ?>" />

	    <div class="form-group md-col-12 form-fields">
	        <div id="Year" class="form-group md-col-3">
	            <label class="control-label col-sm-12 col-md-12" for="">
	                <?php echo _('Year'); ?>
	            </label>
	            <div class="col-sm-8 col-md-12 col-2 btn-extra">
	                <select name="Year" class="form-control">
	                    <option value="2018">2018</option>
	                    <option value="2019">2019</option>
	                </select>
	            </div>
	        </div>
	        <div id="FromWeek" class="form-group md-col-3">
	            <label class="control-label col-sm-12 col-md-12" for="">
	                <?php echo _('From week'); ?>
	            </label>
	            <div class="col-sm-8 col-md-12">
	                <select name="WeekFrom" class="form-control startweek">
	                    <?php 
	                    for($i=1; $i <= 52; $i++){ ?>
	                        <option value="<?php echo $i; ?>" <?php if($i == $suggested_from_week){ echo "selected";} ?>><?php echo $i; ?></option>
	                    <?php
	                    }
	                    ?>
	                </select>
	            </div>
	        </div>
	        <div id="ToWeek" class="form-group md-col-3">
	            <label class="control-label col-sm-12 col-md-12" for="">
	                <?php echo _('To week'); ?>
	            </label>
	            <div class="col-sm-8 col-md-12">
	                <select name="WeekTo" class="form-control endweek">
	                    <?php 
	                    for($i=1; $i <= 52; $i++){ ?>
	                        <option value="<?php echo $i; ?>" <?php if($i == $suggested_to_week){ echo "selected";} ?>><?php echo $i; ?></option>
	                    <?php
	                    }
	                    ?>
	                </select>
	            </div>
	        </div>
	        <div class="form-group md-col-3">
	            <label class="control-label col-sm-12 col-md-12" for="">
	                <?php echo _('Roll'); ?>
	                <span class="helper"><img src="/assets/img/icon-question.png" />
	                	<div class="caption">
	                		<h4><?php echo _('What is roll?'); ?></h4>
	                		<p><?php echo _('Roll determins the week loop of your schedule.') . " " . _("For example if you choose to schedule from week 19 to 26 and select 3 rolls, every third week will be the same. In this case:"); ?></p>
	                		<ul>
	                			<li><p><?php echo _('Work shifts in week 19, 22 and 25 will be identical'); ?></p></li>
	                			<li><p><?php echo _('Work shifts in week 20, 23 and 26 will be identical'); ?></p></li>
	                			<li><p><?php echo _('And work shifts in week 21 and 24 will be identical'); ?></p></li>
	                		</ul>
	                	</div>
	                </span>
	            </label>
	            <div id="Roll" class="col-sm-8 col-md-12 col-4 btn-extra">
	                <select name="Frequence" class="form-control">
	                    <option value="1">1</option>
	                    <option value="2">2</option>
	                    <option value="3">3</option>
	                    <option value="4">4</option>
	                </select>
	            </div>
	        </div>
	    </div>
	</form>

	<div class="divider"></div>
	<div class="form-action" style="margin-right: 10px; margin-top: 10px;">
		<span id="submit" class="btn-cta"><?php echo _('Continue'); ?></span>
		<span id="skip" class="btn-std"><?php echo _('Skip'); ?></span>
	</div>

</div>

<script type="text/javascript">

	$('#submit').click( function() {

		var errors = 0;

		addAlertModal(0,"");

		var year = '';
		var weekfrom = '';
		var weekto = '';
		var frequence = '';

		var startweek = $('.startweek').val();
		var endweek = $('.endweek').val();


		$(".startweek").removeClass('error');

		if(startweek > endweek){
		  	$(".startweek").addClass('error');
		}else{
			$(".startweek").removeClass('error');
		}

        $("#Year").find("li.selected").each(function(){
            year = $(this).attr("data-value");  
        });

        $("#FromWeek").find("li.selected").each(function(){
            weekfrom = $(this).attr("data-value");  
        });

        $("#ToWeek").find("li.selected").each(function(){
            weekto = $(this).attr("data-value");  
        });

        $("#Roll").find("li.selected").each(function(){
            frequence = $(this).attr("data-value");  
        });

		$("html").find(".error").each(function(){
		  	errors += 1;
		  	addAlert(3,"<?php echo _("Something on your start and end time isn't right. Please check if the fields is correct."); ?>");
		});

		if(errors >= 1){
		  	console.log('Great error!' + errors);
		}else{
		  	console.log('Great success!' + errors);

		    $.ajax({
		      method: 'get',
		      url: '/actions/register_setup_schedule.php',
		      data: {
		        'frequence': frequence,
		        'year': year,
		        'weekfrom': weekfrom,
		        'weekto': weekto
		      },
		      success: function() {
		          reg_continue('schedule');
		      }
		    });

		}

	    return false; 
	});



    $('#skip').click( function() {

        reg_continue('success');

        return false; 

    });

    jQuery(function($) {
      $('select.form-control').niceSelect();
    });

</script>