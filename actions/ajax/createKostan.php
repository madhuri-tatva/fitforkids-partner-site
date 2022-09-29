<?php
include("../../includes/config.php");
require_once $_SERVER["DOCUMENT_ROOT"].'/plugins/DOMPDF/vendor/autoload.php';
define("DOMPDF_ENABLE_REMOTE", true);
/* reference the Dompdf namespace */
use Dompdf\Dompdf;

if(isset($_POST)){
    $userArr = $_POST['selected_person'];
    $hwArr = isset($_POST['hwArr']) ? $_POST['hwArr'] : [];
    $mealData = (isset($_POST['mealData'])) ? $_POST['mealData'] : [];
    $meal_type = $_POST['meal_type'];
    $chart_data = (isset($_POST['chart_data'])) ? $_POST['chart_data'] : object;
    $insertArr = array();
    foreach($userArr as $index => $user){
        $userId = explode('_', $user)[0];
        $BMR = 0;
        if($_POST['display_bmr'] == 1) {
            $db->where('Id',$userId);
            $thisUser = $db->getOne('users',['Id','Age','Gender']);
            $from 	= new DateTime($thisUser['Age']);
            $to   	= new DateTime('today');
            $age 	= ($thisUser['Age'] == '0000-00-00') ? 0 : $from->diff($to)->y;
            $gender = ($thisUser['Gender'] == 'Male') ? 1 : 2 ;
            if($gender == 1) {
                $BMR = ((66.5 + (13.75 * $hwArr[$userId]["Weight"]) + (5.003 * $hwArr[$userId]["Height"]) - (6.775 * $age)) * 4.186) / 1000;
            } else if($gender == 2) {
                $BMR = ((655.1 + (9.563 * $hwArr[$userId]["Weight"]) + (1.850 * $hwArr[$userId]["Height"]) - (4.676 * $age)) * 4.186) / 1000;
            }
            $BMR = round($BMR, 2);

        }
        $kostanArr = array (
            "kostan_detail_id"=>(isset($insertArr[0])) ? $insertArr[0] : 0,
            "session_id" => $_SESSION['UserId'],
            "user_id" => $user,
            "is_parent" => ($userId == $_SESSION['UserId']) ? 1 : 0,
            "meal_type" => $meal_type,
            "display_bmr" => $_POST['display_bmr'],
            "height" => ($_POST['display_bmr'] == 1) ? $hwArr[$userId]["Height"] : 0,
            "weight" => ($_POST['display_bmr'] == 1) ? $hwArr[$userId]["Weight"] : 0,
            "BMR" => $BMR,
            "chart_data" => $chart_data,
            "breakfast_spice"=> (strpos($meal_type, '1') !== false) ? $mealData[$userId][1][0]['value'] : '',
            "breakfast_spice_custom"=> (strpos($meal_type, '1') !== false) ? $mealData[$userId][1][0]['custom'] : '',
            "breakfast_drink"=> (strpos($meal_type, '1') !== false) ? $mealData[$userId][1][1]['value'] : '',
            "breakfast_drink_custom"=> (strpos($meal_type, '1') !== false) ? $mealData[$userId][1][1]['custom'] : '',
            "lunch_spice"=> (strpos($meal_type, '2') !== false) ? $mealData[$userId][2][0]['value'] : '',
            "lunch_spice_custom"=> (strpos($meal_type, '2') !== false) ? $mealData[$userId][2][0]['custom'] : '',
            "lunch_drink"=> (strpos($meal_type, '2') !== false) ? $mealData[$userId][2][1]['value'] : '',
            "lunch_drink_custom"=> (strpos($meal_type, '2') !== false) ? $mealData[$userId][2][1]['custom'] : '',
            "dinner"=> $_POST['dinner'],
            "fridge"=> $_POST['fridge'],
            "fridge_custom" => $_POST['fridge_custom'],
            "soda" => $_POST['soda'],
            "soda_custom" => $_POST['soda_custom'],
            "created_at" => $db->now(),
            "updated_at"=> $db->now()
        );
        $db->insert('kostan_detail', $kostanArr);
        array_push($insertArr, $db->getInsertId());
    }

    if(sizeof($insertArr) > 0) {
        $kostanIds = join("','", $insertArr);
        $kostanDetail = $db->where('kd.id', $insertArr,'IN')->join('users','kd.user_id = pg_users.id')->get('kostan_detail as kd', null, ['kd.id','user_id','is_parent','meal_type','display_bmr','height','weight','BMR','chart_data','breakfast_spice','breakfast_spice_custom','breakfast_drink','breakfast_drink_custom','lunch_spice','lunch_spice_custom','lunch_drink','lunch_drink_custom','dinner','fridge','fridge_custom','soda','soda_custom','Firstname','Lastname','Email','Age','ParentId_2','is_partner']);


        /* instantiate and use the dompdf class */
        $dompdf = new Dompdf();
        $name = ''; $meal_type = ''; $display_bmr = '';$type = '';$fridge = ''; $soda = '';

        $kost_userdetail = NULL;
        $db->where('Id',$_SESSION['UserId']);
        $k_userdetail = $db->getOne('users');
        if($k_userdetail){
            $kost_userdetail = '<p> Navn:&nbsp; '.$k_userdetail['Firstname'].' '.$k_userdetail['Lastname'].'</p><p>E-mail:&nbsp; '.$k_userdetail['Email'].'</p><p>Postnummer:&nbsp; '.$k_userdetail['PhoneNumber'].'</p>';
        }

        $HWBtable = '<table class="custom-table"><tbody><tr>
                    <td>Navn</td><td>Højde</td><td>Vægt</td><td>BMR</td>
                    </tr>';
        $breakfastTable = '<table class="custom-table"><tbody><tr>
                    <td>Navn</td><td>Morgenmad spise</td><td>Morgenmad drikke</td>
                    </tr>';
        $lunchTable = '<table class="custom-table"><tbody><tr>
                        <td>Navn</td><td>Frokost spise</td><td>Frokost drikke</td>
                        </tr>';
        foreach($kostanDetail as $index => $detail) {
            // $name .= $detail['Firstname']. ', ';
            $age_yr = '';
            if(empty($detail['is_partner']) && $detail['user_id'] != $_SESSION['UserId']){
                if($detail['Age']>0){
                    $from = new DateTime($detail['Age']);
                    $to   = new DateTime('today');
                    $age_yr = ' - '.$from->diff($to)->y . ' år';
                }else{
                    $age_yr = ' - år';
                }
            }
            $name .= $detail['Firstname'].$age_yr. ', ';
            if($index == 0){
                $type = $detail['meal_type'];
                if(strpos($type, '1') !== false) { $meal_type .= 'Morgenmad, '; }
                if(strpos($type, '2') !== false) { $meal_type .= 'Frokost, '; }
                if(strpos($type, '3') !== false) { $meal_type .= 'Aftensmad'; }

                $display_bmr = ($detail['display_bmr'] == 0) ? 'Nej' : 'Ja' ;
                $chartData = json_decode($detail['chart_data']);
                if(strpos($type, '3') !== false) {
                    $dinner =  getDietDetails($detail['dinner']);
                }
                $fridge = getDietDetails($detail['fridge']).', '.$detail['fridge_custom'];
                $fridge = trim($fridge,' ,');

                $soda = getDietDetails($detail['soda']).', '.$detail['soda_custom'];
                $soda = trim($soda,' ,');
            }
            $HWBtable .= '<tr>
                            <td>'.$detail['Firstname'].'</td><td>'.$detail['height'].'</td><td>'.$detail['weight'].'</td><td>'.$detail['BMR'].'</td>
                        </tr>';
            if(strpos($type, '1') !== false){
                $custom_breakfast_spice = ($detail['breakfast_spice_custom'] == '0') ? getDietDetails($detail['breakfast_spice_custom']) : $detail['breakfast_spice_custom'];
                $breakfast_spice = getDietDetails($detail['breakfast_spice']).', '.$custom_breakfast_spice;

                $custom_breakfast_drink = ($detail['breakfast_drink_custom'] == '0') ? getDietDetails($detail['breakfast_drink_custom']) : $detail['breakfast_drink_custom'];
                $breakfast_drink = getDietDetails($detail['breakfast_drink']).', '.$custom_breakfast_drink;
                $breakfastTable .= '<tr>
                                        <td>'.$detail['Firstname'].'</td><td>'.trim($breakfast_spice,' ,').'</td><td>'.trim($breakfast_drink,' ,').'</td>
                                    </tr>';
            }

            if(strpos($type, '2') !== false) {
                $custom_lunch_spice = ($detail['lunch_spice_custom'] == '0') ? getDietDetails($detail['lunch_spice_custom']) : $detail['lunch_spice_custom'];
                $lunch_spice = getDietDetails($detail['lunch_spice']).', '.$custom_lunch_spice;

                $custom_lunch_drink = ($detail['lunch_drink_custom'] == '0') ? getDietDetails($detail['lunch_drink_custom']) : $detail['lunch_drink_custom'];
                $lunch_drink = getDietDetails($detail['lunch_drink']).', '.$custom_lunch_drink;
                $lunchTable .= '<tr>
                                    <td>'.$detail['Firstname'].'</td><td>'.trim($lunch_spice,' ,').'</td><td>'.trim($lunch_drink,' ,').'</td>
                                </tr>';
            }

        }
        $HWBtable .= '</tbody></table>';
        $breakfastTable .= '</tbody></table>';
        $lunchTable .= '</tbody></table>';
        $i = 4;

        $html = '
        <html>
            <head>
                <style>
                    @page { margin: 5px 10px; }
                    #header { position: fixed; top: 15px;}
                    #footer { position: fixed; left: 0px; bottom: 15px; right: 0px; height: 20px;}
                    #footer .page:after { content: counter(page, upper-roman);}
                    .custom-table { display: inline-table;  color:#422100;}
				    .custom-table tbody tr td {border: 1px solid #4a0b201a; color:#422100;}
                    #content p {color:rgb(66,33,00);}
                </style>
            </head>
            <body>
                <div id="header">
                    <table width="100%">
                        <tbody>
                            <tr align="center">
                                <td style="font-size:20px;color:#93cdf0;">FitforKids</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="footer">
                </div>';

                    $html .= '<div id="content" style="top:70px; left:10px; position:relative;">
                        <p>'.$kost_userdetail.'</p>
                        <p>
                            Q1. Hvor mange personer skal jeres FitforKids Familie Kostplan dække?<br>
                            A : '.rtrim($name,' ,').'
                        </p>
                        <p>
                            Q2. Hvilke måltider skal jeres FitforKids FamilieKostplan dække?<br>
                            A : '.rtrim($meal_type,' ,').'
                        </p>
                        <p>
                            Q3. Ønsker du at din «Min Nye Menu» Kostplan skal vise dit Hvilestofskifte?<br>
                            A : '.$display_bmr.'
                        </p>';
                        if($display_bmr == 'Ja'){
                            $html .= '<p>
                                Q'.$i.'. Højde, Vægt og BMR <br>
                                A : '.$HWBtable.'
                            </p>';
                            $i++;
                        }
                        if (property_exists($chartData, 'A')) {
                            $html .= '<p> Q'.$i.'. Hvornår står du op<br>
                                      A : '.$chartData->A.'</p>';
                                      $i++;
                        }
                        if (property_exists($chartData, 'B')) {
                            $html .= '<p> Q'.$i.'. Hvornår spiser du morgenmad<br>
                            A : '.$chartData->B.'</p>';
                            $i++;
                        }
                        if (property_exists($chartData, 'C')) {
                            $html .= '<p> Q'.$i.'. Hvis du spiser noget mellem morgenmad og frokost, hvad tid spiser du det så<br>
                            A : '.$chartData->C.'</p>';
                            $i++;
                        }
                        if (property_exists($chartData, 'D')) {
                            $html .= '<p> Q'.$i.'. Hvad tid spiser du frokost<br>
                            A : '.$chartData->D.'</p>';
                            $i++;
                        }
                        if (property_exists($chartData, 'E')) {
                            $html .= '<p> Q'.$i.'. Hvis du spiser noget mellem frokost og aftensmad, hvad tid spiser du det så<br>
                            A : '.$chartData->E.'</p>';
                            $i++;
                        }
                        if (property_exists($chartData, 'F')) {
                            $html .= '<p> Q'.$i.'. Hvad tid spiser du aftensmad<br>
                            A : '.$chartData->F.'</p>';
                            $i++;
                        }
                        if (property_exists($chartData, 'G')) {
                            $html .= '<p> Q'.$i.'. Hvis du spiser noget efter aftensmad hvad tid spiser du det så<br>
                            A : '.$chartData->G.'</p>';
                            $i++;
                        }
                        if(strpos($type, '1') !== false){
                            $html .= '<p>
                                Q'.$i.'. Hvad vil du gerne spise og drikke til morgenmad på en hverdag?<br>
                                A : '.$breakfastTable.'
                            </p>';
                            $i++;
                        }
                        if(strpos($type, '2') !== false){
                            $html .= '<p>
                                Q'.$i.'. Hvad vil du gerne spise og drikke til frokost på en hverdag?<br>
                                A : '.$lunchTable.'
                            </p>';
                            $i++;
                        }
                        if(strpos($type, '3') !== false){
                            $html .= '<p>
                                Q'.$i.'. Hvad vil du og din familie gerne spise til Aftensmad på en hverdag ?<br>
                                A : '.$dinner.'
                            </p>';
                            $i++;
                        }
                        if($fridge) {
                            $html .= '<p>
                                Q'.$i.'. De fødevarer, der IKKE skal i DIT køleskab!<br>
                                A : '.$fridge.'
                            </p>';
                            $i++;
                        }
                        if($soda) {
                            $html .= '<p>
                                Q'.$i.'. Dine eller Familiens YNDLINGS ”Søde Sager”!<br>
                                A : '.$soda.'
                            </p>';
                            $i++;
                        }

                        $html .= '</div>';
            $html .= '</body>
                    </html>';
        // echo "<pre>";
        // print_r($html);
        // exit;

        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        // $dompdf->stream('FitForKids.pdf');

        $output = $dompdf->output();

        $fileName = 'FitForKids_'.time().'.pdf';
        $targetPath =  MAIN_PATH. '/uploads/diet/';
        if(!is_dir($targetPath)) {
            mkdir($targetPath, 0777,true);
        }
        file_put_contents($targetPath.$fileName, $output);

        $db->where('Id',$_SESSION['UserId']);
        $logginUserData = $db->getOne('users');

        $db->where('Id',$logginUserData['TeamId']);
        $teamName = $db->getOne('teams');

        $array = array(
            "firstname" =>  $_SESSION['Firstname'],
            "name" => '',
            "username" => $_SESSION['Firstname'].' '.$_SESSION['Lastname'] .' from hold '.$teamName['Name'],
            "email" => KOSTAN_EMAIL
        );
        $_SESSION['whatnow_access'] = 1;
        send_mail('da_DK',"Kustomone", $array, $targetPath. $fileName);

        exit;

    }
}
function getDietDetails($ids) {
    global $db;
    if($ids != '0') {
        $ids = join("','",explode(',', $ids));

        $diet_data = $db->rawQuery("SELECT GROUP_CONCAT(name SEPARATOR ', ') as names FROM pg_diet_data WHERE id IN ('$ids')");

        if(sizeof($diet_data) > 0) {
            return $diet_data[0]['names'];
        } else {
            return '';
        }

    } else {
        return 'Et mix of det Hele';
    }
}

?>