<?php 
include("../../includes/config.php");

putenv('LC_ALL='.$_SESSION['Language']);
setlocale(LC_ALL, $_SESSION['Language']);

bindtextdomain($_SESSION['Language'], "../../locale");
bind_textdomain_codeset($_SESSION['Language'], 'UTF-8'); 

textdomain($_SESSION['Language']);

$date = $_GET['date'];

$events = events_by_date_department($date,$_SESSION['CurrentDepartment']);


if(!empty($events)){ ?>
    
    <!--<h1><?php echo _('Events'); ?></h1>-->

    <div class="events-inner"> 

        <ul class="<?php if($_SESSION['Type'] == 3){ echo "event-emp"; }?>">
        <?php 
        foreach($events as $event){
        ?>

            <li>
                <span>
                    <?php if($_SESSION['Type'] < 3){ ?>
                    <div class="event-menu">
                        <span id="btn-edit-event-menu" class="btn btn-edit-event-menu"><i class="pencil-2icon-"></i></span>
                        <span id="btn-delete-event-menu" class="btn btn-delete-event-menu"><img src="/assets/img/icon-cancel.png"></span>
                        <input type="hidden" class="eventid" value="<?php echo $event['Id']; ?>" />
                    </div>
                    <?php } ?>
                    <i class=""><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19.57 52.18"><defs><style>.cls-1{fill:#57b782;}</style></defs><title>Noteinfo01</title><g id="Lag_2" data-name="Lag 2"><g id="Colortheme"><path class="cls-1" d="M2,43.21h2V28.54H2a2,2,0,0,1-1.43-.62A2,2,0,0,1,0,26.5V21.61a2,2,0,0,1,.61-1.43A2,2,0,0,1,2,19.57H13.45a2.08,2.08,0,0,1,2,2v21.6h2a2.08,2.08,0,0,1,2,2v4.89A2.05,2.05,0,0,1,19,51.57a2,2,0,0,1-1.43.61H2a2,2,0,0,1-1.43-.61A2,2,0,0,1,0,50.14V45.25a2,2,0,0,1,.61-1.43A2.05,2.05,0,0,1,2,43.21ZM9.78,0A7,7,0,0,0,4.59,2.14a7.07,7.07,0,0,0-2.14,5.2,7.07,7.07,0,0,0,2.14,5.2,7.09,7.09,0,0,0,5.19,2.14A7.11,7.11,0,0,0,15,12.54a7.07,7.07,0,0,0,2.14-5.2A7.07,7.07,0,0,0,15,2.14,7.07,7.07,0,0,0,9.78,0Z"/></g></g></svg></i> 
                    <?php echo $event['Title']; ?>
                </span>
                <?php if(!empty($event['Description'])){ ?>

                <div class="toggle">
                    <input name="" class="input-expand" type="checkbox" id="" value="">
                    <span class="checkmark"><?php echo _('See more'); ?></span>

                    <div class="expand"><?php echo $event['Description']; ?></div>
                </div>
                <?php } ?>
            </li>

        <?php } ?>
        </ul>

    </div>

<?php } ?>


<script type="text/javascript">

    $('.btn-delete-event-menu').click( function() {
        console.log('Great success!');

        var event_id = $(this).parents('div:eq(0)').find('.eventid').val();

        $.ajax({
          method: 'post',
          url: '/actions/ajax/crud_event.php',
          data: {
            'EventId': event_id,
            'EventActionType': 3,
          },
          success: function() {
            $('#event-editor .event-inner').remove();

                setTimeout(function() {
                    $("#events").load("https://www.plangy.com/actions/modules/eventlist_sidebar.php?date=<?php echo $date; ?>");
                }, 300);

          }
        });
     
    });


    $('.btn-edit-event-menu').click( function() {

        var event_id = $(this).parents('div:eq(0)').find('.eventid').val();

        $("#list").addClass('fade-away');
        $(".list-add-outer").addClass('fade-away');
        $("#event-editor").load("https://www.plangy.com/actions/modules/event_create_sidebar.php?date=<?php echo $date; ?>&id="+event_id);

    });
</script>