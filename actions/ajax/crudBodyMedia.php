<?php
include("../../includes/config.php");

if(isset($_POST)){

    if($_POST['Action'] == 1){
        // Insert by user form
        $db->where('UserID',$_POST['UserID']);
        $db->orderBy('ID','desc');
        $tempData=$v = $db->getOne('body_media_temp');
        if($tempData) {
            $thumbnail = '';
            $data = array(
                    "Title"    => $_POST['Title'],
                    "Type"     => $_POST['Type'],
                    "SubType"   => $_POST['SubType'],
                    "UserID"    => $_POST['UserID'],
                    "Media"    => $v['URL'],
                    "Thumbnail"  => $thumbnail,
                    "MediaType"   => $v['MediaType'],
                    "Admin"   => $_SESSION['Admin'],
                    "CreatedDate"  => $db->now(),
                    "UpdatedDate"  => $db->now()
            );
         $last_id = $db->insert('body_media', $data);
            $db->where('ID',$v['ID']);
	        $db->delete('body_media_temp');
    }
    // var_dump($tempData);exit;

    exit;

    }elseif($_POST['Action'] == 2){
        // UPDATE
        $db->where('ID',$_POST['MediaId']);
        $mediaData = $db->getOne('body_media');
        $media = $mediaData['Media'];
        $thumbnail = $mediaData['Thumbnail'];
        $type = $mediaData['MediaType'];
        if(!empty($mediaData)){
            $db->where('UserID',$_POST['UserID']);
            $db->where('Type','Media');
            $db->orderBy('ID','desc');
            $tempDataMedia= $db->getOne('body_media_temp');
            if(!empty($tempDataMedia) && !empty($tempDataMedia['URL'])){
                $media = $tempDataMedia['URL'];
                $type =  $tempDataMedia['MediaType'];
                $db->where('ID',$tempDataMedia['ID']);
                $db->delete('body_media_temp');
                $targetPath = $_SERVER['DOCUMENT_ROOT'].$mediaData['Media'];
                if(!empty($mediaData['Media']) && file_exists($targetPath)){
                    unlink($targetPath);
                }
            }

            $db->where('UserID',$_POST['UserID']);
            $db->where('Type','Thumbnail');
            $db->orderBy('ID','desc');
            $tempDataThumb=  $db->getOne('body_media_temp');

            if(!empty($tempDataThumb) && !empty($tempDataThumb['URL'])){
                $thumbnail = $tempDataThumb['URL'];
                $db->where('ID',$tempDataThumb['ID']);
                $db->delete('body_media_temp');
                $thumb_targetPath = $_SERVER['DOCUMENT_ROOT'].$mediaData['Thumbnail'];
                if(!empty($mediaData['Thumbnail']) && file_exists($thumb_targetPath)){
                     unlink($thumb_targetPath);
                }
            }
                $data_arr = array(
                    "Title"      => $_POST['mediaTitle'],
                    "SubTitle"    => (isset($_POST['mediaSubTitle'])) ? $_POST['mediaSubTitle'] : '',
                    "TotalTime" => (isset($_POST['mediaTotalTime'])) ? $_POST['mediaTotalTime'] : '00.00',
                    "Detail"     => $_POST['mediaDetail'],
                    "Type"       => $_POST['Type'],
                    "SubType"    => $_POST['SubType'],
                    "UserID"     => $_POST['UserID'],
                    "Media"      => $media,
                    "Thumbnail"  => $thumbnail,
                    "MediaType"   => $type,
                    "Admin"   => $_SESSION['Admin'],
                    "Status"   => $_POST['Status'],
                    "UpdatedDate"  => $db->now()
                );
            $db->where('ID',$_POST['MediaId']);
            $db->update('body_media', $data_arr);
            $db->where('UserID',$_POST['UserID']);
            $db->delete('body_media_temp');

            if($_POST['Type']==2){
                echo $basehref.'body/training';
            }elseif($_POST['SubType']==3){
                    header('location: /body/standard-training-program');
            }else{
                // header('location: /body/nutrition');
                echo $basehref.'body/training';
            }
        }else{
            echo 'Media row does not exist';
            // header('location: /body/nutrition');
            echo $basehref.'body/training';
        }
        exit;
    }elseif($_POST['Action'] == 3){

        // DELETE
        exit;

    }elseif($_POST['Action'] == 4){
        // Insert by popup
        $db->where('UserID',$_POST['UserID']);
        $db->orderBy('ID','desc');
        $tempData=$v = $db->getOne('body_media_temp');
        if($tempData){
            $thumbnail ='';
            $media ='';
            if($tempData['Type']=='Media'){
                $media = $v['URL'];
            }elseif($tempData['Type']=='Thumbnail'){
                $thumbnail = $v['URL'];
            }
            $type =  $v['MediaType'];
        }
        $db->where('UserID',$_POST['UserID']);
        $db->where('Type','Media');
        $db->orderBy('ID','desc');
        $tempDataMedia= $db->getOne('body_media_temp');
        if(!empty($tempDataMedia)){
            $media = $tempDataMedia['URL'];
            $type =  $tempDataMedia['MediaType'];
            $db->where('ID',$tempDataMedia['ID']);
	        $db->delete('body_media_temp');
        }

        $db->where('UserID',$_POST['UserID']);
        $db->where('Type','Thumbnail');
        $db->orderBy('ID','desc');
        $tempDataThumb=  $db->getOne('body_media_temp');

        if(!empty($tempDataThumb)){
            $thumbnail = $tempDataThumb['URL'];
            $db->where('ID',$tempDataThumb['ID']);
	        $db->delete('body_media_temp');
        }
        // if(!empty($tempData) && !empty($_POST['Type']) && !empty($_POST['SubType']) && ($media!='' && $thumbnail !='')) {
            if(!empty($tempData) && !empty($_POST['Type']) && !empty($_POST['SubType']) && ($media!='')) {
            $body_media_id = 0;
            if(isset($_POST['MediaID']) && $_POST['MediaID']>0){
                $body_media_id = $_POST['MediaID'];
            }
            $data = array(
                "body_media_id" => $body_media_id, // parent id for training sub videos
                "Title"       => $_POST['mediaTitle'],
                "SubTitle"    => (isset($_POST['mediaSubTitle'])) ? $_POST['mediaSubTitle'] : '',
                "TotalTime"    => (isset($_POST['mediaTotalTime'])) ? $_POST['mediaTotalTime'] : '00.00',
                "Detail"       => $_POST['mediaDetail'],
                "Type"       => $_POST['Type'],
                "SubType"    => $_POST['SubType'],
                "UserID"     => $_POST['UserID'],
                "Media"      => $media,
                "Thumbnail"  => $thumbnail,
                "MediaType"   => $type,
                "Admin"   => $_SESSION['Admin'],
                "CreatedDate"  => $db->now(),
                "UpdatedDate"  => $db->now()
            );
            $insertedId = $db->insert('body_media',$data);
            if(!empty($tempDataMedia)){
                $db->where('ID',$tempDataMedia['ID']);
                $db->delete('body_media_temp');
            }
            if(!empty($tempDataThumb)){
                $db->where('ID',$tempDataThumb['ID']);
                $db->delete('body_media_temp');
            }
            $db->where('UserID',$v['UserID']);
	        $db->delete('body_media_temp');

            if($_POST['MediaID']=='SubMedia' && $insertedId>0){
                echo $basehref.'training-videos/'.$insertedId;
            }elseif(isset($_POST['MediaID']) && $_POST['MediaID']>0){
                echo $basehref.'training-videos/'.$_POST['MediaID'];
            }elseif($_POST['Type']==2){
                echo $basehref.'body/training?'.rand(1000,9999);
            }elseif($_POST['SubType']==3){
                echo $basehref.'body/standard-training-program';
            }else{
                echo $basehref.'body/training?'.rand(1000,9999);
                // header('location: /body/training');
            }
        }
        exit;
    }elseif($_POST['Action'] == 5){
        $type = $_POST['Type'];
        $subtype = $_POST['SubType'];
        $order  = $_POST["sort_ids"];
        $mediaid  = $_POST["mediaid"];
        //  var_dump($order);
          $order_ids = '';
        if(!empty($type) && !empty($subtype)){
          for($i=1; $i < count($order);$i++) {
            $db->where('ID',$order[$i]);
            $db->where('Type',$type);
            $db->where('SubType',$subtype);
            $db->where('body_media_id',$mediaid);
            $db->update('body_media',['Position'=>$i]);
            $order_ids .= $order[$i].',';
          }
          echo $order_ids;
        }else{
            echo 0;
        }
    }elseif($_POST['Action'] == 6){
          $type = $_POST['Type'];
          $subtype = $_POST['SubType'];
          $search_value  = $_POST["Search"];
        if(!empty($search_value) && !empty($type) && !empty($subtype)){
            $db->where('Type',$type);
            $db->where('SubType',$subtype);
            $db->where('body_media_id',0);
            // $db->where('Title','%'.$search_value.'%','like');
            $where = '(Title LIKE "%'.$search_value.'%" or Detail LIKE "%'.$search_value.'%")';
            $db->where($where);
            $search_data =  $db->get('body_media');
            if($search_data){?>

            <div class="bodysuit-slider-block">
                <?php     foreach($search_data as $t_media){
                        $opacity = 'unset';
                        $slide_item_display = 1;
                        if($_SESSION['Admin']==1){
                            if($t_media['Status']==0){
                                $opacity ="0.5";
                            }
                        }elseif($_SESSION['Admin']!=1 && $t_media['Status']==0){
                            $slide_item_display = 0;
                        }
                        $type= explode('/',$t_media['MediaType']);
                        $media_name= ($t_media['Media']) ? explode('/',$t_media['Media']): [];
                        $style = '';
                        $poster = '';
                        if(isset($type[0]) &&  $type[0]=='image'){
                                 $poster = $t_media['Media'];
                        }elseif(isset($type[0]) &&  $type[0]=='video'){
                                 $poster = '';
                        }else{
                           $style= 'style="background:#bce5ef"';
                        }
                    if($slide_item_display==1){?>
                   <div class="slide-item">
                        <div class="slide-inner" <?= ($opacity=="0.5") ? 'style="cursor:not-allowed;opacity:0.5;"' : ''?>>
                            <div class="video-wrapper" id="video-container<?= $t_media['ID']?>">
                                <?php if(empty($style)){
                                        $db->where('body_media_id',$t_media['ID']);
                                        $subvideos = $db->get('body_media');
                                        if(count($subvideos)>0){
                                    ?>
                                    <a href="/training-videos/<?= $t_media['ID']?>">
                                    <?php } ?>
                                        <video poster="<?= $poster ?>" <?= $style?> >
                                        <source type="video/mp4" src="<?= $t_media['Media'] ?>" >
                                        </video>
                                    <?php if(count($subvideos)>0){ ?></a><?php } ?>
                                    <?php if(count($subvideos)==0){?>
                                    <script type="text/javascript">
                                        setInterval(function(){
                                           let total_duration = $('#video-container'+<?= $t_media['ID']?>).find('video').get(0).duration;
                                            $('#total-duration'+<?= $t_media['ID']?>).html(format(total_duration));
                                            },100);
                                            function format(s) {
                                                var m = Math.floor(s / 60);
                                                m = (m >= 10) ? m : "0" + m;
                                                s = Math.floor(s % 60);
                                                s = (s >= 10) ? s : "0" + s;
                                                return m + ":" + s;
                                            }
                                    </script>
                                <?php } }else{?>
                                    <a href="<?= $t_media['Media'] ?>" target="_blank" class="btn">
                                    <video poster="<?= $poster ?>" <?= $style?>>
                                    </video>
                                    <p class="btn" title="<?= $t_media['MediaType']?> <?= trim(end($media_name)) ?>" style="z-index:999;">Download <i class="fa fa-download"></i></p>
                                     </a>
                                <?php } ?>
                                <?php if(isset($type[0]) && $type[0]=='video'){if(count($subvideos)>0){?>
                                    <a class="video_link" href="/training-videos/<?= $t_media['ID']?>">
                                    <?php }else{ ?>
                                            <a class="md-trigger video_link" data-modal="modal-video" onclick="crudModalVideo('body','<?= $t_media['ID'] ?>')">
                                    <?php } ?>
                                        <img src="/assets/images/video-play.png" alt="play-icon">
                                    </a>
                                <?php } if( $t_media['Detail']){?>
                                    <div class="info-block"><a href="javascript:void(0)" onclick="BodyInfoModal('<?= $t_media['ID'] ?>')"><i class="fa fa-info"></i></a>
                                    </div>
                                <?php } ?>
                                <span class="duration" id="total-duration<?= $t_media['ID']?>"><?= $t_media['TotalTime'] ?></span>
                            </div>
                            <div class="slide-content" >
                                <div style="min-height:120px;max-height:120px;overflow:hidden;">
                                <p><?= ($t_media['Title']) ? substr($t_media['Title'],0,100) : ''?></p>
                                 <p><?= ($t_media['Detail']) ? substr($t_media['Detail'],0,100) : ''?> </p>
                                <?= ($t_media['SubTitle']) ? '<span>'.$t_media['SubTitle'].'</span>' : ''?>
                                </div>
                                <?php if($_SESSION['Admin']==1){?>
                                    <div class="action-btn-block">
                                        <a class="btn  md-trigger" data-modal="modal-media" onclick="crudModalBodyMedia(2,'<?= $t_media['ID'] ?>',2,1)" >Ret</a>
                                        <a class="btn  md-trigger" data-modal="modal-media" onclick="crudModalBodyMedia(3,'<?= $t_media['ID'] ?>',2,1)">Slet</a>
                                </div>
                                <?php } ?>
                            </div>
                             </div>
                            </div>
                <?php } }?>
            </div>
            <?php  }else{
                echo 0;
            }
        }else{
            echo 'error';
        }
      }elseif($_POST['Action'] == 7){
        // Nutrition page's search data
            $type = $_POST['Type'];
            $subtype = $_POST['SubType'];
            $search_value  = $_POST["Search"];
        if(!empty($search_value) && !empty($type) && !empty($subtype)){
            $db->where('Type',$type);
            $db->where('SubType',$subtype);
            $db->where('body_media_id',0);
            // $db->where('Title','%'.$search_value.'%','like');
            $where = '(Title LIKE "%'.$search_value.'%" or Detail LIKE "%'.$search_value.'%")';
            $db->where($where);
            $search_data =  $db->get('body_media');
            if($search_data){?>
                <div class="bodysuit-slider-block">
                <?php foreach($search_data as $u_media){
                            $opacity = 'unset';
                            $slide_item_display = 1;
                            if($_SESSION['Admin']==1){
                                if($u_media['Status']==0){
                                    $opacity ="0.5";
                                }
                            }elseif($_SESSION['Admin']!=1 && $u_media['Status']==0){
                                $slide_item_display = 0;
                            }
                            $type= explode('/',$u_media['MediaType']);
                            $media_name= ($u_media['Media']) ? explode('/',$u_media['Media']): [];
                            $style = '';
                            $poster = '';
                            if(isset($type[0]) &&  $type[0]=='image'){
                                    $poster = $u_media['Media'];
                            }elseif(isset($type[0]) &&  $type[0]=='video'){
                                $poster = '';
                            }else{
                            $style= 'style="background:#bce5ef"';
                            }
                        if($slide_item_display==1){
                        ?>
                        <div class="slide-item">
                            <div class="slide-inner" <?= ($opacity=="0.5") ? 'style="cursor:not-allowed;opacity:0.5"' : ''?>>
                                <div class="video-wrapper" style="opacity:<?= $opacity?>;">
                                <?php if(empty($style)){
                                        $db->where('body_media_id',$u_media['ID']);
                                            $subvideos = $db->get('body_media');
                                        if(count($subvideos)>0 || (isset($type[0]) && $type[0]=='image')){
                                        ?>
                                        <a href="/training-videos/<?= $u_media['ID']?>">
                                        <?php } ?>
                                            <video poster="<?= $poster ?>" <?= $style?> >
                                            <source type="video/mp4" src="<?= $u_media['Media'] ?>" >
                                            </video>
                                        <?php if(count($subvideos)>0 || (isset($type[0]) && $type[0]=='image')){ ?></a><?php } ?>
                                    <?php }else{?>
                                        <a href="<?= $u_media['Media'] ?>" target="_blank" <?= ($opacity=="0.5") ? 'style="cursor:not-allowed;"' : ''?> class="btn">
                                        <video poster="<?= $poster ?>" <?= $style?>>
                                        </video>
                                        <p class="btn" title="<?= $u_media['MediaType']?> <?= trim(end($media_name)) ?>" style="z-index:999;">Download <i class="fa fa-download"></i></p>
                                        </a>
                                    <?php } ?>
                                    <?php if(isset($type[0]) && $type[0]=='video'){?>
                                    <a class="md-trigger video_link" data-modal="modal-video" onclick="crudModalVideo('body','<?= $u_media['ID'] ?>')">
                                        <img src="/assets/images/video-play.png" alt="play-icon">
                                    </a>
                                    <?php } if($u_media['Detail']){ ?>
                                    <div class="info-block"><a href="javascript:void(0)" onclick="BodyInfoModal('<?= $u_media['ID']?>')"><i class="fa fa-info"></i></a></div>
                                    <?php } ?>
                                    <?php if(!empty($u_media['TotalTime']) && $u_media['TotalTime'] !='00.00'){?>
                                        <span class="duration" id="total-duration<?= $u_media['ID']?>"><?= $u_media['TotalTime'] ?></span>
                                    <?php } ?>
                                </div>
                                <div class="slide-content">
                                    <div style="min-height:75px;max-height:75px;overflow:hidden;">
                                        <p><?= ($u_media['Title']) ? substr($u_media['Title'],0,100) : ''?> </p>
                                        <p><?= ($u_media['Detail']) ? substr($u_media['Detail'],0,100): ''?> </p>
                                        <?= ($u_media['SubTitle']) ? '<span>'.$u_media['SubTitle'].'</span>' : ''?>
                                    </div>
                                    <?php if($_SESSION['Admin']==1){?>
                                    <div class="action-btn-block">
                                        <a class="btn  md-trigger" data-modal="modal-media" onclick="crudModalBodyMedia(2,'<?= $u_media['ID'] ?>',1,2)" >Ret</a>
                                        <a class="btn  md-trigger" data-modal="modal-media" onclick="crudModalBodyMedia(3,'<?= $u_media['ID'] ?>',1,2)">Slet</a>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } }?>
                </div>
            <?php  }else{
                echo 0;
            }
        }else{
            echo 'error';
        }
    }

}

?>