<?php 
include("../../includes/config.php");
// echo "<pre>";
// print_r($_SESSION);
    if($_SESSION['Admin'] == 0 && $_SESSION['EmployeeGroup'] == 0){
        $db->where('EmployeeGroup', 1);
    } else {
        $db->where('EmployeeGroup', 0);
        $db->where('ParentId', 0);
    }
    $users = $db->get('users');
    if($_SESSION['Admin'] == 0 && $_SESSION['EmployeeGroup'] == 0){
        unset($users[2]);
        unset($users[1]);
    }
?>
<style>
    .section.compose-section {
        overflow: hidden;
        color:rgb(66,33,00);
        margin-bottom: 0px;
    }
    .composer-form input, .composer-form textarea, .composer-form .nice-select {
        background: #ffffff;
        border: 1px solid rgb(66,33,00);
        border-radius: 5px;
    }
    .composer-form .nice-select {
        margin-bottom:10px;   
        padding-left: 10px;
        padding-right: 20px;
    }
    .modal-header.compose-header h3{
        color:rgb(66,33,00);
        line-height:10px;
    }
    .compose-btn {       
        background: #ffbad6;
        color: rgb(66,33,00) !important;
    }
    .compose-btn:hover, .compose-btn:focus,.compose-btn:active, .compose-btn:active:hover {
        background: #ffbad6;
    }    
    /* .composer-form option:checked, .composer-form option:hover {
        background: red -webkit-linear-gradient(bottom, red 0%, red 100%) !important;
        box-shadow: 0 0 10px 100px red inset !important;
    } */
    .composer-form .form-success {
        display:none;
        color: rgb(66,33,00);
    }
</style>
<div class="modal-header compose-header">
    <h3>Ny besked</h3>
	<button class="md-close compose_close_icon"></button>
</div>
<div class="section compose-section">    
    <div class="compose-inner"> 
        <form class="composer-form" id="composer-form" action="" method="POST" enctype="multipart/form-data">
        <p class="form-success"><span>besked blev sendt.</span></p>
            <p>Til*:<select id="to_user" name="to_user" class="nice-select">
                <option value="0">Vælg venligst</option>                
                <?php 
                foreach($users as $user){ ?>
                    <option value="<?php echo $user['Id']; ?>"><?php echo $user['Firstname'].' '.$user['Lastname']; ?></option>
                <?php } ?>
                </select>
            </p>
            <span id="ToErr" class="required-field hide">Til er påkrævet</span>
            <p>Emne*:<input type="text" name="subject" id="subject"><span id="subjectErr" class="required-field hide">Emne er påkrævet</span></p>
            <p>Tekst*: <textarea name="message" id="message"></textarea><span id="messageErr" class="required-field hide">Tekst er påkrævet</span></p>
            <p>Vedhæftet fil: <input name="attachment" type="file" accept=".mp3,.pdf" id="sortfile"/><span>maks: 20mb</span>
                <span id="fileErr" class="required-field hide"></span>
            </p>
            <p><button type="submit" class="btn-cta btn-primary compose-btn">Send</button></p>
        </form>
    </div>
</section>
<script>
    var fileErr = 0;
    $(document).off('click','.compose_close_icon');
    $('html').on('click','.compose_close_icon',function(){
        $('.compose-modal').removeClass('md-show');
        $('#compose-content-modal').html('');        
    }); 
    $('#subject').blur(function() {
        if($(this).val() ) {
          $("#subjectErr").addClass("hide");
        }
    });
    $( "#to_user" ).change(function() {
        if(this.value > 0) {
            $('#ToErr').addClass("hide");
        }
    });
    $('#message').bind('input propertychange', function() {
        if(this.value.length){
            $('#messageErr').addClass('hide');
        }
    });
    $("#sortfile").change(function() {
        var file = this.files[0];
        if(file) {
            var fileType = file.type;
            console.log("fileType", file);
            var match = ['application/pdf', 'application/mp3'];
            if(jQuery.inArray(fileType, match) == -1) {
                $('#fileErr').html('Upload kun mp3 og pdf');
                $('#fileErr').removeClass('hide');
                fileErr = 1;
                return;
            } else {
                $('#fileErr').html('');
                $('#fileErr').addClass('hide');
                fileErr = 0;
            } 
            var size = Math.round(file.size /1024);
            
            if(size > 20480){
                $('#fileErr').html('Upload filen mindre end 20 MB');
                $('#fileErr').removeClass('hide');
                fileErr = 1;
            }  else {
                $('#fileErr').html('');
                $('#fileErr').addClass('hide');
                fileErr = 0; 
            }
        }     
    });
    $("#composer-form").submit(function( event ) {
        var error = 0; 
        if($("#subject").val() == '') {
            $("#subjectErr").removeClass("hide");
            error = 1;
        }
        if($('#to_user option:selected').val() == 0){
            $('#ToErr').removeClass("hide");
            error = 1;
        } 
        if($('#message').val().trim().length == 0) {
            $('#messageErr').removeClass('hide');
            error = 1;
        } 
        if(error > 0 || fileErr > 0) {
            return false;
        }
        event.preventDefault();
        var data = $('#composer-form').serialize();
        $('.loader').show();
        $.ajax({ 
            url: '/actions/ajax/sentInternalMail.php',
            dataType: 'text',  // <-- what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: new FormData(this),      
	        type: 'post',
	        success: function(res) {
                $('.loader').show();
                $(".form-success").show();
                setTimeout(function() { $(".form-success").hide(); location.reload();}, 5000);
                console.log(res);  
                $('#composer-form').each(function(){
                    this.reset();
                });                                  
	        }
	    });             
    });
</script>