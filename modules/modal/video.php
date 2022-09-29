<?php
    include("../../includes/config.php");
    $videoUrl = $_GET['action'];
    //set default timezone and get time.
    date_default_timezone_set(date_default_timezone_get());
    $time = date('H.i', time());
    $db->where('id',$_SESSION['UserId']);
    $user = $db->getOne('users');

?>
<style>
    #close {
        cursor: pointer;
        padding: 5px 7px;
        background: #ffbfffad;
    }

    .video-popup-btn{
        border: 2px solid pink;
        background-color: #fff;
        color: rgb(66,33,00);
        font-size: 14px !important;
        padding: 8px;
        border-radius: 5px;
        font-weight: 600;
    }
</style>
<div class="modal-header calender-header">
	<button class="md-close video_close_icon"></button>
</div>
<div class="section overview-video-section">
	<div class="video-responsive">
    	<iframe id="cartoonVideo" width="100%" height="500px" src="<?php echo $videoUrl;?>" title="video-frame" frameborder="0" autoplay allowfullscreen></iframe>
	</div>
    <div class="video-below-section">
        <p class="mtb-10">
        Coaching hjælper dig til at sætte mål, du brænder for at nå, og med at bevare energien, så du kan nyde din rejse til succes! Coaching er også et trygt rum for dig til at tale om sensitive emner. Coaching er 100% privat.
        <!-- Vil du tale med din FitforKids Coach? -->
            <!-- <br/> Her er dine muligheder: -->
        </p>
        <p>
            <!-- <a href="javascript:void(0);" onclick="clickHere(1)">Lige nu? Klik her!</a> -->
            <!-- <button type="button" onclick="clickHere(1)" class="video-popup-btn">Lige nu?</button> -->
        </p>
        <div class="right-now-btn">
            <p><button class="btn btn-cta btn-send-inquiry call_btn" data-tel="tel:70 70 29 20">Vil du tale med os lige nu?<br/> Så ring til os : 70 70 29 20</button>
            <span class="sub-class">(mellem kl. 9.30-16.30 og<br/> 20.00-21.30 på hverdage)</span> </p>
        </div>
        <p>
            <!-- <a href="javascript:void(0);" class="sent-email" onclick="clickHere(2)">Inden for 24 timer? Så Klik her! </a> -->
            <!-- <button type="button" onclick="clickHere(2)" class="sent-email video-popup-btn">Inden for 24 timer?</button> -->
        </p>
        <div class="video-modal-message"><p>
            <sub> Tak for at række ud til os, <?php echo $user['Firstname'].'!'; ?> <br/> Vi vil meget gerne lytte, og hjælpe dig videre mod målet! Vi ringer til dig på telefonnummer <?php echo $user['PhoneNumber']; ?> indenfor 24 timer.<br/><br/> Kh FitforKids Coach Team<br/><span id="close">Luk vindue</span></sub>
            </p>
        </div>
        <p>
            <!-- <a href="javascript:void(0);" data-modal="calendar-modal" onclick="openCalendarModal('/uploads/testingv_1615360094_1616677614.mp4')">Book en aftale </a> -->
            <button type="button" data-modal="calendar-modal" onclick="openCalendarModal('/uploads/testingv_1615360094_1616677614.mp4')" class="video-popup-btn">Book trygt en coaching. Klik 
            <!-- Book en aftale<br/>op til 14 dage frem -->
            </button>
        </p>
    </div>
</div>
<script>
	$(document).ready(function() {
        $('.right-now-btn').hide();
        $(".video-modal-message").hide();
	    var src = $('.videoplayer').children('iframe').attr('src');
        $('#modal-video').click(function(e) {
            e.preventDefault();
            $('.video-responsive').children('iframe').attr('src', src);
            $('.modal-background').fadeIn();
        });
        $('.sent-email').click(function(e){
            sendEmail();
        });
        $('.call_btn').click(function(e){
		    let  isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
            if (isMobile)
                window.location.href = $(this).data('tel');
            return false;
        });

	});

    function clickHere(arg = 1) {
        if(arg === 1) {
            $('.right-now-btn').toggle();
        } else if( arg === 2) {
            $('.right-now-btn').hide();
        } else {
            $('.right-now-btn').hide();
        }
    }
    function sendEmail(){
        //Use for send email for contact details
        $('.loader').show();
        $.ajax({
                method: 'post',
                url: '/actions/ajax/contactEmail.php',
                data: {},
                success: function() {
                    $(".video-modal-message").show();
                    $('.loader').hide();
                    setTimeout(function() { $(".video-modal-message").hide(); }, 60000);
                }
            });
    }
    $(document).off('click','.video_close_icon');
    $('html').on('click','.video_close_icon, #close',function(){
        $('.video-modal').removeClass('md-show');
        $('#video-content-modal').html('');
    });

</script>