<?php 
include("../../includes/config.php");

putenv('LC_ALL='.$_SESSION['Language']);
setlocale(LC_ALL, $_SESSION['Language']);

bindtextdomain($_SESSION['Language'], "../../locale");
bind_textdomain_codeset($_SESSION['Language'], 'UTF-8'); 

textdomain($_SESSION['Language']);

$date   = null;
$id     = null;

if(isset($_GET['date'])){
    $date   = $_GET['date'];
}

if(isset($_GET['id'])){
    $id     = $_GET['id'];
    $event  = events_by_id($id);
}


?>

<div class="event-inner">

    <?php if(empty($id)){ ?>
        <h1><?php echo _('Create an event for'); ?> <?php echo convert_date_pretty($date,$_SESSION['Country']); ?></h1>
    <?php }else{ ?>
        <h1><?php echo _('Edit event') . " " . $event['Title']; ?></h1>
    <?php } ?>

    <span id="btn-cancel-event"><img src="/assets/img/icon-cancel.png"></span>

    <div class="md-col-12 form-group">
        <input type="text" id="input-event-title" class="form-control" placeholder="<?php echo _('Event title'); ?>" value="<?php if(!empty($event['Id'])){ echo $event['Title'];} ?>" />
    </div>

    <div class="md-col-12 form-group">
        <textarea type="text" id="input-event-description" class="form-control" placeholder="<?php echo _('Event description'); ?>"><?php if(!empty($event['Id'])){ echo $event['Description'];} ?></textarea>
    </div>

    <div class="md-col-6 form-group" id="input-event-group">
        <select name="" class="form-control">
            <option value="0"><?php echo _('Visible for') . " " . _('all'); ?></option>
        <?php 
            $data_titles = get_all_titles($_SESSION['CustomerId'],$_SESSION['CurrentDepartment']);

            foreach ($data_titles as $title){

                $selected = ""; 

                if(!empty($event['Id'])){ 
                    if($event['TitleGroup'] == $title['Id']){ 
                        $selected = "selected"; 
                    }
                } 

                echo "<option value='". $title['Id'] ."' ".$selected.">" . _('Visible for') . " " . $title['Title'] . "</option>";
            }
        ?>
        </select>
    </div>


    <div class="md-col-6">

        <?php if(empty($id)){ ?>
            <a class="btn-cta" id="btn-create-event"><?php echo _('Create event'); ?></a>
        <?php }else{ ?>
            <a class="btn-cta" id="btn-edit-event"><?php echo _('Edit event'); ?></a>
        <?php } ?>
        
    </div>

    <script type="text/javascript">

    $('#btn-cancel-event').click( function() {
        $(this).parents("div:eq(0)").remove();
        $(".list-add-outer").removeClass('fade-away');
        $("#list").removeClass('fade-away');
    }); 

    $('#btn-create-event').click( function() {

        addAlert(0,"");
        var errors = 0;

        var event_title         = $('#input-event-title').val();
        var event_description   = $('#input-event-description').val();

        $("#input-event-group").find("li.selected").each(function(){
            event_group = $(this).attr("data-value");  
        });

        if(event_title == ''){
            $('#input-event-title').addClass('error');
            errors += 1;
        }


        if(errors < 1){
            console.log('Great success!');

            $.ajax({
              method: 'post',
              url: '/actions/ajax/crud_event.php',
              data: {
                'EventTitle': event_title,
                'EventDescription': event_description,
                'EventGroup': event_group,
                'EventDate': '<?php echo $date; ?>',
                'EventActionType': 1
              },
              success: function() {
                $('#event-editor .event-inner').remove();

                    setTimeout(function() {
                        $("#events").load("https://www.plangy.com/actions/modules/eventlist_sidebar.php?date=<?php echo $date; ?>");
                    }, 300);

              }
            });

        }else{
            console.log('Great error!');
        }

        $(".list-add-outer").removeClass('fade-away');
        $("#list").removeClass('fade-away');
     
    });

    $('#btn-edit-event').click( function() {

        addAlert(0,"");
        var errors = 0;

        var event_title         = $('#input-event-title').val();
        var event_description   = $('#input-event-description').val();

        $("#input-event-group").find("li.selected").each(function(){
            event_group = $(this).attr("data-value");  
        });

        if(event_title == ''){
            $('#input-event-title').addClass('error');
            errors += 1;
        }


        if(errors < 1){
            console.log('Great success!');

            $.ajax({
              method: 'post',
              url: '/actions/ajax/crud_event.php',
              data: {
                'EventId': '<?php echo $id; ?>',
                'EventTitle': event_title,
                'EventDescription': event_description,
                'EventGroup': event_group,
                'EventActionType': 2
              },
              success: function() {

                setTimeout(function() {
                    $("#events").load("https://www.plangy.com/actions/modules/eventlist_sidebar.php?date=<?php echo $date; ?>");
                }, 300);

                $('#event-editor .event-inner').remove();

              }
            });

        }else{
            console.log('Great error!');
        }

        $(".list-add-outer").removeClass('fade-away');
        $("#list").removeClass('fade-away');
     
    });

    jQuery(function($) {
        $('select.form-control').niceSelect();
    });
    </script>

</div>