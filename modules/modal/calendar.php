<?php 
    include("../../includes/config.php");
    $videoUrl = $_GET['action'];  
?>
<script>
    jQuery(document).ready(function($) {
        var calendarEl = document.getElementById('calendar');
        var time_arr = ['09','10','11','12','13','14','15','16','17','18','19','20','21'];
        var calendar = new FullCalendar.Calendar(calendarEl, {    
            headerToolbar: {
                left: 'title',
                center: '',
                right: 'prev,next'
            },
            locale: 'da',
            initialView: 'timeGridDay',
            navLinks: false, // can click day/week names to navigate views
            selectable: true,       
            timeZone: 'Europe/Berlin',
            firstDay: 1,           
            slotDuration: '00:30:00',
            slotLabelInterval: 15,
            slotMinTime:"09:30:00",
            slotMaxTime:"21:30:00",
            longPressDelay: 1,
            selectLongPressDelay: 1,
            slotLabelFormat:{
                hour: 'numeric',
                minute: '2-digit',
                omitZeroMinute: false,
                meridiem: 'short'
            },        
            validRange: {
                start: moment().format('YYYY-MM-DD HH:mm:ss'),
                end: moment().add(2,'weeks').format('YYYY-MM-DD')
            },
            hiddenDays: [ 0, 6 ], // For hide saturday and sunday from list       
        
            select: function(arg) {
                var startDate = arg.startStr.split('T');
                var dArr = startDate[0].split('-');
                var tArr = startDate[1].split(':');
                let startTimeStamp = Date.UTC(dArr[0], dArr[1], dArr[2], tArr[0], tArr[1], tArr[2]);               
                var currentTimestamp = getEuropeCurrentTimeStamp();

                console.log("startTimeStamp", startTimeStamp, currentTimestamp);
                if(calendar.getEvents().length > 0 ){
                    calendar.unselect();   
                    return false;
                }
                var diffMs = (arg.end - arg.start);
                if(diffMs > 1800000) {
                    calendar.unselect();   
                    return false;
                } 
                if(jQuery('.fc-day-today').length > 0){    
                    if(currentTimestamp > startTimeStamp) {
                        calendar.unselect();   
                        return false;
                    } 
                }
                $(".event-book-success").hide();      
                $('.loader').show();                  
                $.ajax({
                    method: 'post',
                    url: '/actions/ajax/bookEvent.php',
                    data: {'startDate':arg.startStr , 'endDate' : arg.endStr, 'type' : 1, 'title' : 'Reserveret' },
                    success: function(id) {   
                        $('.loader').hide();
                        calendar.refetchEvents();                         
                        jQuery('td.fc-timegrid-slot.fc-timegrid-slot-label.fc-scrollgrid-shrink').parent().addClass('disable');
                        $(".event-book-success sub").html('Tak fordi du bookede en tid!<br/> Vi glæder os til at tale med dig');
                        $(".event-book-success").show();
                        setTimeout(function() { $(".event-book-success").hide(); }, 30000);
                    }
                });  
            },                   
            eventClick: function(arg) {
                console.log("Arg", arg);
                $(".event-book-success").hide();
                var sDate = arg.event.startStr.split('T');
                var edArr = sDate[0].split('-');
                var etArr = sDate[1].split(':');
                let sTimeStamp = Date.UTC(edArr[0], edArr[1], edArr[2], etArr[0], etArr[1], etArr[2]);               
                var cTimestamp = getEuropeCurrentTimeStamp();

                if(jQuery('.fc-day-today').length > 0){    
                    if(cTimestamp > sTimeStamp)
                        return false;                    
                } 
                $('.loader').show();
                $.ajax({
                    method: 'post',
                    url: '/actions/ajax/bookEvent.php',
                    data: {'startDate':arg.event.startStr, 'endDate' : arg.event.endStr, 'id':arg.event.id, 'type' : 2 },
                    success: function(id) { 
                        $('.loader').hide();
                        disableTimeSlot();   
                        arg.event.remove();
                        $(".event-book-success sub").html('Din aftale er hermed annulleret');
                        $(".event-book-success").show();
                        setTimeout(function() { $(".event-book-success").hide(); }, 30000);
                    }
                });                      
            },
            eventSources: [
                '/actions/ajax/bookingSlot.php'            
            ],         
            loading: function(bool) {
                if (!bool){  
                    /*Remove slot from 16:30 to 20:00 */                         
                    var arr_time = ['00','30'];
                    jQuery('td.fc-timegrid-slot.fc-timegrid-slot-label.fc-scrollgrid-shrink[data-time="16:30:00"]').parent().remove();               
                    jQuery(arr_time).each(function(e){
                        jQuery('td.fc-timegrid-slot.fc-timegrid-slot-label.fc-scrollgrid-shrink[data-time="17:'+this+':00"]').parent().remove();               
                        jQuery('td.fc-timegrid-slot.fc-timegrid-slot-label.fc-scrollgrid-shrink[data-time="18:'+this+':00"]').parent().remove();
                        jQuery('td.fc-timegrid-slot.fc-timegrid-slot-label.fc-scrollgrid-shrink[data-time="19:'+this+':00"]').parent().remove();               

                    });
                    setTimeout(() => {
                        if(calendar.getEvents().length > 0 ){
                            jQuery('td.fc-timegrid-slot.fc-timegrid-slot-label.fc-scrollgrid-shrink').parent().addClass('disable');
                        } else {
                            disableTimeSlot();                            
                        } 
                    });
                }
            }        
        });
         function disableTimeSlot(){
            var todayDate = new Date();
            var todayTime = todayDate.toLocaleTimeString('da-DK', {
                                hour: '2-digit',
                                minute: '2-digit',
                                timeZone: 'Europe/Berlin',   
                            });
            
            var timeArr = todayTime.split('.');
            var currentTime_h = timeArr[0]; 
            var currentTime_m = timeArr[1]; 
            if(jQuery('.fc-day-today').length > 0){     
                jQuery(time_arr).each(function(e){     
                    if(currentTime_h > this){
                        jQuery('td.fc-timegrid-slot.fc-timegrid-slot-label.fc-scrollgrid-shrink[data-time="'+this+':30:00"]').parent().addClass('disable');               
                        jQuery('td.fc-timegrid-slot.fc-timegrid-slot-label.fc-scrollgrid-shrink[data-time="'+this+':00:00"]').parent().addClass('disable');               
                    }
                    else if(currentTime_h == this){
                        jQuery('td.fc-timegrid-slot.fc-timegrid-slot-label.fc-scrollgrid-shrink[data-time="'+this+':00:00"]').parent().addClass('disable');               
                        if(currentTime_m > 30 ){
                            jQuery('td.fc-timegrid-slot.fc-timegrid-slot-label.fc-scrollgrid-shrink[data-time="'+this+':30:00"]').parent().addClass('disable');               
                        } else {
                            jQuery('td.fc-timegrid-slot.fc-timegrid-slot-label.fc-scrollgrid-shrink[data-time="'+this+':30:00"]').parent().removeClass('disable');  
                        }
                    } else {
                        jQuery('td.fc-timegrid-slot.fc-timegrid-slot-label.fc-scrollgrid-shrink[data-time="'+this+':30:00"]').parent().removeClass('disable');
                        jQuery('td.fc-timegrid-slot.fc-timegrid-slot-label.fc-scrollgrid-shrink[data-time="'+this+':00:00"]').parent().removeClass('disable');   
                    }
                });
            } else {                
                jQuery(time_arr).each(function(e){
                    jQuery('td.fc-timegrid-slot.fc-timegrid-slot-label.fc-scrollgrid-shrink').parent().removeClass('disable');               
                });
            }  
        } 
        calendar.render();  
        function getEuropeCurrentTimeStamp(){
            var date = new Date();
            var time = date.toLocaleTimeString('da-DK', {
                        year:'numeric',
                        month:'2-digit',
                        day:'2-digit',
                        timeZone: 'Europe/Berlin',   
                    });
            var arr = time.split(' ');
            var dateArr = arr[0].split('.');
            var timeArr = arr[1].split('.');
            return Date.UTC(dateArr[2], dateArr[1], dateArr[0], timeArr[0], timeArr[1], timeArr[2]);
        }
    });
</script>
<div class="modal-header calender-header">
	<button class="md-close calendar_close_icon"></button>
</div>

<div class="section overview-video-section">
    <div class="video-responsive">
    	<iframe id="cartoonVideo" width="100%" height="500px" src="<?php echo $videoUrl;?>" title="video-frame" frameborder="0" autoplay allowfullscreen></iframe>
	</div>
    <div class="video-below-section"> 
        <p>Her kan du booke 30 min med din FitforKids Coach inden for de næste 14 dage</p>
        <p class="event-book-success"><sub>Tak fordi du bookede en tid!<br/> Vi glæder os til at tale med dig</sub></p>
        <div id='calendar' class="booking-calender"></div>
    </div>
</section>
<script>
    jQuery(document).ready(function($) {
        moment.locale('da');
        var headerRange = moment().format('ll')+' - '+ moment().add(2,'weeks').format('ll');       
    });
    $(document).off('click','.calendar_close_icon');
    $('html').on('click','.calendar_close_icon',function(){
        $('.video-modal').addClass('md-show');
        $('.calendar-modal').removeClass('md-show');
        $('#calendar-content-modal').html('');        
    });    
</script>