
        <ul class="meta-navigation">

          <?php if(($_SESSION['Type']) < 3){ ?>

            <li><a href="">
<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 24 24">
  <path d="m23,10h-8.5c-0.3,0-0.5-0.2-0.5-0.5v-8.5c0-0.6-0.4-1-1-1h-2c-0.6,0-1,0.4-1,1v8.5c0,0.3-0.2,0.5-0.5,0.5h-8.5c-0.6,0-1,0.4-1,1v2c0,0.6 0.4,1 1,1h8.5c0.3,0 0.5,0.2 0.5,0.5v8.5c0,0.6 0.4,1 1,1h2c0.6,0 1-0.4 1-1v-8.5c0-0.3 0.2-0.5 0.5-0.5h8.5c0.6,0 1-0.4 1-1v-2c0-0.6-0.4-1-1-1z"/>
</svg>
            </a>
                <ul class="submenu">
                    <i class="arrow"></i>
                    <li class="first"><a class="md-trigger" data-modal="modal-create-schedule">Opret ny vagtplan</a></li>
                    <li><a class="md-trigger" data-modal="modal-download">Download vagtplan</a></li>
                    <li><a href="/add-user">Tilføj ny medarbejder</a></li>
                    <li class="last"><a href="/add-department">Tilføj ny butik</a></li>
                </ul>
            </li>

          <?php } ?>


<?php $notifications = get_notifications(); ?>

            <li><a class="md-trigger" data-modal="modal-notifications" onclick="modal_notifications()">
<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
   viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
<g>
  <g>
    <path d="M467.812,431.851l-36.629-61.056c-16.917-28.181-25.856-60.459-25.856-93.312V224c0-67.52-45.056-124.629-106.667-143.04
      V42.667C298.66,19.136,279.524,0,255.993,0s-42.667,19.136-42.667,42.667V80.96C151.716,99.371,106.66,156.48,106.66,224v53.483
      c0,32.853-8.939,65.109-25.835,93.291l-36.629,61.056c-1.984,3.307-2.027,7.403-0.128,10.752c1.899,3.349,5.419,5.419,9.259,5.419
      H458.66c3.84,0,7.381-2.069,9.28-5.397C469.839,439.275,469.775,435.136,467.812,431.851z"/>
  </g>
</g>
<g>
  <g>
    <path d="M188.815,469.333C200.847,494.464,226.319,512,255.993,512s55.147-17.536,67.179-42.667H188.815z"/>
  </g>
</g>
</svg><em class="badge-notifications <?php if($notifications != 0){ echo 'highlight'; } ?>"><?php echo $notifications; ?></em></a></li>




            <li class="meta-profile">
                <a href="/edit-user/<?php echo $_SESSION['UserId']; ?>">

                    <div class="profile-img">
                        <div class="profile-img-frame">
                            <span class="noimage" style="background: #<?php echo $_SESSION['AvatarBackgroundColor']; ?>; color: #<?php echo $_SESSION['AvatarTextColor']; ?>;"><?php echo $_SESSION['Initials']; ?></span>
                        </div>
                    </div>

                    <div class="profile-tag">

                    <strong><?php echo $_SESSION['Firstname'] . " " . $_SESSION['Lastname']; ?></strong>

                    <em>
                    <?php
                        if($_SESSION['Type'] == 1){
                            echo _('Owner');
                        }elseif($_SESSION['Type'] == 2){
                            echo _('Manager');
                        }elseif($_SESSION['Type'] == 3){
                            echo _('Employee');
                        }
                    ?>
                    </em>
                    </div>
                </a>
            </li>


        </ul>
        