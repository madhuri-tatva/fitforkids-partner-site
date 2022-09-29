<?php
include("../../includes/config.php");
$current_user_id = $_SESSION['UserId'];
$meal_type = !empty($_POST['meal_id']) ? $_POST['meal_id'] : 0;
$user_ids = !empty($_POST['user_id']) ? $_POST['user_id'] : 0;
$type = $_POST['type'];
$html = '';

$userSavedData = array();
$userSavedData1 = array();
if(!empty($type) && isset($_COOKIE['mealData']) && !empty($_COOKIE['mealData'])) {
    $userSavedData = objectToArray(json_decode($_COOKIE['mealData']));
}

if(!empty($type) && isset($_COOKIE['dinner']) && !empty($_COOKIE['dinner'])) {
    $userSavedData1['dinner'] = explode(',',$_COOKIE['dinner']);
}

if(!empty($type)){
    foreach($userSavedData as $key => $value){
        foreach($value as $k => $v){
            foreach($v as $kk => $vv){
                foreach($vv as $k1 => $v1){
                    $userSavedData1[$key][$k][$kk][$k1] = explode(',',$v1);
                }
            }
        }
    }
    // echo '<pre>';
    // print_r($userSavedData1);
    // exit;
}



function objectToArray ($object) {
    if(!is_object($object) && !is_array($object)){
    	return $object;
    }
    return array_map('objectToArray', (array) $object);
}
$firstStep = 'first-steps';
$iii = 0;

