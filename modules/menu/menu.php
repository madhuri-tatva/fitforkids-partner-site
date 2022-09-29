
        <ul class="meta-navigation">

          <?php if(($_SESSION['Type']) < 3){ ?>

            <li><a href="">
<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 24 24">
  <path d="m23,10h-8.5c-0.3,0-0.5-0.2-0.5-0.5v-8.5c0-0.6-0.4-1-1-1h-2c-0.6,0-1,0.4-1,1v8.5c0,0.3-0.2,0.5-0.5,0.5h-8.5c-0.6,0-1,0.4-1,1v2c0,0.6 0.4,1 1,1h8.5c0.3,0 0.5,0.2 0.5,0.5v8.5c0,0.6 0.4,1 1,1h2c0.6,0 1-0.4 1-1v-8.5c0-0.3 0.2-0.5 0.5-0.5h8.5c0.6,0 1-0.4 1-1v-2c0-0.6-0.4-1-1-1z"/>
</svg>
            </a>
                <ul class="submenu">
                    <i class="arrow"></i>
                    <li class="first"><a><?php echo _('Create new landing page'); ?></a></li>
                </ul>
            </li>

          <?php } ?>


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
        