foreach($meal_type as $m => $meal) {

    if($meal == '1' || $meal == '2') {
        // echo '<div class="step-detail-div  '.(($meal == '1') ? '' : 'hide').'">
        //         <p>4 side ud af 5</p>
        //      </div>';
        foreach($user_ids as $u=> $user) {
            $userId = explode('_', $user)[0];
            for($k = 0; $k < 2; $k ++) {
            echo '<form action="" data-pre="'.$iii.'" data-meal="'.$meal.'" data-type="'.$k.'" class="step4-form '.(($m == '0' && $u == '0' && $k == 0) ? '' : 'hide').'" data-user="'.$userId.'" >
                    <div class="step-row title-row '.(($m == '0' && $u == '0' && $k == 0) ? '' : 'hide').'">
                       <!-- <div class="left-block">
                            <div class="left-col-wrapper">
                                <h3>Nu skal vi høre dig om, hvad du gerne vil spise til dine forskellige måltider?</h3>
                                <h3>Vi starter med morgenmaden.</h3>
                            </div>
                        </div>
                        <div class="right-block">
                            <div class="img-video-block">
                                <a class="img-video-content md-trigger" data-modal="modal-video" onclick="crudModalVideo(body)">
                                    <img src="/assets/images/profile-2.jpg" alt="">
                                </a>
                            </div>
                        </div> -->
                    </div>
                    <div class="checkbox-outer-wrap">
                        <div class="selection-title-block">
                            <div class="left-block">';
                                $dynamic_name = ( $current_user_id == $userId) ? 'du'  : explode('_', $user)[1];
                                $dynamic_diet = ($meal == 1) ? 'morgenmad' : 'frokost' ;
                                $dynamic_type = ($k == 0) ? 'spise' : 'drikke';
                                $dynamic_time = ($meal == '1') ? ''  :'Frokost: ';
                                echo '<p>' .$dynamic_time.' Hvad vil '.$dynamic_name.' gerne '.$dynamic_type.' til '.$dynamic_diet.' på en hverdag?</p>
                            </div>
                            <div class="right-block">
                                <p>(Du må gerne angive flere felter)</p>
                            </div>
                        </div>';
                        if($k == 0) {
                            echo '<div class="image-checkbox-block">
                                    <div class="inner-wrapper-block '.$firstStep.'">';
                        } else {
                            echo '<div class="image-checkbox-block eight-images">
                                    <div class="inner-wrapper-block">';
                        }
                            if($u > 0) {
                                echo '<div class="btn-wrap skip-btn">
                                        <button class="custom-btn btn-cta skip" type="button">Ved ikke/Spring over</button>
                                    </div>';
                                }

                                $allChecked = '';
                                if(!empty($type) && !empty($userSavedData1)  && isset($userSavedData1[$userId][$meal][$k]) && ($userSavedData1[$userId][$meal][$k]['custom'][0] == 0 && $userSavedData1[$userId][$meal][$k]['custom'][0] != '')){
                                    $allChecked = 'checked="checked"';

                                }else{
                                    $allChecked = '';
                                }

                            echo '<div class="center-block">
                                <span class="required-field step4Err hide">Du skal vælge mindst ét felt</span>
                                    <span class="img-block">
                                        <span class="image-checkbox">
                                            <input type="checkbox" name="'.$userId.'['.$meal.']['.$k.'][]" value="all_diet" class="diet-checkbox" '.$allChecked.'>
                                            <span class="img-block"><img src="/assets/images/center-img.jpg" alt=""></span>
                                        </span>
                                    </span>
                                </div>';

                            $db->where('type', $meal);
                            $db->where('is_drink', $k);
                            $dietData = $db->get('diet_data');

                            foreach($dietData as $d => $diet) {
                                $checked = $customChecked = '';
                                if(isset($userSavedData1) && !empty($userSavedData1) && isset($userSavedData1[$userId][$meal][$k])){
                                    if(!empty($userSavedData1[$userId][$meal][$k]['value']) && in_array($diet['id'], $userSavedData1[$userId][$meal][$k]['value'])){
                                        $checked = 'checked="checked"';
                                    }else{
                                        $checked = '';
                                    }

                                    //custom
                                    if(!empty($userSavedData1[$userId][$meal][$k]['custom'][0])){
                                        $customChecked = 'checked="checked"';
                                    }else{
                                        $customChecked = '';
                                    }
                                }

                                echo  '<div class="option-block option-'.($d + 1).'">
                                            <span class="image-checkbox">
                                                <input type="checkbox" name="'.$userId.'['.$meal.']['.$k.'][]" value="'.$diet['id'].'" class="diet-checkbox" '.$checked.'>
                                                <span class="img-block"><img src="/assets/images/'.$diet['file'].'" alt=""></span>
                                            </span>
                                        </div>';
                            }
                            echo '<div class="option-block option-10">
                                <span class="image-checkbox">
                                    <input type="checkbox" name="'.$userId.'['.$meal.']['.$k.'][]" value="custom_diet" class="diet-checkbox custom-diet-checkbox" '.$customChecked.'>
                                    <span class="img-block"><img src="/assets/images/meal-10.jpg" alt=""></span>
                                </span>
                                <div class="form-group hide"><input type="text" class="form-control" name="custom_input" style="font-size: 17px;"> </div>
                                </div>
                                <div class="btn-wrap">
                                    <button class="custom-btn btn-cta step4-btn" id="ste4-btn">GEM</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>';
                $firstStep = '';
                $iii++;
            }
            //$iii++;
        }
        //$iii++;
    } else {
        // echo '<div class="step-detail-div hide" >
        //     <p>5 side ud af 5</p>
        // </div>;
        $db->where('type', $meal);
        $dietData = $db->get('diet_data');
        $dinnerChecked = $allDinnerChecked = '';

        if(!empty($type) && empty($userSavedData1['dinner'])){
            $allDinnerChecked = 'checked="checked"';
        }
        else{
            $allDinnerChecked = '';
        }

        echo '<form class="step5-form '.((sizeof($meal_type) > 1) ? 'hide' : '').'" action="">
            <div class="step-row full-width">
                <div class="fullwidth-block">
                    <h3>Hvad vil du og din familie gerne spise til Aftensmad på en hverdag:</h3>
                    <div class="dinner-selection-block">
                        <div class="selection-wrapper">
                            <div class="selection-list">
                                <span class="selection-item center-item">
                                    <span class="secondary-checkbox">
                                        <input type="checkbox" name="dinner[]" value="0" class="dinner-checkbox" '.$allDinnerChecked.'>
                                        <span class="text"><img src="/assets/images/center-img.jpg" alt=""></span>
                                    </span>
                                </span>';
                                foreach($dietData as $d => $diet) {
                                    if(!empty($type) && !empty($userSavedData1['dinner'])){
                                        if(in_array($diet['id'], $userSavedData1['dinner'])){
                                            $dinnerChecked = 'checked="checked"';
                                        }
                                        else{
                                            $dinnerChecked = '';
                                        }
                                    }
                                    echo  '<span class="selection-item item-'.($d + 1).'">
                                            <span class="secondary-checkbox">
                                                <input type="checkbox" name="dinner[]" value="'.$diet['id'].'" class="dinner-checkbox" '.$dinnerChecked.'/>
                                                <span class="text">'.$diet['name'].'</span>
                                            </span>
                                        </span>';
                                }
                            echo '</div>
                        </div>
                        <span class="required-field hide" id="dinnerErr">Du skal vælge mindst ét felt</span>
                        <div class="btn-wrap">
                            <button class="custom-btn btn-cta step5-btn" id="ste5-btn">GEM</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>';
    }

}
exit;
?>