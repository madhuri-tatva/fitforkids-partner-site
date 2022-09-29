<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

function slugify($text){
  // replace non letter or digits by -
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, '-');

  // remove duplicate -
  $text = preg_replace('~-+~', '-', $text);

  // lowercase
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
}

return $text;
}

function array_to_csv_download($array, $filename = "export.csv", $delimiter=";") {

    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'";');
    header('Content-Encoding: UTF-8');
    header('Content-type: text/csv; charset=UTF-8');

    echo "\xEF\xBB\xBF";

    $f = fopen('php://output', 'w');

    foreach ($array as $line) {

        //$line = array_map("utf8_decode", $line);
        fputcsv($f, $line, $delimiter);

    }

    fclose($f);
    $stringCSV = ob_get_contents();
    //ob_end_clean();


    //$stringCSVfinal = mb_convert_encoding($stringCSV, 'Windows-1252', 'auto');

    return $stringCSV;

}

function generateCSV($array, $filename = "export.csv", $delimiter=";") {
    //header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'";');
    header('Content-Encoding: UTF-8');
    header('Content-type: text/csv; charset=UTF-8');

    echo "\xEF\xBB\xBF";

    $f = fopen('php://output', 'w');

    foreach ($array as $line) {

        //$line = array_map("utf8_decode", $line);
        fputcsv($f, $line, $delimiter);

    }

    fclose($f);
    $stringCSV = ob_get_contents();
    ob_end_clean();


    //$stringCSVfinal = mb_convert_encoding($stringCSV, 'Windows-1252', 'auto');

    return $stringCSV;


}



function generatePDF($userId){

    global $db;

    $db->where('UserId',$userId);
    $bookData = $db->getOne('book');


    $db->where('Id',$userId);
    $userData = $db->getOne('users');


    $mpdf = new mPDF(); //Create an instance of the class:

    ob_start(); // Buffer the following html with PHP so we can store it to a variable later

    ?>

    <style>

        .section {
            margin-bottom: 50px;
        }

        h1 {
            color: #6d1331;
            margin-bottom: 50px;
        }

        h2 {
            color: #6d1331;
            font-size: 18px;
        }

        h3 {
            color: #6d1331;
            font-size: 14px;
        }

    </style>

    <div id="wrapper">

        <div class="headersection">
            <h1 style="text-align: center;"><?php echo $userData['Firstname']; ?><br>Hemmelige bog:<br>Mine DrømmeMål<br>og Lykkevisioner</h1>
        </div>

        <div class="section">
            <h2>Mine Mind Mål</h2>
            <?php echo $bookData['FieldMind']; ?>
        </div>

        <div class="section">
            <h2>Mine Body Mål</h2>
            <?php echo $bookData['FieldBody'] . "<br><br>"; ?>
            <?php if(isset($bookData['WeightTo'])){
                echo "Jeg vil veje " . $bookData['WeightTo'] . " kg<br>";
            } ?>
            <?php if(isset($bookData['FatTo'])){
                echo "Min fedtprocent skal ligge på " . $bookData['FatTo'] . "%<br>";
            } ?>
            <?php if(isset($bookData['BMITo'])){
                echo "Jeg skal have en BMI på max " . $bookData['BMITo'];
            } ?>
        </div>

        <div class="section">
            <h2>Mine mål for kærlighed</h2>
            <?php echo $bookData['FieldLove']; ?>
        </div>

        <div class="section">
            <h2>Mine mål for arbejde</h2>
            <?php echo $bookData['FieldWork']; ?>
        </div>

        <div class="section">
            <h2>Mine kompetence mål</h2>
            <?php echo $bookData['FieldCompetences']; ?>
        </div>

        <div class="section">
            <h2>Mine mål for venskab</h2>
            <?php echo $bookData['FieldFriendship']; ?>
        </div>

        <div class="section">
            <h2>Mine mål for hobby</h2>
            <?php echo $bookData['FieldHobby']; ?>
        </div>

        <div class="section">
            <h2>Mine materielle mål</h2>
            <?php echo $bookData['FieldMaterial']; ?>
        </div>

        <div class="section">
            <h2>Mine familie mål</h2>
            <?php echo $bookData['FieldFamily']; ?>
        </div>

        <div class="section">
            <h2>Mine erotiske mål</h2>
            <?php echo $bookData['FieldErotic']; ?>
        </div>

        <div class="section">
            <h2>Mine mål for opleveler</h2>
            <?php echo $bookData['FieldExperiences']; ?>
        </div>

        <div class="section">
            <h2>Mine spirituelle mål</h2>
            <?php echo $bookData['FieldSpiritual']; ?>
        </div>

        <div class="section">
            <h2>Min ActionPlan</h2>

            <h3>Min trofaste støtte er:</h3>
            <?php echo $bookData['MyLoyalSupport']; ?>

            <h3>Mit Vinder Mindset er:</h3>
            <?php echo $bookData['MyWinnerMindset']; ?>

            <h3>Mit Taber Mindset er:</h3>
            <?php echo $bookData['MyLoserMindset']; ?>

            <h3>Undervejs på min Eventyrrejse kan jeg tanke op her:</h3>
            <?php echo $bookData['MyTemple']; ?>

            <h3>Det landskab (mine omgivelser; venner, familie, job mv.) jeg skal igennem er:</h3>
            <?php echo $bookData['MyTerrain']; ?>

            <h3>Sumpen jeg kan sidde fast i er:</h3>
            <?php echo $bookData['MyStop']; ?>

            <h3>De bjerge (udfordringer) jeg kan møde undervejs er:</h3>
            <?php echo $bookData['MyObstacles']; ?>
        </div>

        <div class="section">
            <h2>Mine SuccessMål</h2>
            <h3>Min VÆK FRA Mål</h3>
            <?php echo $bookData['FieldCurrent']; ?>

            <h3>Min HEN TIL Mål</h3>
            <?php echo $bookData['FieldProspect']; ?>

        </div>




    </div>



    <?php


    $html = ob_get_contents(); // Now collect the output buffer into a variable


    ob_end_clean(); // clean buffer

    $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
    $mpdf->WriteHTML($html); // send the captured HTML from the output buffer to the mPDF class for processing


    //$mpdf->Output();
    //return $mpdf->Output('book.pdf', 'I');
    return $mpdf->Output($userData['Firstname'].'-hemmelige-bog.pdf', 'D');

}

// Define the root folder and base URL for the application
function baseURL(){
    return sprintf(
        "%s://%s%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME'],
        dirname($_SERVER['REQUEST_URI'])
    );
}

function curPageURL() {
   $pageURL = 'https';
   $pageURL .= "://";
   $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
   return $pageURL;
}

function fileExists($path){
    return (@fopen($path,"r")==true);
}


function datetimedisplay($dateinput)
{
    $date = new DateTime($dateinput);
    $date = $date->format('d/m/Y');
    return $date;
}
function danishdateandtime($dateinput)
{
    $date = new DateTime($dateinput);
    $date = $date->format('d/m/Y H:i:s');
    return $date;
}
function datetimedisplayreverse($dateinput)
{
    $date = DateTime::createFromFormat('d/m/Y', $dateinput);
    $date = $date->format('Y-m-d');
    return $date;
}
function removetimefromdate($dateinput)
{
    $dt = new DateTime($dateinput);
    return $dt->format('Y-m-d');
}
function removetimefromdatedanish($dateinput)
{
    $dt = new DateTime($dateinput);
    return $dt->format('d-m-Y');
}
function getcityname($zipcode)
{
    global $db;
    $db->where("zip_code", $zipcode);
    $zipinfo = $db->getOne('zip_codes');
    $name    = $zipinfo['city'];
    return $name;
}


function doubleSalt($username)
{
    global $toHash;
    $password = str_split($toHash, (strlen($toHash) / 2) + 1);
                //var_dump($password);
    $hash     = hash('md5', $username . $password[0] . 'e68LW61lVz1qrwk2gfAFCxPyi5sn49m3Y3IRK5M6SN5d8a68RNTdu' . $password[1]);
    return $hash;
}


//PLANGY
function redirect($url){
    header('Location: /redirect.php?url='.$url);
    exit;
}

//PLANGY
function accesslevel($pagelevel, $usertype, $admin){

    // GUIDE
    // Page levels: 50 = User Admin, 30 = User Manager, 3 = User Employee
    // User types: 1 = User Admin, 2 = User Manager, 3 = User Employee

    if($admin != 1){
        if($usertype == 1 && $pagelevel > 50){
            header("Location: /dashboard");
            exit;
        }elseif($usertype == 2 && $pagelevel > 30){
            header('Location: /dashboard');
            exit;
        }elseif($usertype == 3 && $pagelevel > 10){
            header('Location: /dashboard');
            exit;
        }elseif($pagelevel > 50){
            header('Location: /dashboard');
            exit;
        }
    }

}

//PLANGY
function accesscustomerlevel($level, $customertype, $admin){

    // GUIDE
    // Levels: 1 = Premium, 0 = Freemium
    // Customer types: 1 = Premium, 0 = Freemium

    if($admin != 1){
        if($customertype == 0 && $level == 1){
            header("Location: /dashboard");
            exit;
        }
    }

}

//PLANGY
function logfront($page,$key,$ipaddress,$cookie,$cookiedie,$platform,$browser,$browserver){

    $date_month = sprintf("%02d", date('m'));
    $date_year  = date('Y');

    $logpath = "var/blackbox/".$date_year."/".$date_month."/".$page."-".$key.".json";

    $date       = date ("Y-m-d H:i:s");

    $log        = fopen("$logpath", "a+");

    $_SESSION['Visitor']['IP'] = $ipaddress;

    if(empty($page)){
        $page = 'index';
    }

    $array = array();

    $array[] = array(
        $ipaddress,
        $page,
        $date,
        $cookie,
        $cookiedie,
        $platform,
        $browser,
        $browserver
    );

    // Exclude
    $exclude = array(
        '87.61.67.198',
        //'78.111.164.42',
        '85.81.27.183'
    );

    $search = array_search($ipaddress, $exclude);

    if(empty($search)){
        fwrite($log,json_encode($array)."\n");
        fclose($log);
    }

}


//PLANGY
function getlogaction($actionid) {

    $actions = array(
        '1'   => 'Register Pro',
        '2'   => 'Login',
        '3'   => 'Enter Dashboard',
        '4'   => 'Enter Schedule',
        '5'   => 'Enter Messages',
        '6'   => 'Enter Administration',
        '7'   => 'Enter Statistics',
        '8'   => 'Enter Settings',
        '9'   => 'Enter Payment',
        '10'  => 'Enter Create Schedule',
        '11'  => 'New Schedule Created',
        '12'  => 'Create Single Shift',
        '13'  => 'Edit Single Shift',
        '14'  => 'Open Sidebar',
        '15'  => 'Sidebar Create Shift',
        '16'  => 'Sidebar Edit Shift',
        '17'  => 'Dashboard Create Single Shift',
        '18'  => 'Dashboard Edit Single Shift',
        '19'  => 'Starting new conversation',
        '20'  => 'Wrote Message',
        '21'  => 'Reports Download CSV',
        '22'  => 'Reports Download PDF',
        '23'  => 'Added New Department',
        '24'  => 'Edited Department',
        '25'  => 'Added New User',
        '26'  => 'Edited User',
        '27'  => 'Added New Absence',
        '28'  => 'Edited Absence',
        '29'  => 'Added New Title',
        '30'  => 'Edited Title',
        '31'  => 'Assign Title',
        '32'  => 'Edited My Profile',
        '33'  => 'Note Wroted',
        '33'  => 'Note Removed',
        '34'  => 'Register Freemium',
        '35'  => 'Login Failed',
        '36'  => 'Checked in',
        '37'  => 'Checked out',
        '38'  => 'Schedule Download PDF',
        '39'  => 'Enter Schedule User',
        '40'  => 'Created Customer',
        '41'  => 'Created Employees',
        '42'  => 'Completed Setup Schedule',
        '43'  => 'Created Schedule',
        '44'  => 'Password creation email sent to user',
        '45'  => 'Password creation email received'
    );

    $if_exist = array_key_exists($actionid,$actions);

    if($if_exist == TRUE){
        $action = $actions[$actionid];
    }else{
        $action = 'Action does not exist';
    }

    return $action;

}

//PLANGY
function logaction($action,$customerid,$userid){

    /*
    LOG INDEX

    1   = Register Pro
    2   = Login
    3   = Enter Dashboard
    4   = Enter Schedule
    5   = Enter Messages
    6   = Enter Administration
    7   = Enter Statistics
    8   = Enter Settings
    9   = Enter Payment
    10  = Enter Create Schedule
    11  = New Schedule Created
    12  = Create Single Shift
    13  = Edit Single Shift
    14  = Open Sidebar
    15  = Sidebar Create Shift
    16  = Sidebar Edit Shift
    17  = Dashboard Create Single Shift
    18  = Dashboard Edit Single Shift
    19  = Starting new conversation
    20  = Wrote Message
    21  = Reports Download CSV
    22  = Reports Download PDF
    23  = Added New Department
    24  = Edited Department
    25  = Added New User
    26  = Edited User
    27  = Added New Absence
    28  = Edited Absence
    29  = Added New Title
    30  = Edited Title
    31  = Assign Title
    32  = Edited My Profile
    33  = Note Wroted
    33  = Note Removed
    34  = Register Freemium
    35  = Login Failed
    36  = Checked in
    37  = Checked out
    38  = Schedule Download PDF
    39  = Enter Schedule User

    */

    global $db;

    if(empty($customerid)){
        $customerid = $_SESSION['CustomerId'];
    }

    if(empty($userid)){
        $userid = $_SESSION['UserId'];
    }

    $ipaddress = $_SERVER['REMOTE_ADDR'];
    if(isset($_SERVER['HTTP_CLIENT_IP'])){
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }elseif(isset($_SERVER['HTTP_X_FORWARDED'])){
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    }elseif(isset($_SERVER['HTTP_FORWARDED_FOR'])){
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    }elseif(isset($_SERVER['HTTP_FORWARDED'])){
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    }elseif(isset($_SERVER['REMOTE_ADDR'])){
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    }else{
        $ipaddress = 'UNKNOWN';
    }

    if(isset($_COOKIE['visitor'])){
        $cookie = unserialize($_COOKIE['visitor']);
        if(isset($cookie['Cookie'])){
            $cookie = $cookie['Cookie'];
        }else{
            $cookie = 'None';
        }
    }




}




/* LOGIN RELATED */

function loginaction($username, $password)
{
    global $db;
    global $basehref;
    $passwordhashed = doubleSalt($password);
                // echo $passwordhashed; exit;
    $db->where("Email", $username);
    $db->where("PasswordHash", $passwordhashed);
    $userinfo = $db->getOne('users');
    if(!empty($userinfo)){
        $_SESSION['userid']         = $userinfo['Id'];
        $_SESSION['Firstname']      = $userinfo['Firstname'];
        $_SESSION['Lastname']       = $userinfo['Lastname'];
        $_SESSION['LanguageID']     = $userinfo['LanguageID'];
        $_SESSION['PhoneNumber']    = $userinfo['PhoneNumber'];
        $_SESSION['Type']           = $userinfo['Type'];
        $_SESSION['FamilyId']       = $userinfo['FamilyId'];
        $_SESSION['Admin']          = $userinfo['Admin'];
        $_SESSION['Title']          = $userinfo['Title'];
        $_SESSION['Address']        = $userinfo['Address'];


        $data = Array (
            'LastSigninTimestamp' => $db->now(),
            'LastSigninIP'  => $_SERVER['REMOTE_ADDR']

        );
        $db->where ('Id', $userinfo['Id']);
        if ($db->update ('users', $data)){
        }


                //logins update
                //loginattempt($username, $password, '0');


        header("Location:".$basehref."/dashboard");
    }else{
        $_SESSION['errormsg'] = 'error1';
                //loginattempt($username, $password, '1');
        header("Location:".$basehref."/login");
    }

    exit;
}

function send_sms($mobile_number, $subject, $message)
{
    $nexmo_sms = new NexmoMessage(NEXMO_KEY, NEXMO_SECRET);
    $info      = $nexmo_sms->sendText('+45' . $mobile_number, $subject, $message);
}


function messagestringreplace($message,$firstname,$lastname,$password,$phonenumber,$address,$zipcode,$city,$email,$company,$vat_number){

    $message                 = str_replace('%firstname%', $firstname, $message);
    $message                 = str_replace('%lastname%', $lastname, $message);
    $message                 = str_replace('%password%', $password, $message);
    $message                 = str_replace('%phonenumber%', $phonenumber, $message);
    $message                 = str_replace('%address%', $address, $message);
    $message                 = str_replace('%zipcode%', $zipcode, $message);
    $message                 = str_replace('%city%', $city, $message);
    $message                 = str_replace('%email%', $email, $message);
    $message                 = str_replace('%company%', $company, $message);
    $message                 = str_replace('%vat_number%', $vat_number, $message);

    return $message;
}


function emailcontentbookings($mailid, $id, $type){


}
function emailcontentreperations($mailid, $id, $type){


}
function emailcontentorders($mailid, $id, $type){


}
function emailcontentcustomer($mailid, $id, $type){


}




function emailreplace($bookingid, $mailid)
{
    global $db;
                //GET ALL BOoking DATA :D

    $db->where("id", $bookingid);
    $booking = $db->getOne('bookings');
    if ($booking == null)
    {
        echo "<script> alert('hacking attempt!'); </script>";
        exit;
    }
    $id             = $booking['id'];
    $name           = $booking['name'];
    $datofra        = removetimefromdatedanish($booking['datefrom']);
    $datecreated    = $booking['datecreated'];
    $datotil        = removetimefromdatedanish($booking['dateto']);
    $model            = $booking['car'];
    $brand            = $booking['class'];

    $price         = $booking['price'];

    $email          = $booking['email'];
    $number         = $booking['number'];
    $street         = $booking['street'];
    $zipcode        = $booking['zipcode'];
    $comments       = $booking['comments'];
    $status         = $booking['status'];
    $timepickup     = $booking['timepickup'];
    $timepickup =  substr($timepickup,0,2).':'.substr($timepickup,2,2);
    $town = getcityname($zipcode);
                // SET MAIL VARIABLE!
    $db->where("service", $session);
    $db->where("messageid", $mailid);
    $mailz                = $db->getOne('message_templates');
    $mail                 = $mailz['message'];

    $price                = $priceforemail;
//                $locationstart = showbookinglocationfromid($bookingid, '1');
//                $locationend = showbookinglocationfromid($bookingid, '0');


    $host = $_SERVER['HTTP_HOST'];

    $link                 = "https://$host/backend/betalingsvindue.php?id=" . $bookingid . "&a=" . $price;
    $link2                = "";
                //  echo "<script> alert('Mail før: $mail!'); </script>";
    $mail                 = str_replace('%navn%', $name, $mail);
    $mail                 = str_replace('%bookingid%', $bookingid, $mail);
    $mail                 = str_replace('%email%', $email, $mail);
    $mail                 = str_replace('%telefon%', $number, $mail);
    $mail                 = str_replace('%email%', $email, $mail);

    $mail                 = str_replace('%datofra%', $datofra, $mail);
    $mail                 = str_replace('%datotil%', $datotil, $mail);
    $mail                 = str_replace('%lokation%', $locationstart, $mail);
    $mail                 = str_replace('%zip%', $zipcode, $mail);
    $mail                 = str_replace('%town%', $town, $mail);
    $mail                 = str_replace('%adress%', $street, $mail);
    $mail                 = str_replace('%biltype%', $cartype, $mail);
                $mail                 = str_replace('%afhentningstid%', $timepickup, $mail); //!!!


                $mail                 = str_replace('%kommentar%', $comments, $mail);

                $mail                 = str_replace('%pris%', $price, $mail); // Mangler...
                $mail                 = str_replace('%betalingslink%', $link, $mail); // Mangler...
                $mail                 = str_replace('%ankomstlink%', $link2, $mail); // Mangler...
                //  echo "<script> alert('Mail efter: $mail!'); </script>";
                //

                return $mail;
            }



            function copyrightyear($startyear){
               if(intval($startyear) == 'auto'){ $startyear = date('Y'); }
               if(intval($startyear) == date('Y')){ echo intval($startyear); }
               if(intval($startyear) < date('Y')){ echo intval($startyear) . ' - ' . date('Y'); }
               if(intval($startyear) > date('Y')){ echo date('Y'); }

           }




           function loginattempt($username, $password, $status){
    // status 0 success, status 1 fail.
            $db = MysqliDb::getInstance();
            $data = Array (
                "Username" => $username,
                   //"firstName" => "John",
                "Password" => $password,
                "IP" => $_SERVER['REMOTE_ADDR'],
           //        "IP2" => $_SERVER["HTTP_X_FORWARDED_FOR"],
                "Status" => $status
            );
    //$id = $db->insert ('loginhistory', $data);
    //if($id){ }

        }


        function reperationdatefromid($id){

            $db = MysqliDb::getInstance();
            $db->where("Id", $id);
            $data = $db->getOne('reparationtype');
//    $Title    = $zipinfo['Title'];
            return $data;

        }


        function get_unique_ordernumber_generator()
        {
            $n = date("Ymdhis") . mt_rand(1000000, 9999999) . mt_rand(1000000, 9999999) . mt_rand(1000000, 9999999) . mt_rand(1000000, 9999999);
            $n = substr($n, 2, 20);
            return ($n);
        }



        /* Price related */

        function getreperationtypeprice($id,$device)
        {
            global $db;
                $db->where("Id", $id); //ReperationTypeID   is ID     The connection to reparationtype is from ReperationID :-),.. name mixup...
                $db->where("ModelID", $device);
                $dbinfo = $db->getOne('deviceoperations');
       //         d($dbinfo);
                $price    = $dbinfo['Price'];
                return $price;
            }


            function getmodelfromreparationid($id)
            {
                global $db;
                $db->where("Id", $id);
                $dbinfo = $db->getOne('reparations');
                $model    = $dbinfo['models'];
                return $model;
            }


            function getreparationdatafromorderid($orderid)
            {
                global $db;
                $db->where("orderid", $orderid);
                $dbinfo = $db->getOne('reparations');
             //   $model    = $dbinfo['models'];
                return $dbinfo;
            }


            function getpricefromreparationid($reparationid){

        // status 0 success, status 1 fail.
                $db = MysqliDb::getInstance();

                $db->where("reparationid", $reparationid);
                $allreparations = $db->get('reparations_chosen');
        $deviceid = getmodelfromreparationid($reparationid); //model id
        $totalprice = 0;

       // d($allreparations);
       // d($deviceid);

        foreach ($allreparations as $allreparations) {
            # code...
            $id = $allreparations['reparationstypeid'];
            $totalprice += getreperationtypeprice($id,$deviceid);
     //       d($totalprice);
        }

        return $totalprice;
    }


    function getpricefromitems($reperations, $deviceid){



        $totalprice = 0;
        foreach ($reperations as $reperations) {
            # code...
            $id = $reperations['reparationstypeid'];
            $totalprice += getreperationtypeprice($id,$deviceid);
        }

        return $totalprice;

    }



    /* end price related */



    /* admin display related */


//admin dashboard

    function admingetrepairtypenum(){

    }

    function admindashboardgraph(){

    }


    function getallreperationstitles($reparationid){

        // status 0 success, status 1 fail.
        $db = MysqliDb::getInstance();

        $db->where("reparationid", $reparationid);
        $allreparations = $db->get('reparations_chosen');
        $deviceid = getmodelfromreparationid($reparationid); //model id
        $totalprice = 0;
        $reperationtitles = "";
        foreach ($allreparations as $allreparations) {
            # code...




            $id = $allreparations['reparationstypeid'];

            $db->where("Id", $id);
            $dbinfo = $db->getOne('deviceoperations');

            $db->where("Id", $dbinfo['ReperationID']);
            $dbinfo = $db->getOne('reparationtype');
            $reperationtitles .= $dbinfo['Title'];
            $reperationtitles .= ", ";

        }

        return $reperationtitles;
    }


    function getmodelnamefromid($modelid){

        global $db;
        $db->where("Id", $modelid);
        $dbinfo = $db->getOne('devices');
        $modeltitle    = $dbinfo['Title'];
        return $modeltitle;

        return $modelid;
    }

    function statusidtext($status){
        if($status == 0){
            $status = "Pakkelabel endnu ikke betalt";
        }elseif($status == 1){
            $status = "Pakkelabel betalt";
        }elseif($status == 2){
            $status = "Pakke sendt";
        }elseif($status == 3){
            $status = "Pakke modtaget";

        }elseif($status == 4){
            $status = "Pakke til gennemtjek";

        }elseif($status == 5){
            $status = "Enhed repareret";

        }elseif($status == 6){
            $status = "Reparation betalt";

        }elseif($status == 7){
            $status = "Enhed sendes tilbage til kunden";

        }elseif($status == 8){
            $status = "Endhed modtaget af kunde";

        }else{
            $status = "Ugyldig!";
        }


            /*
            1. Pakkelabel betalt
            2. Pakke sendt
            3. Pakke modtaget
            4. Pakke til gennemtjek (eventuelle andre tilbud pg reps)
            5. Enhed repareret
            6. Reparation betalt (af kunden)
            7. Enhed sendes tilbage til kunden
            8. Endhed modtaget af kunde


             */
            return $status;
        }


        function getmailcontent($messageid, $type){
//type 0 email, type 1 sms
//messageid
            global $db;
            $db->where("type", $type);
            $db->where("messageid", $messageid);
            $dbinfo = $db->getOne('message_templates');
//                $modeltitle    = $dbinfo['Title'];
            return $dbinfo;

        }

        function reparationhistorychange($oldstatus, $newstatus, $repid){

            $db = MysqliDb::getInstance();
            $data = Array (
                "statusbefore" => $oldstatus,
                   //"adminid" => "$_SESSION['adminuserid']",
                "newstatus" => $newstatus,
                  // "IP" => $_SERVER['REMOTE_ADDR'],
           //        "IP2" => $_SERVER["HTTP_X_FORWARDED_FOR"],
                "repid" => $repid
            );
            $id = $db->insert ('reparationhistory', $data);
    //if($id){ }

        }



        function updatereparationstatus($repid, $newstatus){
            global $db;
            $data = Array (
                'status' => $newstatus
            );
            $db->where('Id', $repid);
            $db->update('reparations', $data);
        }

        function changestatuswithmailandsms($status, $repid){
            global $basehref;

     //getmailcontent('4','0');

            $customerinfo = customerinfo($repid);
    //$subject = 'Website Change Request';
            $email = $customerinfo['email'];
            $to = $email;
            $phonenumber = $customerinfo['phonenumber'];

            $headers = "From: " . strip_tags($email) . "\r\n";
            $headers .= "Reply-To: ". strip_tags($email) . "\r\n";
    //$headers .= "CC: susan@example.com\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";




            $oldstatus = $status;
            $status = intval($status);
            if($status < 8){
                $newstatus = intval($status) + 1;
            }else{
                $newstatus = $status;
            }

    //echo "old status $oldstatus  status   $status  and new status $newstatus";
            reparationhistorychange($oldstatus, $newstatus, $repid);
            updatereparationstatus($repid, $newstatus);


            $mailcontent = getmailcontent($newstatus, '0');
            $smscontent = getmailcontent($newstatus, '0');
    //  $smscontent = getmailcontent($status, '1');

            $mailsubject = $mailcontent['title'];
            $mailmessage = $mailcontent['message'];
            $phonesender = "PhoneCare";

            $smssubject = $smscontent['title'];
            $smsmessage = $smscontent['message'];

                // %paymentlink% Betalingslink  status 5
                //get userid  %userid%    and  %amount%
                ///betalingslink/amount/user

            $betalingslink = $basehref;
                // user is rep id :-)
            $user = $repid;
            $amount = getpricefromreparationid($repid);

            $betalingslink .= "betalingslink/$amount/$user";
            $mailmessage                 = str_replace('%betalingslink%', $betalingslink, $mailmessage);
            $smsmessage                 = str_replace('%betalingslink%', $betalingslink, $smsmessage);



            if($status == 0){
                $textstatus = "Pakkelabel endnu ikke betalt";
                //update status in db.
                //send sms
                //send email
            }elseif($status == 1){
                $textstatus = "Pakkelabel betalt";
                                //update status in db.
                //send sms
                //send email
            }elseif($status == 2){
                $textstatus = "Pakke sendt";
                                //update status in db.
                //send sms
                //send email
            }elseif($status == 3){
                $textstatus = "Pakke modtaget";
                                //update status in db.
                //send sms
                //send email

            }elseif($status == 4){
                $textstatus = "Pakke til gennemtjek";
                                //update status in db.
                //send sms
                //send email

            }elseif($status == 5){
                $textstatus = "Enhed repareret";
                                //update status in db.
                //send sms
                //send email

            }elseif($status == 6){
                $textstatus = "Reparation betalt";
                                //update status in db.
                //send sms
                //send email

            }elseif($status == 7){
                $textstatus = "Enhed sendes tilbage til kunden";
                                //update status in db.
                //send sms
                //send email

            }elseif($status == 8){
                $textstatus = "Endhed modtaget af kunde";
                                //update status in db.
                //send sms
                //send email

            }else{
                $textstatus = "Ugyldig!";
                                //update status in db.
                //send sms
                //send email
            }


    //send_sms($phonenumber, $phonesender, $smsmessage);
            mail($to, $mailsubject, $mailmessage, $headers);




    //TODO
    //REPLACE CONTENT WITH % % VARIABLES FUNCTION!!



            /*
            1. Pakkelabel betalt
            2. Pakke sendt
            3. Pakke modtaget
            4. Pakke til gennemtjek (eventuelle andre tilbud pg reps)
            5. Enhed repareret
            6. Reparation betalt (af kunden)
            7. Enhed sendes tilbage til kunden
            8. Endhed modtaget af kunde


             */

            return $status;
        }


        function admindashboardcontent(){

        // status 0 success, status 1 fail.
            $db = MysqliDb::getInstance();

     //   $db->where("reparationid", $reparationid);
            $allreparations = $db->get('reparations');

            foreach ($allreparations as $allreparations) {
                $id = $allreparations['Id'];
                $Price = $allreparations['Price'];
                $type = $allreparations['type'];
                $firstname = $allreparations['firstname'];
                $lastname = $allreparations['lastname'];
                $phonenumber = $allreparations['phonenumber'];
                $address = $allreparations['address'];
                $zipcode = $allreparations['zipcode'];
                $city = $allreparations['city'];
                $email = $allreparations['email'];
                $company = $allreparations['company'];
                $vat_number = $allreparations['vat_number'];
                $invoice_email = $allreparations['invoice_email'];
                $ean_number = $allreparations['ean_number'];
                $brand = $allreparations['brand'];
                $models = $allreparations['models'];
                $dato = $allreparations['CreateDate'];
                $status = $allreparations['status'];
                $status = statusidtext($status);
                $reperations = getallreperationstitles($id);
                if($type == 1){
                    $type = "Privat";
                }elseif($type == 2){
                    $type = "Erhverv";
                }elseif($type == 3){
                    $type = "Organisation";

                }else{
                    $type = "Ugyldig!";
                }

                $models = getmodelnamefromid($models);
                $Price = getpricefromreparationid($id);

                echo "<br>ID: $id <br> TYPE: $type  <br> Model: $models <br> Rep.: $reperations  <br> Pris: $Price <br> Dato: $dato <br> Status: $status <br> <br> <br> <a href='kundeinformation/$id'>Kunde informationer her </a> <br><br><br>";
            }

//        return $totalprice;
        }


        function getbrandnamefromid($customid){

            global $db;
            $db->where("Id", $customid);
            $dbinfo = $db->getOne('devicebrands');
            $brandtitle    = $dbinfo['Title'];
            return $brandtitle;
        }

// Customer info
        function customerinfo($reservationid)
        {
            global $db;

            $db->join("customers u", "p.userid=u.Id", "LEFT");
            $db->where("p.Id", $reservationid);
//$customerinfo = $db->get ("reparations p", null, "u.firstname, p.Price");
            $customerinfo = $db->getOne("reparations p");

         //       $customerinfo = $db->getOne('reparations');
         //       d($customerinfo);
            return $customerinfo;
        }

        function customerinfofromuserid($customerid)
        {
            global $db;

            $db->where("Id", $customerid);
            $customerinfo = $db->getOne("customers");

            return $customerinfo;
        }

        function customer_data_by_email($email){
            global $db;

            $db->where("Email", $email);
            $customer_info = $db->getOne("customers");

            return $customer_info;
        }

        function customer_data_by_id($id){
            global $db;

            $db->where("Id", $id);
            $customer_info = $db->getOne("customers");

            return $customer_info;
        }


        function store_id_by_customer_id($customerid){
            global $db;

            $db->where("CustomerId", $customerid);
            $store_id = $db->getOne("departments");

            return $store_id;
        }


        function array_flatten($array) {
            if (!is_array($array)) {
                return false;
            }
            $result = array();
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $result = array_merge($result, array_flatten($value));
                }else {
                    $result[$key] = $value;
                }
            }
            return $result;
        }

        function input_array_check($array){

            $i = 0;

            $array = array_flatten($array);

            foreach($array as $data){

                if(preg_match('(<|>|{|})', $data)) {
                    $i ++;
                }

            }

            return $i;

        }

        function register_array_check($array){

            $i = 0;

            $array = array_flatten($array);

            foreach($array as $key => $data){

                if($key != 'password'){
                    if(preg_match('(<|>|{|})', $data)) {
                        $i ++;
                    }
                }

            }

            return $i;

        }

        function input_check($string){

            $i = 0;

            if(preg_match('(<|>|{|})', $string) === 1) {
                $i ++;
            }

            return $i;

        }

        function generate_slug() {

            global $db;

            $slugs_all = $db->get('landingpages', null, 'Slug');

            $arr = $slugs_all;

            $loop = true;
            while($loop) {
                $randomize = rand(10000,99999);

                $loop = in_array($randomize, $arr);
                if (!$loop) {
                    $slug = $randomize;
                }
            }

            return $slug;

        }

        function generate_initials($userid) {

            global $db;

            $db->where('Id',$userid);
            $userdata = $db->getOne('users');

            $fullname = $userdata['Firstname'] . " " . $userdata['Lastname'];
            $fullname_nospace = str_replace(" ", "", $fullname);
            $fullname_nospace = str_replace("æ", "ae", $fullname_nospace);
            $fullname_nospace = str_replace("ø", "oe", $fullname_nospace);
            $fullname_nospace = str_replace("å", "aa", $fullname_nospace);
            $fullname_nospace = str_replace("Æ", "ae", $fullname_nospace);
            $fullname_nospace = str_replace("Ø", "oe", $fullname_nospace);
            $fullname_nospace = str_replace("Å", "aa", $fullname_nospace);

            $fullname = explode(" ",$fullname);
            $first_character = substr($fullname_nospace,0,1);


            $initials = '';
            foreach($fullname as $name){
                $initial = substr($name,0,1);
                $initials .= $initial;
            }

            if(strlen($initials) < 2){
                $initials = substr($userdata['Firstname'],0,3);
                $initials = str_replace(" ", "", $initials);
            }


            $db->where('CustomerId',$userdata['CustomerId']);
            $db->where('Initials',$initials);
            $intialcheck = $db->has('users');

            if($intialcheck == 1){

                $initials_all = $db->get('users', null, 'Initials');

                $arr = $initials_all;

                $loop = true;
                while($loop) {
                    $randomize = $first_character . substr(str_shuffle($fullname_nospace),0,2);
            #var_dump($randomize);
                    $loop = in_array($randomize, $arr);
                    if (!$loop) {
                        $initials = $randomize;
                    }
                }

            }

            $initials = substr($initials,0,4);
            $initials = strtoupper($initials);

            return $initials;

        }

        function department_data_by_id($departmentid){
            global $db;

            $db->where("Id", $departmentid);
            $department = $db->getOne("departments");

            return $department;
        }

        function title_data_by_id($titleid){
            global $db;

            $db->where("Id", $titleid);
            $title = $db->getOne("titles");

            return $title;
        }

        function shifttype_data_by_id($shifttypeid){
            global $db;

            $db->where("Id", $shifttypeid);
            $shifttype = $db->getOne("shifttypes");

            return $shifttype;
        }

        function order_data_by_id($invoiceid){
            global $db;

            $db->where("Id", $invoiceid);
            $order = $db->getOne("orders");

            return $order;
        }

        function getcustomeerorderid($customerid,$orderid){
            global $db;

            $db->where('CustomerId', $customerid);
            $orders = $db->get('orders');

            $customerorderid = '';
            $countid = 0;
            foreach($orders as $order){
                $countid++;

                if($order['Id'] == $orderid){
                    $padcountid = sprintf("%04d", $countid);
                    $customerorderid = $customerid . "-" . $padcountid;
                }
            }

            return $customerorderid;
        }

        function getproductdata($productid){
            global $db;

            if($productid == 1){
                $data = array(
                    "ProductName" => 'Store',
                    "ProductPriceExTax" => 289,
                    "ProductCurrency" => 'DKK'
                );
            }elseif($productid == 2){
                $data = array(
                    "ProductName" => 'User',
                    "ProductPriceExTax" => 19,
                    "ProductCurrency" => 'DKK'
                );
            }

            return $data;

        }

        function departmentcheck($customerid,$departmentid){
            global $db;

            $db->where("Id", $departmentid);
            $department = $db->getOne("departments");

            if($_SESSION['Admin'] == 1){

                $check = 1;

            }else{

                if($department['CustomerId'] == $customerid){
                    $check = 1;
                }else{
                    $check = 0;
                }

            }

            return $check;
        }


        function cmssettingscheck($cmsid){
            global $db;

            $db->where("CMSId", $cmsid);
            $cmssettings = $db->getOne("pages");

            return $cmssettings;
        }

        function usercheck($email){
            global $db;

            $email = strtolower($email);

            $db->where("Email", $email);
            $db->where("EmployeeGroup",3,'!=');
            $usercheck = $db->getOne("users");

            return $usercheck;
        }

        function instructor_usercheck($email){
            global $db;
            $email = strtolower($email);
            $db->where("Email", $email);
            $db->where("EmployeeGroup",3);
            $usercheck = $db->getOne("users");
            if(empty($usercheck)){
                $email = strtolower($email);
                $db->where("Email", $email);
                $db->where("EmployeeGroup",0);
                $db->where("is_instructor",1);
                $usercheck = $db->getOne("users");
            }
            return $usercheck;
        }

        function invoicecheck($invoiceid){
            global $db;

            $db->where("Id", $invoiceid);
            $invoice = $db->getOne("orders");

            if($invoice['CustomerId'] == $_SESSION['CustomerId']){
                $invoicecheck = 1;
            }else{
                $invoicecheck = 0;
            }

            return $invoicecheck;
        }

        function salaryratereference_check($referenceid,$referencenumber){
            global $db;

            $db->where("Id", $referenceid);
            $salaryrate = $db->getOne("salaryrates");

            $db->where("CustomerId", $_SESSION['CustomerId']);
            $db->where("ReferenceId", $referencenumber);
            $check = $db->getOne("salaryrates");

            if(!empty($salaryrate) && !empty($check)){
                if($salaryrate['Id'] == $check['Id']){
                    $check = false;
                }else{
                    $check = true;
                }
            }

            return $check;
        }

        function salarysupplementratereference_check($referenceid,$referencenumber){
            global $db;

            $db->where("Id", $referenceid);
            $salaryrate = $db->getOne("salarysupplementrates");

            $db->where("CustomerId", $_SESSION['CustomerId']);
            $db->where("ReferenceId", $referencenumber);
            $check = $db->getOne("salarysupplementrates");

            if(!empty($salaryrate) && !empty($check)){
                if($salaryrate['Id'] == $check['Id']){
                    $check = false;
                }else{
                    $check = true;
                }
            }

            return $check;
        }




        function get_absence($absenceid){
            global $db;

            $db->where("Id", $absenceid);
            $absence = $db->getOne('absence');


            return $absence;

        }

        function get_absence_icon($absenceid){

            if($absenceid == 1){
                $absence_icon = '
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 57.66 57.7"><defs><style>.cls-1{fill:#30694a;}</style></defs><title>Sygdom02</title><g id="Lag_2" data-name="Lag 2"><g id="Icons"><path class="cls-1" d="M47.56.81A13.83,13.83,0,0,0,32.8,4.27c-9.6,9.47-19.09,19-28.61,28.58A13.9,13.9,0,0,0,.44,39.59c-.18.77-.29,1.56-.44,2.34v2.4c.15.8.27,1.61.47,2.39A14.52,14.52,0,0,0,12.93,57.59a3.54,3.54,0,0,1,.44.11h2.51a2.72,2.72,0,0,1,.38-.11,14.11,14.11,0,0,0,8.59-4.08q14.31-14.28,28.58-28.6a13.86,13.86,0,0,0,4-12.73A13.77,13.77,0,0,0,47.56.81ZM35,38.18c-4.27,4.27-8.5,8.57-12.81,12.79a10.94,10.94,0,0,1-18.16-4.9A10.44,10.44,0,0,1,6.7,35.51C10.91,31.18,15.22,27,19.49,22.7l.37-.33,15.4,15.57ZM48.27,10.69a10.54,10.54,0,0,0-10.89,2.69c-3.1,3-6.12,6.09-9.17,9.15a3.72,3.72,0,0,0-.35.49l-3.3-3.31a4.09,4.09,0,0,1,.3-.33q4.82-4.83,9.65-9.65a11,11,0,0,1,15.18-.38,7.55,7.55,0,0,0,.78.52C49.44,11.06,49.45,11,48.27,10.69Z"/></g></g></svg>';
            }elseif($absenceid == 2){
                $absence_icon = '
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70.69 58.98"><defs><style>.cls-1{fill:#30694a;}</style></defs><title>Ferie</title><g id="Lag_2" data-name="Lag 2"><g id="Icons"><path class="cls-1" d="M68,16.82A58.1,58.1,0,0,0,41.33,1.56l.08,0a7.72,7.72,0,0,0-1-.23s-.11-.1-.19-.4A.58.58,0,0,0,40.4.61.55.55,0,0,0,39.92,0a.54.54,0,0,0-.6.48.52.52,0,0,0,.13.42,1.25,1.25,0,0,0-.28.25h0c-1-.18-2-.31-3-.41l-.24,0-.13,0a50,50,0,0,0-14.38.7A78.86,78.86,0,0,0,2.6,7.14l.12.25L3.53,7l-1,1.7a10.36,10.36,0,0,1,1.4-.05,59.88,59.88,0,0,0,7,2.83l1.4-1.23.45,1c3.2.22,14,3.81,14.67,4a5.13,5.13,0,0,0,2.37-1l1.09,1c.59,0,2.68.47,5.15,1l-5.9,32.44C14.82,48.91,2.26,53.3,0,59H62.45c-2.28-5.74-15.07-10.15-30.61-10.27l5.83-32.08c3.8.85,7.9,1.79,8.63,1.85a3.05,3.05,0,0,0,2.34-1.18L50.06,19c1.38,0,12.53,2,12.82,2,1,0,1.71-1.2,2.06-1.89l.63,1.69,3.88.17.24-2.12.79.82.21-.19S69.7,18.37,68,16.82ZM33.18.91c.33,0,.66,0,1-.05h0C33.85.88,33.51.88,33.18.91Z"/></g></g></svg>';
            }elseif($absenceid == 3){
                $absence_icon = '
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 59.63 59.68"><defs><style>.cls-1{fill:#30694a;}</style></defs><title>Skade</title><g id="Lag_2" data-name="Lag 2"><g id="Icons"><path class="cls-1" d="M59.25,43.84a11.13,11.13,0,0,0-2.66-5.29c-2.33-2.41-4.7-4.78-7.06-7.17,0,0,0,0,0,0L31.35,49.54l.08.09c2.13,2.1,4.25,4.2,6.39,6.29a11.63,11.63,0,0,0,1.71,1.51,12.87,12.87,0,0,0,19.79-7.89c.13-.6.21-1.21.31-1.82V46.08l-.06-.3C59.47,45.14,59.4,44.48,59.25,43.84Z"/><path class="cls-1" d="M56.32,21.31A10.57,10.57,0,0,0,59.22,16,12.59,12.59,0,0,0,55.65,3.59,12.36,12.36,0,0,0,49.42.29a12.69,12.69,0,0,0-8.56,1.16,9.92,9.92,0,0,0-2.27,1.72q-4.29,4.1-8.5,8.27Q17.19,24.31,4.34,37.23L3.58,38A12.36,12.36,0,0,0,.05,45.66a2,2,0,0,1,0,.24v1.7c.09.61.15,1.22.28,1.82a12.45,12.45,0,0,0,7.84,9.29,12.44,12.44,0,0,0,12.21-1.48,13.64,13.64,0,0,0,1.9-1.75Q32.46,45.33,42.66,35.13C47.24,30.55,51.75,25.89,56.32,21.31ZM31.71,12.7,46.9,27.89l-.87.87Q38.47,21.22,30.87,13.6Zm-2.28,5.57a1.72,1.72,0,0,1,1.84.83,1.35,1.35,0,0,1,0,1.69,1.71,1.71,0,0,1-1.76.83A1.92,1.92,0,0,1,28.05,20,2,2,0,0,1,29.43,18.27ZM24.9,23.21A1.7,1.7,0,0,1,26.58,25,2,2,0,0,1,25,26.61a1.76,1.76,0,0,1-1.81-1.71A1.64,1.64,0,0,1,24.9,23.21Zm1.69,11.46a1.72,1.72,0,0,1-1.71,1.79,1.61,1.61,0,0,1-1.69-1.7A1.69,1.69,0,0,1,25,33.06,2,2,0,0,1,26.59,34.67Zm-6.66-6.55a1.85,1.85,0,0,1,1.73,1.72,2.07,2.07,0,0,1-1.72,1.75,2,2,0,0,1-1.75-1.76A1.86,1.86,0,0,1,19.93,28.12Zm8,18.86L12.73,31.77l.86-.86L28.67,46.17C28.47,46.37,28.18,46.68,27.91,47Zm1.9-5.52a1.85,1.85,0,0,1-1.71-1.74A1.73,1.73,0,0,1,29.82,38a1.88,1.88,0,0,1,1.75,1.7A2,2,0,0,1,29.81,41.46Zm0-9.87a2,2,0,0,1-1.79-1.76,2,2,0,0,1,1.74-1.75,2,2,0,0,1,1.78,1.76A2,2,0,0,1,29.84,31.59Zm4.93,4.88A1.77,1.77,0,0,1,33,34.68a2,2,0,0,1,1.6-1.61,1.7,1.7,0,0,1,1.79,1.73A1.6,1.6,0,0,1,34.77,36.47Zm-.1-9.86A2,2,0,0,1,33,25a1.67,1.67,0,0,1,1.67-1.77,1.59,1.59,0,0,1,1.73,1.68A1.72,1.72,0,0,1,34.67,26.61Zm5,5A2,2,0,0,1,38,29.8a1.92,1.92,0,0,1,1.65-1.7,1.89,1.89,0,0,1,1.81,1.71A2,2,0,0,1,39.7,31.59Z"/><path class="cls-1" d="M2.63,20.64c1.25,1.32,2.5,2.65,3.78,3.95s2.49,2.47,3.71,3.68L28.27,10.13a.18.18,0,0,0-.05-.07L22.07,4A13.17,13.17,0,0,0,19,1.61,12.58,12.58,0,0,0,10.22.29,12.81,12.81,0,0,0,1.15,18.14,9,9,0,0,0,2.63,20.64Z"/></g></g></svg>';
            }elseif($absenceid == 4){
                $absence_icon = '
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 58.37 59.44"><defs><style>.cls-1{fill:#30694a;}</style></defs><title>Barsel</title><g id="Lag_2" data-name="Lag 2"><g id="Icons"><path class="cls-1" d="M4.8,24.55a4.08,4.08,0,0,1,1,4.23c-.44,1.54-1,3.06-1.56,4.57a29.73,29.73,0,0,0-1.8,7.28A17.17,17.17,0,0,0,16.62,59.24a17.71,17.71,0,0,0,8.82-.93c3.63-1.31,5.46-4.17,6.74-7.55.22-.56.37-1.14.58-1.7a2.55,2.55,0,0,1,3.83-1.45,11.42,11.42,0,0,1,2,1.63c1,.91,1.92,1.88,2.94,2.76a3.45,3.45,0,0,0,4.09.39,6.63,6.63,0,0,0,3.17-3.06c.54-1.13.22-1.78-1-1.94a6.28,6.28,0,0,0-2.12.09c-1.29.28-1.84.18-2.63-.9-.59-.83-1-1.74-1.65-2.56a36.91,36.91,0,0,0-3.07-4,4.5,4.5,0,0,0-5.51-1,11.33,11.33,0,0,1-6.69.79,4,4,0,0,1,.49-.16,12.65,12.65,0,0,0,9.34-6.57c1.23-2.27,2.1-4.74,3.13-7.12.14-.33.27-.66.38-1A4.15,4.15,0,0,1,43.78,22a40.9,40.9,0,0,1,4.83.24c5,.63,8.57-1.63,9.76-6.37-1.2,2.26-9.09.23-11.73.69-1.41.24-2.86.25-4.26.53a7.17,7.17,0,0,0-5.16,3.83c-.86,1.66-1.45,3.46-2.2,5.18s-1.51,3.58-2.42,5.29a8.51,8.51,0,0,1-4.11,3.82c-.26-1.46-.46-2.84-.79-4.19a4.73,4.73,0,0,0-.82-1.57,1.62,1.62,0,0,0-2.17-.7A1.84,1.84,0,0,0,23.55,31c.18,1,.05,1.15-.88.94A12.67,12.67,0,0,1,20,31.07c-1.47-.7-1.5-1.78-.06-2.55a14.57,14.57,0,0,1,2.95-1c.8-.23,1.64-.33,2.44-.58,2-.64,3.18-2,2.87-4.09a11.42,11.42,0,0,1,1-5.8c2-5.65.37-13.09-7.28-15.81A17.81,17.81,0,0,0,10.42.69C5.06,2.34,2.18,6.33.59,11.47a10.77,10.77,0,0,0,2.3,10.94C3.52,23.13,4.13,23.87,4.8,24.55Z"/></g></g></svg>';
            }elseif($absenceid == 5){
                $absence_icon = '
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50.34 58.09"><defs><style>.cls-1{fill:#30694a;}</style></defs><title>Fridag</title><g id="Lag_2" data-name="Lag 2"><g id="Icons"><path class="cls-1" d="M26.43,33.69c-1-1.55-2-3-3-4.57l-.17.08c-.07.25-.12.5-.2.75-.26.78-.67,1.17-1.23,1.16s-1-.4-1.22-1.17a5.56,5.56,0,0,1,0-2.69,11.51,11.51,0,0,0,.24-3.08c-.29-2.21-.78-4.4-1.21-6.59A1,1,0,0,0,19.2,17c-.56-.38-1.16-.71-1.74-1.06-1.7-1-3.42-2-5.08-3.11-.86-.55-1.38-.43-1.71.55s-.64,1.82-1,2.7A27.84,27.84,0,0,0,7.36,22.3c-.39,2.22,0,4.14,1.79,5.64.48.4.93.83,1.39,1.26,1.11,1.05,2.26,2.07,3.3,3.19a3.48,3.48,0,0,1,.77,1.66,1,1,0,0,1-1.26,1.14,3.3,3.3,0,0,1-1.56-.84,26.79,26.79,0,0,1-2.88-3.5,15.91,15.91,0,0,1-3-8.5A19.22,19.22,0,0,1,8.27,12.24c.81-1.42,1.55-2.82,1.28-4.54A.77.77,0,0,1,10,7.07c.16-.06.44.22.64.38s.09.2.13.31a3.9,3.9,0,0,0,2,2.23c.78.39,1.58.74,2.32,1.2,1.34.84,2.64,1.74,4,2.65-.31-1.3-.55-2.62-.93-3.91A14.15,14.15,0,0,0,10.26.73C7.3-.67,4.9,0,2.85,2.5a13.81,13.81,0,0,0-2.81,8A22.77,22.77,0,0,0,2,20.91,80.71,80.71,0,0,0,8.28,32.62c2.21,3.5,4.46,7,6.78,10.43a5.56,5.56,0,0,0,5.36,2.33,10.72,10.72,0,0,0,6.23-2.88,4.34,4.34,0,0,0,1.52-4.22A12,12,0,0,0,26.43,33.69Z"/><path class="cls-1" d="M44.24,11.79a5.41,5.41,0,0,0-6.89,0,13.86,13.86,0,0,0-4.43,5.59,15.74,15.74,0,0,0-1.21,8.1c.05.54.09,1.07.13,1.61A26.92,26.92,0,0,1,36.65,22a4.17,4.17,0,0,0,1.61-3.12c0-.34.16-.72.54-.66a.94.94,0,0,1,.58.63,5.71,5.71,0,0,0,1.84,3.37,16.85,16.85,0,0,1,3.36,4.34A17.91,17.91,0,0,1,47,34.06a19.55,19.55,0,0,1-2.38,10.21A7.65,7.65,0,0,1,43.23,46a1.08,1.08,0,0,1-1.41.23,1.24,1.24,0,0,1-.55-1.39,6.25,6.25,0,0,1,.65-1.8c1.1-1.83,2.27-3.63,3.4-5.44a4.58,4.58,0,0,0,.48-3.93,14.63,14.63,0,0,0-2.63-4.8q-1.77-2.28-3.51-4.59a.91.91,0,0,0-1.63,0c-.58.71-1.16,1.44-1.81,2.1C35,27.62,33.79,28.82,32.55,30a1.07,1.07,0,0,0-.32,1,35.09,35.09,0,0,1,.37,3.67,13.69,13.69,0,0,0,1.6,6.7,3,3,0,0,1,.19,2.33,1.06,1.06,0,0,1-1.86.49A9.18,9.18,0,0,1,31.66,43c-.37.93-.76,1.83-1.09,2.75a17.37,17.37,0,0,0-1.29,6.82,4.38,4.38,0,0,0,2,3.9,11.36,11.36,0,0,0,5.83,1.63,6.29,6.29,0,0,0,6.4-4.61c.54-1.87,1.22-3.7,1.81-5.56A112.39,112.39,0,0,0,50,29.86,23,23,0,0,0,49.27,19,13.71,13.71,0,0,0,44.24,11.79Z"/></g></g></svg>';
            }elseif($absenceid == 6){
                $absence_icon = '
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35.12 59.09"><defs><style>.cls-1{fill:#30694a;}</style></defs><title>Rejse</title><g id="Lag_2" data-name="Lag 2"><g id="Icons"><path class="cls-1" d="M35.11,16.76a7.6,7.6,0,0,0-7.86-7.85H25.57V8c0-2.33,0-4.65,0-7,0-.64-.22-1.06-.94-1.06H10.46c-.66,0-.92.35-.92,1,0,1.94,0,3.87,0,5.8V8.91H7.47A7.57,7.57,0,0,0,0,16.51q0,15.55,0,31.1a7.5,7.5,0,0,0,3.39,6.51,1.3,1.3,0,0,1,.51.71,5.22,5.22,0,0,0,4.47,4.22,5.24,5.24,0,0,0,5.37-3.13,1,1,0,0,1,.67-.45q3.12,0,6.26,0a.91.91,0,0,1,.67.44,5.18,5.18,0,0,0,9.85-1,1.64,1.64,0,0,1,.58-.82,7.61,7.61,0,0,0,3.35-6.47Q35.11,32.19,35.11,16.76Zm-24-15.14H24V8.53H11.08Zm16,50.77c-6.44-.12-12.89,0-19.34,0A4.57,4.57,0,0,1,3,47.61q0-10,0-20v-.79H14.24c0,.56,0,1.1,0,1.64s.28.86.86.86H20c.66,0,.95-.33.92-1,0-.48,0-1,0-1.51H32c0,.32,0,.6,0,.88,0,6.57-.07,13.15,0,19.73A4.86,4.86,0,0,1,27.09,52.39Zm5-27.56a1.27,1.27,0,0,1-.09.33H20.87c0-.54,0-1.05,0-1.56,0-.66-.26-1-.91-1-1.62,0-3.24,0-4.85,0-.62,0-.9.33-.87.94s0,1,0,1.61H3v-2C3,21,3,18.88,3,16.77A4.57,4.57,0,0,1,7.79,12H27.3a4.46,4.46,0,0,1,4.76,4.45C32.18,19.24,32.09,22,32.08,24.83Z"/></g></g></svg>';
            }else{
                $absence_icon = '
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 59.88 67.54"><defs><style>.cls-1{fill:#57b782;}.cls-2{fill:#3c9564;}</style></defs><title>CreateAbsence</title><g id="Lag_2" data-name="Lag 2"><g id="Icons"><path class="cls-1" d="M48.74,58.25a26.83,26.83,0,0,1-2.19,1.8v.76h2.19Z"/><path class="cls-1" d="M13.34,60.05a25.37,25.37,0,0,1-2.2-1.8v2.56h2.2Z"/><polygon class="cls-1" points="10.85 59.72 9.4 58.34 49.69 15.67 51.23 16.97 10.85 59.72"/><polyline class="cls-1" points="49.69 15.67 50.01 18.18 10.85 59.72 9.4 58.34"/><path class="cls-2" d="M13.34,51.26V34.89c0-4.56,10.35-8.56,16.6-8.56a24.09,24.09,0,0,1,6.08.92l1.68-1.78a27.87,27.87,0,0,0-7.76-1.33c-6.45,0-18.8,4.19-18.8,10.75V53.58Z"/><path class="cls-2" d="M43.55,28,42,29.63c2.64,1.48,4.53,3.32,4.53,5.26V57.51a26.67,26.67,0,0,0,2.19-2.06V34.89C48.74,32.13,46.56,29.8,43.55,28Z"/><path class="cls-1" d="M29.94,21.51a9,9,0,0,1-9.14-9.15V9.14A8.94,8.94,0,0,1,29.94,0a9,9,0,0,1,9.15,9.14v3.22A9,9,0,0,1,29.94,21.51Zm0-19.32a6.73,6.73,0,0,0-7,7v3.22a6.72,6.72,0,0,0,7,7,6.72,6.72,0,0,0,7-6.95V9.14A6.72,6.72,0,0,0,29.94,2.19Z"/><path class="cls-2" d="M29.94,0A8.94,8.94,0,0,0,20.8,9.14v3.22a9,9,0,0,0,9.14,9.15,9,9,0,0,0,9.15-9.15V9.14A9,9,0,0,0,29.94,0Zm7,12.36a6.72,6.72,0,0,1-6.95,7,6.72,6.72,0,0,1-7-6.95V9.14a6.73,6.73,0,0,1,7-7,6.72,6.72,0,0,1,7,7Z"/><path class="cls-1" d="M41.09,9.81V12A27.95,27.95,0,1,1,18.8,12V9.81a29.94,29.94,0,1,0,22.29,0Z"/></g></g></svg>';
            }

            return $absence_icon;

        }

        function get_notifications(){
            global $db;

    // Make this a setting
            $autotakeover = 0;
            $autocomplete = 0;


            $current_date = date('Y-m-d H:i:s');

    // Condition 1 - get not completed
            $db->where('CustomerId', $_SESSION['CustomerId']);
            $db->where('DepartmentId', $_SESSION['CurrentDepartment']);
            $db->where('UserId','0','!=');
            $db->where('End',$current_date,'<');
            $db->where('Completed',0,'=','OR');

    // Condition 2 - get available
            $db->where('CustomerId', $_SESSION['CustomerId']);
            $db->where('DepartmentId', $_SESSION['CurrentDepartment']);
            $db->where('Available',1,'=','OR');

    // Condition 3 - get not approved absence
            $db->where('CustomerId', $_SESSION['CustomerId']);
            $db->where('DepartmentId', $_SESSION['CurrentDepartment']);
            $db->where('AbsenceId',0,'!=');
            $db->where('Approved',0,'=','OR');

    // Condition 4 - get not approved takeovers
            $db->where('CustomerId', $_SESSION['CustomerId']);
            $db->where('DepartmentId', $_SESSION['CurrentDepartment']);
            $db->where('TakenDate','0000-00-00 00:00:00','!=');
            $db->where('ApprovedTakeover',0,'=');

            $shifts = $db->get('schedule');

            $shifts_not_completed           = array();
            $shifts_available               = array();
            $shifts_absence_not_approved    = array();
            $shifts_takeover_not_approved   = array();

            foreach($shifts as $shift){

                $shift_time = convert_start_end_date($shift['Start'],$shift['End']);

                if($shift_time != '-'){

            // Condition 1
                    if($shift['UserId'] != 0 && $shift['End'] < $current_date && $shift['Completed'] == 0){
                        $shifts_not_completed[] = $shift;
                    }

            // Condition 2
                    if($shift['Available'] == 1){
                        $shifts_available[] = $shift;
                    }

            // Condition 3
                    if($shift['AbsenceId'] != 0 && $shift['Approved'] == 0){
                        $shifts_absence_not_approved[] = $shift;
                    }

            // Condition 4
                    if($shift['TakenDate'] != '0000-00-00 00:00:00' && $shift['ApprovedTakeover'] == 0){
                        $shifts_takeover_not_approved[] = $shift;
                    }

                }

            }


            $shifts_absence_not_approved_count = count($shifts_absence_not_approved);
            $shifts_available_count = count($shifts_available);
            $shifts_not_completed_count = count($shifts_not_completed);
            $shifts_takeover_not_approved_count = count($shifts_takeover_not_approved);


            $notifications = $shifts_absence_not_approved_count + $shifts_not_completed_count + $shifts_takeover_not_approved_count;

            return $notifications;

        }



        function getlanguage($country){

            if($country == 'Denmark'){
                $language = 'da_DK';
            }

            if($country == 'USA'){
                $language = 'en_US';
            }

            if($country == 'GB'){
                $language = 'en_GB';
            }

            return $language;

        }

        function getlangaugepath_calendar($language){

            if($language == 'da_DK'){
                $path = 'da.js';
            }

            if($language == 'en_US'){
                $path = 'en-gb.js';
            }

            if($language == 'en_GB'){
                $path = 'en-gb.js';
            }

            return $path;

        }

        function getstarttime_calendar($departmentid,$week){

            global $db;

            $db->where('DepartmentId', $departmentid);
            $db->where('Week', $week);
            $db->orderBy("Start","desc");
            $schedule = $db->get('schedule');

            $start = "00:00:00";

            foreach($schedule as $shift){

                $zero = $shift['Date'] . " 00:00:00";

                $time = date("H:i:s",strtotime($shift['Start']));

                if(($shift['Start'] != $zero) && ($time < $start)){

                    $start = $time;

                }
            }

            return $start;

        }

        function getendtime_calendar($departmentid,$week){

            global $db;

            $db->where('DepartmentId', $departmentid);
            $db->where('Week', $week);
            $db->orderBy("End","desc");
            $schedule = $db->get('schedule');

            $end = "24:00:00";

            foreach($schedule as $shift){

                $zero = $shift['Date'] . " 00:00:00";

                $time = date("H:i:s",strtotime($shift['Start']));

                if(($shift['End'] != $zero) && ($time > $end)){

                    $end = $time;

                }
            }

            return $end;

        }


//PLANGY
        function send_mail($language, $name, $array, $pdfFile = ''){

            global $db;

            $db->where('Name', $name);
            $db->where('Language', $language);
            $mail = $db->getOne('mails');

            $style = "
            <style>

            @import url('https://fonts.googleapis.com/css?family=Ubuntu:300,400,400i,500,700');

            body {
                width: 100%;
                height: 100%;
                background: #f6f9fa;
                margin: 0;
                padding: 50px 0;
                text-align: center;
                font-family: 'Ubuntu', sans-serif;
                font-size: 14px;
                font-weight: 300;
                line-height: 21px;
                color: #777;
            }

            .wrapper {
                width: 100%;
                max-width: 600px;
                margin: 0 auto;
                padding: 0 50px;
                border-radius: 5px;
                overflow: hidden;
            }

            .header {
                width: 100%;
                height: auto;
                padding: 0 20px;
                float: none;
                display: inline-block;
                background: white;
                position: relative;
                box-sizing: border-box;
            }

            .header:before {
                content: '';
                width: 100%;
                height: 100%;
                background: url(https://www.fitforkids.iomweb.dk/assets/img/mailheader.jpg);
                background-size: cover;
                opacity: 1;
                position: absolute;
                top: 0;
                left: 0;
                z-index: 1;
            }


            .logo {
                position: relative;
                z-index: 2;
                width: 100%;
                display: inline-block;
                float: none;
				background: white;
				text-align: center;
            }

            .logo img {
                height: 20px;
                width: auto;
                padding: 15px 0 20px;
				max-width: 100px;
            }

            .main {
                width: 100%;
                margin: 0;
                padding: 0;
                background: #fff;
                border-radius: 5px;
                overflow: hidden;
                float: none;
                display: inline-block;
            }

            .content {
                width: 100%;
                margin: 0;
                padding: 25px 60px 50px 60px;
                text-align: left;
                float: none;
                display: inline-block;
                box-sizing: border-box;
            }

            .content ul {
                margin: 0 0 15px;
                padding: 0 0 0 10px;
            }

            .content ul li {
                list-style-type: none;
                position: relative;
            }

            .content ul li:before {
                content: '';
                background: #2cb48f;
                width: 3px;
                height: 10px;
                border-radius: 5px;
                position: absolute;
                left: -9px;
                top: 5px;
                transform: rotate(0deg);
                opacity: 1;
            }

            .regards {
                margin-top: 50px;
                color: #111;
            }

            .regards strong {
                width: 100%;
                float: left;
                font-weight: 400;
                font-size: 15px;
            }

            .footer {
                margin-bottom: 30px;
            }

            .footer p {
                color: #b6c1c5;
                margin-top: 20px;
                margin-bottom: 0;
                font-size: 12px;
            }

            .footer ul {
                padding: 0;
                margin: 0;
            }

            .footer ul li {
                display: inline-block;
            }

            .footer ul li a {
                padding: 5px 10px;
                display: inline-block;
                font-size: 12px;
            }

            .center {
                text-align: center;
                display: inline-block;
                width: 100%;
            }

            strong {
                font-weight: 400;
            }

            h1 {
                font-size: 21px;
                font-weight: 400;
                margin-bottom: 20px;
                color: #111;
            }

            h2 {
                margin: 30px 0 10px 0;
                font-size: 17px;
                font-weight: 400;
                color: #111;
            }

            p {
                font-size: 14px;
                font-weight: 300;
                line-height: 21px;
                margin: 0 0 10px;
            }

            a {
                color: #2cb48f;
                font-weight: 400;
                transition: all 200ms ease;
            }

            a:hover {
                color: #4d997d;
            }

            a.btn {
                border: 1px solid #2cb48f;
                background: #2cb48f;
                padding: 12px 45px;
                border-radius: 40px;
                display: inline-block;
                font-size: 16px;
                font-weight: 300;
                text-decoration: none;
                color: #fff;
                margin-top: 20px;
            }

            a.btn:hover {
                border: 1px solid #24a582;
                background: #24a582;
                color: #fff;
            }

            .btn-continue {
                width: 30px;
                height: 30px;
                background: url(https://www.fitforkids.iomweb.dk/assets/img/icon-arrow-right-white.png);
                background-size: 16px;
                background-repeat: no-repeat;
                background-position: center;
                position: absolute;
                top: 13px;
                right: 10px;
                z-index: 2;
                transition: all 200ms ease;
            }

            .header:hover .btn-continue {
                right: 14px;
            }
            </style>
            ";

            if(isset($array['firstname']) && isset($array['lastname'])){
                $to_name = $array['firstname'] . " " . $array['lastname'];
            }else{
                $to_name = $array['firstname'];
            }

            $to = $to_name  . " < " . $array['email'] . " > ";
            $from = $mail['FromMail'];
            $name = "FitforKids";
            $subject = $mail['Subject'];

            $headers = "From:" . $name . "<" . $from . ">\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF8\r\n";

            $message = "<html>";
            $message .= $style;
            $message .= "<div class='wrapper'>";
            $message .= "<div class='main'>";
            $message .= "<a href='".$mail['Link']."' class='header'><div class='logo'><img style='margin: 0 auto;display: block;' src='https://www.fitforkids.dk/assets/img/logo-blue.jpg' width='200' height='40'/></div><span class='btn-continue'></span></a>";
            $message .= "<div class='content' style='color: rgb(66,33,00);'>";
            $message .= $mail['Message'];
            $message .= "<div class='regards'>".$mail['Regards']."</div>";
            $message .= "</div>";
            $message .= "</div>";
            $message .= "<div class='footer'>".$mail['Footer']."</div>";
            $message .= "</div>";
            $message .= "</body></html>";



    //REPLACE SUBJECT
            if(isset($array)){
                foreach($array as $key => $value){
                    $subject = str_replace("%".$key."%", $value, $subject);
                }
            }

    //REPLACE MESSAGE
            if(isset($array)){
                foreach($array as $key => $value){
                    $message = str_replace("%".$key."%", $value, $message);
                }
            }
            if($pdfFile != ''){
                $mail = new PHPMailer();
                $is_successfull = false;
                $to = $array['email'];
                $tomail = trim($to);
                $subject = $subject;
                $mail->isSMTP();
                $mail->Host = 'localhost';
                $mail->SMTPAuth = false;
                $mail->SMTPAutoTLS = false;
                $mail->Port = 25;
                $mail->setFrom('no-reply@fitforkids.dk', $name);
                $mail->addReplyTo('no-reply@fitforkids.dk', $name);
                $mail->addAddress($to, $name);
                $mail->Subject = $subject;
                $mail->AltBody = '';
                $mail->isHTML(true);
                $mail->CharSet = "text/html; charset=UTF-8;";
                $mail->Body = $message;
                $mail->AddAttachment($pdfFile);
                if (!$mail->Send()) {
                    $is_successfull = 0;
                    $msg = 'Unable to submit. Please try again later.';
                } else {
                    $is_successfull = 1;
                    $msg = "Email sent.";
                    if($pdfFile != '') {
                        unlink($pdfFile);
                    }
                }
                $result_mail['success'] = $is_successfull;
                $result_mail['msg'] = $msg;
                echo json_encode($result_mail);
            } else {
                mail($to,$subject,$message,$headers);
            }


        }

//PLANGY
        function send_message($customerid, $departmentid, $senderid, $receiverid, $message){

            global $db;

            if(empty($receiverid)){
                header("Location: /messages");
            }

            $db->where("UserId", $senderid);
            $connections = $db->get('message_connect');

            $connected = 0;
    /*$db->where("UserId", $receiverid);
    $message_connect_receiver = $db->get('message_connect');*/

    foreach($connections as $connection){

        $db->where("MessageGroupId", $connection['MessageGroupId']);
        $participants = $db->get('message_connect');

        foreach($participants as $participant){
            if($participant['UserId'] == $receiverid){
                $connected = 1;
                $connected_group = $participant['MessageGroupId'];
            }
        }
    }

    if($connected == 0){
        // Create Group
        $data = array(
            "CustomerId"            => $customerid,
            "DepartmentId"          => $departmentid,
            "CreateDate"            => $db->now()
        );
        $db->insert('message_group', $data);
        $message_group_id = $db->getInsertId();

        // Create Connection
        $data = array(
            "MessageGroupId"        => $message_group_id,
            "UserId"                => $senderid,
            "CreateDate"            => $db->now(),
            "LastActive"            => $db->now()
        );
        $db->insert('message_connect', $data);

        // Create Connection
        $data = array(
            "MessageGroupId"        => $message_group_id,
            "UserId"                => $receiverid,
            "CreateDate"            => $db->now(),
            "LastActive"            => $db->now()
        );
        $db->insert('message_connect', $data);

        // Create Message
        $data = array(
            "MessageGroupId"        => $message_group_id,
            "UserId"                => $senderid,
            "Message"               => $message,
            "CreateDate"            => $db->now()
        );
        $db->insert('messages', $data);

        logaction(19,0,0);

    }else{

        // Create Message
        $data = array(
            "MessageGroupId"        => $connected_group,
            "UserId"                => $senderid,
            "Message"               => $message,
            "CreateDate"            => $db->now()
        );
        $db->insert('messages', $data);

        // Update all connections
        $data = array(
            "LastActive"            => $db->now()
        );
        $db->where('MessageGroupId', $connected_group);
        $db->update('message_connect', $data);

        logaction(20,0,0);

    }

}

//PLANGY
function send_message_simple($userid, $messagegroupid, $message){

    global $db;

    if(empty($message)){
        header("Location: /messages");
    }

    // Create Message
    $data = array(
        "MessageGroupId"        => $messagegroupid,
        "UserId"                => $userid,
        "Message"               => $message,
        "CreateDate"            => $db->now()
    );
    $db->insert('messages', $data);

    // Update all connection
    $data = array(
        "LastActive"            => $db->now()
    );

    $db->where('MessageGroupId', $messagegroupid);
    $db->update('message_connect', $data);

    logaction(20,0,0);

}

//PLANGY
function get_messages_list($departmentid, $userid){

    global $db;

    $db->where("UserId", $userid);
    $db->orderBy("LastActive","desc");
    $message_connections = $db->get('message_connect');

    foreach($message_connections as $message_connection) {

        $db->where("MessageGroupId", $message_connection['MessageGroupId']);
        $db->orderBy("Id","desc");
        $message_users = $db->get('message_connect');

        $db->where("MessageGroupId", $message_connection['MessageGroupId']);
        $db->orderBy("Id","desc");
        $message = $db->getOne('messages');

        echo "<li><a href='/messages/".$message['MessageGroupId']."'>";
        echo "<strong>";
        foreach($message_users as $message_user){
            $user = user_data_by_id($message_user['UserId']);

            if($message_user['UserId'] == $userid){
                echo " " . _('and you');
            }else{

                if(empty($user['Firstname'])){
                    echo "<span class='red'>" . _("User don't exist") . "</span>";
                }else{
                    echo $user['Firstname'] . " " . $user['Lastname'];
                }

            }
        }
        echo "</strong>";

        echo "<span>".$message['Message']."</span>";
        echo "<span class='timestamp'>".$message['CreateDate']."</span>";

        if($message['Seen'] == 0 && $message['UserId'] != $userid){
            echo "<i></i>";
        }

        echo "</a></li>";

    }

}

function check_message_restriction($messagegroupid, $userid){

    global $db;

    $db->where("MessageGroupId", $messagegroupid);
    $db->where("UserId", $userid);
    $check = $db->getOne('message_connect');

    if($check == false){
        header('Location: /messages');
        exit;
    }

}

//PLANGY
function if_seen($departmentid, $userid){

    global $db;

    $db->where("UserId", $userid);
    $message_connections = $db->get('message_connect');

    foreach($message_connections as $message_connection) {

        $db->where("MessageGroupId", $message_connection['MessageGroupId']);
        $db->orderBy("Id","desc");
        $message = $db->getOne('messages');

        $user = user_data_by_id($message['UserId']);

        if($message['Seen'] == 0 && $message['UserId'] != $userid){
            echo "<i></i>";
        }

    }

}

//PLANGY
function get_conversation($userid, $departmentid, $messagegroupid){

    global $db;

    $db->where("MessageGroupId", $messagegroupid);
    $messages = $db->get('messages');

    foreach($messages as $message){

        if($message['Seen'] == 0){
            if($message['UserId'] != $userid){

                $data = array('Seen' => '1');

                $db->where("Id", $message['Id']);
                $db->update('messages', $data);

            }
        }

        $user = user_data_by_id($message['UserId']);
        if($userid == $message['UserId']){
            $class_msg = "hl";
            $class = "rg";
        }else{
            $class_msg = "";
            $class = "rg";
        }

        echo "<div><div class='msg " . $class_msg . "'><span class='" . $class . "'>" . $message['Message'] . "</span><em> " . $user['Firstname'] . " " . $user['Lastname'] . "<span>" . $message['CreateDate'] . "</span></em></div></div>";

    }

}

//PLANGY
function switch_shift($shiftid,$date,$start,$end,$userid,$slug){
    global $db;

    $db->where("UserId", $userid);
    $db->where("Date", $date);
    $shift = $db->getOne('schedule');

    //Check if shift overlaps
    if($shift){
        $overlap = max($start, $shift['Start']) < min($end, $shift['End']);
    }else{
        $overlap = 0;
    }

    if($overlap == 1){
        //header('Location: /');
    }else{

        $data = Array (
            'Available' => 0,
            'Approved' => 0,
            'TakenDate' => $db->now(),
            'UserId' => $userid
        );

        $db->where("Id", $shiftid);

        $db->update ('schedule', $data);

    }

    return $overlap;
}

//PLANGY
function shift_overlap($date,$start,$end,$userid){
    global $db;

    $db->where("UserId", $userid);
    $db->where("Date", $date);
    $shift = $db->getOne('schedule');

    //Check if shift overlaps
    if($shift){
        $overlap = max($start, $shift['Start']) < min($end, $shift['End']);
    }else{
        $overlap = 0;
    }

    return $overlap;
}


function devices_with_id_as_key(){
    global $db;
    $devices_select = $db->get('devices');

    $devices_with_id_as_key = array();

    for ($i = 0; $i < count($devices_select); $i++) {

        $array_key_tmp = array_keys($devices_select[$i]);

        $devices_with_id_as_key[$devices_select[$i]["Id"]] = array();

        for ($j = 0; $j < count($array_key_tmp); $j++) {

            $devices_with_id_as_key[$devices_select[$i]["Id"]][$array_key_tmp[$j]] = $devices_select[$i][$array_key_tmp[$j]];

        }

    }
    return $devices_with_id_as_key;
}

function deviceoperations_with_id_as_key(){
    global $db;
    $deviceoperations_select = $db->get('deviceoperations');

    $deviceoperations_with_id_as_key = array();

    for ($i = 0; $i < count($deviceoperations_select); $i++) {

        $array_key_tmp = array_keys($deviceoperations_select[$i]);

        $deviceoperations_with_id_as_key[$deviceoperations_select[$i]["Id"]] = array();

        for ($j = 0; $j < count($array_key_tmp); $j++) {

            $deviceoperations_with_id_as_key[$deviceoperations_select[$i]["Id"]][$array_key_tmp[$j]] = $deviceoperations_select[$i][$array_key_tmp[$j]];

        }

    }
    return $deviceoperations_with_id_as_key;
}




// customers

function customers_with_id_as_key(){
    global $db;
    $customers_select = $db->get('customers');

    $customers_with_id_as_key = array();

    for ($i = 0; $i < count($customers_select); $i++) {

        $array_key_tmp = array_keys($customers_select[$i]);

        $customers_with_id_as_key[$customers_select[$i]["Id"]] = array();

        for ($j = 0; $j < count($array_key_tmp); $j++) {

            $customers_with_id_as_key[$customers_select[$i]["Id"]][$array_key_tmp[$j]] = $customers_select[$i][$array_key_tmp[$j]];

        }

    }
    return $customers_with_id_as_key;
}


// Plangy
function users_by_customer_id($id){
    global $db;

    $db->where("CustomerId", $id);
    $users = $db->get('users');

    $users_by_id = array();

    for ($i = 0; $i < count($users); $i++) {

        $array_key_tmp = array_keys($users[$i]);

        $users_by_customer_id[$users[$i]["Id"]] = array();

        for ($j = 0; $j < count($array_key_tmp); $j++) {

            $users_by_customer_id[$users[$i]["Id"]][$array_key_tmp[$j]] = $users[$i][$array_key_tmp[$j]];

        }

    }

    return $users_by_customer_id;

}

// Plangy
function users_by_department_id($id){
    global $db;

    $db->where("DepartmentId", $id);
    $users = $db->get('users');

    if(!empty($users)){

        $users_by_id = array();
        for ($i = 0; $i < count($users); $i++) {

            $array_key_tmp = array_keys($users[$i]);

            $users_by_customer_id[$users[$i]["Id"]] = array();

            for ($j = 0; $j < count($array_key_tmp); $j++) {

                $users_by_customer_id[$users[$i]["Id"]][$array_key_tmp[$j]] = $users[$i][$array_key_tmp[$j]];

            }

        }

        return $users_by_customer_id;

    }else{

        return false;

    }


}

// Plangy
function other_users($customerid,$departmentid){
    global $db;

    $db->where("CustomerId", $customerid);
    $db->where("DepartmentId", $departmentid,'!=');
    $users = $db->get('users');

    if(!empty($users)){

        $users_by_id = array();
        for ($i = 0; $i < count($users); $i++) {

            $array_key_tmp = array_keys($users[$i]);

            $users_by_customer_id[$users[$i]["Id"]] = array();

            for ($j = 0; $j < count($array_key_tmp); $j++) {

                $users_by_customer_id[$users[$i]["Id"]][$array_key_tmp[$j]] = $users[$i][$array_key_tmp[$j]];

            }

        }

        return $users_by_customer_id;

    }else{

        return false;

    }


}

// Plangy | User with Type = 2
function leader_by_customer_id($id, $departmentid){
    global $db;

    $db->where("CustomerId", $id);
    $db->where("DepartmentId", $departmentid);
    $db->where("Type", 3, "<");
    $users = $db->get('users');

    $users_by_id = array();

    for ($i = 0; $i < count($users); $i++) {

        $array_key_tmp = array_keys($users[$i]);

        $users_by_customer_id[$users[$i]["Id"]] = array();

        for ($j = 0; $j < count($array_key_tmp); $j++) {

            $users_by_customer_id[$users[$i]["Id"]][$array_key_tmp[$j]] = $users[$i][$array_key_tmp[$j]];

        }

    }
    return $users_by_customer_id;
}

// Plangy
function departments_by_leader($customerid,$userid,$type){
    global $db;

    if($type == 1){

        $db->where("CustomerId", $customerid);
        $departments = $db->get('departments');

    }elseif($type == 2){

        $db->where("LeaderId", $userid);
        $departments = $db->get('departments');

    }else{
        $departments = false;
    }

    return $departments;

}

// Plangy
function events_by_id($eventid){
    global $db;

    $db->where("Id", $eventid);
    $event = $db->getOne('events');

    return $event;
}

// Plangy
function events_by_date_department($date,$departmentid){
    global $db;

    $db->where("DepartmentId", $departmentid);
    $db->where("Date", $date);
    $events = $db->get('events');

    return $events;
}

// Plangy
function shift_by_user_dwy($userid,$day,$week,$year){
    global $db;

    $db->where("UserId", $userid);
    $db->where("Day", $day);
    $db->where("Week", $week);
    $db->where("Year", $year);
    $shifts = $db->getOne('schedule');

    return $shifts;
}

// Plangy
function shift_by_date_department($date,$departmentid){
    global $db;

    $db->where("DepartmentId", $departmentid);
    $db->where("Date", $date);
    $db->where("Start", $date . ' 00:00:00', '!=', 'AND');
    $db->where("End", $date . ' 00:00:00', '!=');
    $shifts = $db->get('schedule');

    return $shifts;
}

// Plangy
function shift_list_user($userid,$startperiod,$endperiod){
    global $db;

    //$startperiod = date('Y-m-d', strtotime($startperiod. ' - 1 days'));
    //$endperiod = date('Y-m-d', strtotime($endperiod. ' + 1 days'));

    $db->where("UserId", $userid);
    $db->where("Start", $startperiod . ' 00:00:00', '>', 'AND');
    $db->where("End", $endperiod . ' 23:59:59', '<');
    $db->orderBy("Date","asc");
    $shifts = $db->get('schedule');

    $shifts_return = array();

    foreach($shifts as $shift){

        $starttime = substr($shift['Start'],-8,8);
        $endtime = substr($shift['End'],-8,8);

        if($starttime != '00:00:00' && $endtime != '00:00:00'){
            $shifts_return[] = $shift;
        }

    }

    return $shifts_return;
}

// Plangy
function shift_overview_by_date($date,$departmentid,$userid){
    global $db;

    $db->where("DepartmentId", $departmentid);
    $db->where("Date", $date);
    $db->where("Start", $date . ' 00:00:00', '!=', 'AND');
    $db->where("End", $date . ' 00:00:00', '!=');
    $shifts = $db->get('schedule');

    $shifts_thisuser = array();
    $shifts_normal = array();
    $shifts_absence = array();
    $shifts_absence_pay = array();
    $shifts_absence_nopay = array();
    $shifts_available = array();
    $total_count = 0;

    $approved_takover = 0;

    foreach($shifts as $shift){

        if($shift['AbsenceId'] != 0){
            $absence = get_absence($shift['AbsenceId']);
        }

        // Normal
        if($shift['UserId'] == $shift['OriginalUserId'] && $shift['Available'] == 0 && $shift['TakenDate'] == '0000-00-00 00:00:00' && $shift['AbsenceId'] == 0){
            $shifts_normal[] = $shift;
            $total_count++;
        }elseif($shift['UserId'] != $shift['OriginalUserId'] && $shift['Available'] == 0 && $shift['TakenDate'] != '0000-00-00 00:00:00'){
            $shifts_normal[] = $shift;
            $total_count++;
        }elseif($shift['Available'] == 0 && $shift['TakenDate'] != '0000-00-00 00:00:00' && $shift['ApprovedTakeover']  == $approved_takover){
            $shifts_normal[] = $shift;
            $total_count++;
        }elseif($shift['Available'] == 0 && $shift['UserId'] !=  0 && $shift['OriginalUserId'] ==  0){
            $shifts_normal[] = $shift;
            $total_count++;
        }

        // All Absence
        if($shift['AbsenceId'] != 0 && $shift['Approved'] == 1 && $shift['Available'] == 0){
            $shifts_absence[] = $shift;
            $total_count++;
        }

        // Billable Absence
        if($shift['AbsenceId'] != 0 && $shift['Approved'] == 1 && $shift['Available'] == 0 && $absence['Type'] == 1){
            $shifts_absence_pay[] = $shift;
            $total_count++;
        }

        // Not billable Absence
        if($shift['AbsenceId'] != 0 && $shift['Approved'] == 1 && $shift['Available'] == 0 && $absence['Type'] == 0){
            $shifts_absence_nopay[] = $shift;
            $total_count++;
        }

        // Available
        if($shift['Available'] == 1){
            $shifts_available[] = $shift;
            $total_count++;
        }

    }

    $shifts_return = array(
        'Available' => $shifts_available,
        'Normal' => $shifts_normal,
        'Absence' => $shifts_absence,
        'AbsencePay' => $shifts_absence_pay,
        'AbsenceNoPay' => $shifts_absence_nopay,
        'TotalCount' => $total_count,
        'ThisUser' => $shifts_thisuser
    );

    return $shifts_return;
}

// Plangy
function shift_taken_by_date_department($date,$departmentid){
    global $db;

    $db->where("DepartmentId", $departmentid);
    $db->where("Date", $date);
    $db->where("AbsenceId",' 0', '!=', 'AND');
    $db->where("Start", $date . ' 00:00:00', '!=', 'AND');
    $db->where("End", $date . ' 00:00:00', '!=');
    $shifts = $db->get('schedule');

    return $shifts;
}


// Plangy
function shift_by_date_user($date,$userid){
    global $db;

    $db->where("UserId", $userid);
    $db->where("Date", $date);
    $db->where("Start", $date . ' 00:00:00', '!=', 'AND');
    $db->where("End", $date . ' 00:00:00', '!=');
    $shift = $db->getOne('schedule');

    return $shift;
}

// Plangy
function shift_by_date_user_department($date,$userid,$departmentid){
    global $db;

    $db->where("UserId", $userid);
    $db->where("DepartmentId", $departmentid);
    $db->where("Date", $date);
    $db->where("Start", $date . ' 00:00:00', '!=', 'AND');
    $db->where("End", $date . ' 00:00:00', '!=');
    $shift = $db->getOne('schedule');

    return $shift;
}

function shift_by_date_user_original($date,$userid){
    global $db;

    $db->where("OriginalUserId", $userid);
    $db->where("Date", $date);
    $db->where("Start", $date . ' 00:00:00', '!=', 'AND');
    $db->where("End", $date . ' 00:00:00', '!=');
    $shift = $db->getOne('schedule');

    return $shift;
}

function shift_by_date_user_department_original($date,$userid,$departmentid){
    global $db;

    $db->where("OriginalUserId", $userid);
    $db->where("DepartmentId", $departmentid);
    $db->where("Date", $date);
    $db->where("Start", $date . ' 00:00:00', '!=', 'AND');
    $db->where("End", $date . ' 00:00:00', '!=');
    $shift = $db->getOne('schedule');

    return $shift;
}

// Plangy
function users_week_department($departmentid,$week,$year){
    global $db;

    $db->where("DepartmentId", $departmentid);
    $db->where("Week", $week);
    $db->where("Year", $year);
    $db->where("UserId",'0', '!=');
    $shifts = $db->get('schedule');

    $shifts_not_empty = array();

    foreach($shifts as $shift){

        $start  = strstr($shift['Start'], ' ');
        $end    = strstr($shift['End'], ' ');


        if($start == ' 00:00:00' && $end == ' 00:00:00'){
        }else{
            $shifts_not_empty[] = $shift;

        }

    }

    if(!empty($shifts)){
        $users = array_column($shifts_not_empty,'UserId');
        $users = array_unique($users);
    }else{
        $users = 0;
    }

    return $users;
}

// Plangy
function single_shift_pay($shiftid,$supplementamount) {
    global $db;

    // Get shift data
    $db->where('Id',$shiftid);
    $shift = $db->getOne('schedule');

    $shift_hours = convert_hours($shift['Start'],$shift['End']);

    // Get user data
    $user = user_data_by_id($shift['UserId']);

    // Get salary data
    $salary = salaryrate_by_id($user['SalaryId']);

    $shift_paid = 1;
    $return = 0;

    if($shift['AbsenceId'] != 0){
        $absence = get_absence($shift['AbsenceId']);
        if($absence['Type'] == 0){
            $shift_paid = 0;
        }
    }

    if($shift_paid == 1 & $salary == true){

        if($salary['Type'] == 1){
            $return = $shift_hours * $salary['Rate'];
            $return = $return + $supplementamount . " " . $salary['Currency'];
        }elseif($salary['Type'] == 2){
            $return = _('Monthly pay');
        }

    }

    return $return;

}

// Plangy
function shift_by_id($shiftid){
    global $db;

    $db->where("Id", $shiftid);
    $shift = $db->getOne('schedule');

    return $shift;
}

// Plangy
function user_by_shift($shiftid){
    global $db;

    $db->where("Id", $shiftid);
    $shift = $db->getOne('schedule');

    return $shift;
}

/* PLANGY */
function absence_permission($absenceid){
    global $db;

    $db->where("Id", $absenceid);
    $absence = $db->getOne('absence');

    if($absence['ManagerPermission'] == 1){
        $absence = 1;
    }else{
        $absence = 0;
    }

    return $absence;
}

/* PLANGY */
function get_shift_template($type){
    global $db;

    $users = users_by_department_id($_SESSION['CurrentDepartment']);
    $count_department_active = count_active_department_by_customer($_SESSION['CustomerId']);

    $hourstart      = '09';
    $minutestart    = '00';
    $hourend        = '17';
    $minuteend      = '00';

    $end_by_minutes = ($hourend * 60) + $minuteend;
    $start_by_minutes = ($hourstart * 60) + $minutestart;
    $difference = $end_by_minutes - $start_by_minutes;

    //$format = "%02d:%02d";
    $difference_hours = floor($difference / 60);
    $difference_minutes = ($difference % 60);
    $difference_final = sprintf("%02d",$difference_hours) .":". sprintf("%02d",$difference_minutes);


    $total_difference_hours = floor(($difference) / 60);
    $total_difference_minutes = (($difference) % 60);
    $total_difference_final = sprintf("%02d",$total_difference_hours) .":". sprintf("%02d",$total_difference_minutes);



    $ini_total_shift_value = $difference;
    $ini_total_row_value = $difference;

    $ini_total_shift = $difference_final;
    $ini_total_row = $total_difference_final;

    $shift = "<div class='md-col-3 center'>";
    $shift .= "<div class='time'>";
    $shift .= "<input onclick='this.setSelectionRange(0, this.value.length)' class='hours starthour' name='starthour[]' type='text' max='2' value='" . $hourstart . "' /><span>:</span>";
    $shift .= "<input onclick='this.setSelectionRange(0, this.value.length)' class='minutes startminute' name='startminute[]' type='text' max='2' value='" . $minutestart . "' /></div>";
    $shift .= "<div class='seperator'>-</div>";
    $shift .= "<div class='time'>";
    $shift .= "<input onclick='this.setSelectionRange(0, this.value.length)' class='hours endhour' name='endhour[]' type='text' max='2' value='" . $hourend . "' /><span>:</span>";
    $shift .= "<input onclick='this.setSelectionRange(0, this.value.length)' class='minutes endminute' name='endminute[]' type='text' max='2' value='" . $minuteend . "' />";
    $shift .= "</div>";
    $shift .= "<input type='hidden' name='total_shift' class='total day_single disabled' value='".$ini_total_shift_value."' disabled />";
    $shift .= "</div>";

    if($type == 1){

        $template = "<div class='row new-row'>";
        $template .= "<span class='btn-remove'><img src='assets/img/icon-cancel.png' /></span>";
        $template .= "<div class='item'>";
        $template .= "<div class='md-col-6'>";
        $template .= "<select class='form-control userid center' name='UserId[]'>";
        $template .= "<option value=''>". _('Choose employee') ."</option>";
        $template .= "<option value='open'>". _('Open shift') ."</option>";

        foreach($users as $user){
            if(!empty($user['Title'])){
                $title = title_by_id($user['Title']);
                $template .= "<option value='".$user['Id']."'>".$user['Firstname']." ".$user['Lastname']." - ".$title ."</option>";
            }else{
                $template .= "<option value='".$user['Id']."'>".$user['Firstname']." ".$user['Lastname']."</option>";
            }

        }

        if($count_department_active > 1){

            $users_others = other_users($_SESSION['CustomerId'],$_SESSION['CurrentDepartment']);

            $template .= "<option class='nonselectable' disabled>"._('Lån fra en anden afdeling')."</option>";


            foreach($users_others as $user){
                if(!empty($user['Title'])){
                    $title = title_by_id($user['Title']);
                    $template .= "<option value='".$user['Id']."'>".$user['Firstname']." ".$user['Lastname']." - ".$title ."</option>";
                }else{
                    $template .= "<option value='".$user['Id']."'>".$user['Firstname']." ".$user['Lastname']."</option>";
                }

            }

        }

        $template .= "</select>";
        $template .= "</div>";
        $template .= $shift;
        $template .= "<input type='text' class='total_row disabled' name='total_row' value='" . $ini_total_row . "' disabled />";
        $template .= "<div class='md-col-3 item-actions'>";
        $template .= "<span class='btn-save'><i class='pg-icon-check'></i></span>";
        $template .= "</div>";
        $template .= "</div>";
        $template .= "</div>";

    }


    return $template;
}

/* Plangy | Schedule Update */
function update_schedule($userid,$year){
    global $db;

    $path = $_SERVER["DOCUMENT_ROOT"]."/shifts/";

    $db->where("Id", $userid);
    $user = $db->getOne('users');

    $db->where("UserId", $userid);
    $db->orderBy("Date","asc");
    $schedule = $db->get('schedule');

    $array = Array();

    foreach($schedule as $shift){

        $managerpermission = absence_permission($shift['AbsenceId']);

        $array[] = Array(
            $shift['Date'],
            $shift['Start'],
            $shift['End'],
            $shift['AbsenceId'],
            $shift['Note'],
            $shift['Available'],
            $shift['Approved'],
            $managerpermission,
            $shift['TakenDate'],
            $shift['Id']
        );
    }


    $filename = $user['Id'].$user['Key'];
    $filepath = $path.$year."/".$filename.".json";


    $fp = fopen($filepath, 'w');
    fwrite($fp, json_encode($array));
    fclose($fp);


    return $array;


}

// Plangy | Schedule Setup
function setup_schedule($customerid,$departmentid,$year,$startweek,$endweek,$frequence,$hourstart,$minutestart,$hourend,$minuteend,$userid){

    global $db;

    // Number of weeks
    $number_of_weeks = $endweek - $startweek + 1;

    // Get users

    if($userid != 'all'){
        $db->where("Id", $userid);
    }

    $db->where("CustomerId", $customerid);
    $db->where("DepartmentId", $departmentid);
    $users = $db->get('users');

    $dto = new DateTime();
    $dto->setISODate($year, $startweek);

    $week = $startweek - 1;

    for($i=1; $i<=$frequence; $i++){

        $current_week = $week + $i;

        echo "<div class='row'>";

        //echo "<h2>Week " . $current_week . "</h2>";

        echo "<h2>". _('Week') ." ";
        for ($n = $current_week; $n <= $endweek; $n+=$frequence) {
            echo "<span>" . $n . "</span> ";
        }
        echo " | ". _('Frequence') . " " . $i;
        echo "</h2>";

        for($day=1; $day<=7; $day++){
            if($day == 1){
                echo "<div class='col first'>";
            }else{
                echo "<div class='col'>";
            }


            echo "<h4>" . _($dto->format('l')) . "</h4>";
            //echo $dto->format('d-m-Y') . "<br>";

            $count = 0;

            echo "<div class='week-table'>";
            for ($n = $current_week; $n <= $endweek; $n+=$frequence) {

                $count = $count+1;

                echo "<div class='week'>";
                echo "<em>". _('Week') ." " . $n . "</em>";
                echo "<span>" . $dto->format('d-m-Y') . "</span>";
                echo "</div>";
                $dto->modify('+7 day');
            }
            echo "</div>";

            $backtrack = $count * -7;

            $dto->modify($backtrack . " day");

            $dto->modify('+1 day');

            foreach($users as $user){

                echo "<div class='day'>";

                if($day == 1){
                    echo "<h3>" . $user['Firstname'] . "</h3>";
                }else{
                    echo "<h3 class='hide-md'>" . $user['Firstname'] . "</h3>";
                }

                $validation_hours = "maxlength='2' min='0' data-parsley-min='0' max='24' data-parsley-max='24' data-parsley-trigger='focusout'";
                $validation_minutes = "maxlength='2' min='0' data-parsley-min='0' max='60' data-parsley-max='60' data-parsley-trigger='focusout'";

                echo "<div class='time'><strong>"._('Start')."</strong>";
                echo "<input class='hours' name='starthour_".$user['CustomerId']."_".$user['Id']."_".$day."_".$current_week."_".$year."_".$frequence."_".$number_of_weeks."_".$departmentid."' type='text' value='" . $hourstart . "' ". $validation_hours ." /><span>:</span>";
                echo "<input class='minutes' name='startminute_".$user['CustomerId']."_".$user['Id']."_".$day."_".$current_week."_".$year."_".$frequence."_".$number_of_weeks."_".$departmentid."' type='text' value='" . $minutestart . "' ". $validation_minutes ." /></div>";

                echo "<div class='time'><strong>"._('End')."</strong>";
                echo "<input class='hours' name='endhour_".$user['CustomerId']."_".$user['Id']."_".$day."_".$current_week."_".$year."_".$frequence."_".$number_of_weeks."_".$departmentid."' type='text' value='" . $hourend . "' ". $validation_hours ." /><span>:</span>";
                echo "<input class='minutes' name='endminute_".$user['CustomerId']."_".$user['Id']."_".$day."_".$current_week."_".$year."_".$frequence."_".$number_of_weeks."_".$departmentid."' type='text' value='" . $minuteend . "' ". $validation_minutes ." /></div>";

                echo "</div>";

            }

            echo "</div>";

        }

        echo "</div>";

    }

    return;

}

// Plangy | Department with Status = 1
function department_by_customer_id($id){
    global $db;

    $db->where("CustomerId", $id);
    $db->where("Type", 1);
    $departments = $db->get('departments');

    $department_by_id = array();

    for ($i = 0; $i < count($departments); $i++) {

        $array_key_tmp = array_keys($departments[$i]);

        $department_by_id[$departments[$i]["Id"]] = array();

        for ($j = 0; $j < count($array_key_tmp); $j++) {

            $department_by_id[$departments[$i]["Id"]][$array_key_tmp[$j]] = $departments[$i][$array_key_tmp[$j]];

        }

    }
    return $department_by_id;
}

// Plangy
function titles_by_department_id($customerid,$departmentid){
    global $db;

    $db->where("CustomerId", $customerid);
    $db->where("DepartmentId", 0);
    $titles_global = $db->get('titles');

    $db->where("CustomerId", $customerid);
    $db->where("DepartmentId", $departmentid);
    $titles_department = $db->get('titles');

    $array = array_merge($titles_global,$titles_department);

    return $array;
}

// Plangy
function title_by_id($titleid){
    global $db;

    $db->where("Id", $titleid);
    $title = $db->getOne('titles');

    return $title['Title'];
}

// Plangy
function get_salaryrates($customerid,$departmentid){
    global $db;

    $db->where("CustomerId", $customerid);
    $db->where("DepartmentId", 0);
    $salaryrate_all = $db->get('salaryrates');

    $db->where("CustomerId", $customerid);
    $db->where("DepartmentId", $departmentid);
    $salaryrate_specific = $db->get('salaryrates');

    $salaryrate = array_merge($salaryrate_all,$salaryrate_specific);

    return $salaryrate;
}

// Plangy
function salaryrate_by_id($salaryrateid){
    global $db;

    $db->where("Id", $salaryrateid);
    $salaryrate = $db->getOne('salaryrates');

    return $salaryrate;
}

// Plangy
function salarysupplementrate_by_id($salaryrateid){
    global $db;

    $db->where("Id", $salaryrateid);
    $salaryrate = $db->getOne('salarysupplementrates');

    return $salaryrate;
}

// Plangy
function salarysupplementrule_by_supplementid($salaryrateid){
    global $db;

    $db->where("SupplementId", $salaryrateid);
    $salaryrule = $db->getOne('salarysupplementrules');

    return $salaryrule;
}

// Plangy
function salarysupplementrates($customerid,$departmentid,$userid,$start,$end){
    global $db;

    $day = date('N', strtotime($start));
    $year = date('Y', strtotime($start));
    $timefrom = substr($start,-8,8);
    $timeto = substr($end,-8,8);
    $datefrom = substr($start,0,10);
    $dateto = substr($start,0,10);

    $shift_hours = convert_hours($start,$end);

    $user = user_data_by_id($userid);



    $db->where('Active',1);
    $db->where('CustomerId',$customerid);
    $db->where('EmployeeGroup',0);
    $db->where('DepartmentId',0,'=','OR');
    $db->where('DepartmentId',$departmentid);
    $supplementrates_all = $db->get('salarysupplementrates');



    $db->where('Active',1);
    $db->where('CustomerId',$customerid);
    $db->where('EmployeeGroup',$user['Title']);
    $db->where('DepartmentId',0,'=','OR');
    $db->where('DepartmentId',$departmentid);
    $supplementrates_specific = $db->get('salarysupplementrates');

    $supplementrates = array_merge($supplementrates_specific,$supplementrates_all);

    $supplement_return = array();
    $supplement_total = 0;

    foreach($supplementrates as $supplementrate){

        $supplementrate_ref = array();

        $db->where('SupplementId',$supplementrate['Id']);
        $rule = $db->getOne('salarysupplementrules');


        $applicable = 1;
        $supplement_pay = 0;

        $overlap = 0;

        if($supplementrate['Type'] == 1){

            if($rule != NULL){

                $supplement_pay = $shift_hours * $supplementrate['Rate'];

                // Run rules
                if($rule['DateFrom'] != '0000-00-00' && $rule['DateTo'] != '0000-00-00'){
                    if($datefrom < $rule['DateFrom'] OR $datefrom > $rule['DateTo']){
                        $applicable = 0;
                    }
                }

                if($rule['Weekdays'] != '{0,0,0,0,0,0,0}'){

                    $rule_weekdays = substr($rule['Weekdays'],1,13);
                    $rule_weekdays = explode(',',$rule_weekdays);

                    $index = $day -1;

                    if($rule_weekdays[$index] == 0){
                        $applicable = 0;
                    }


                }

                if($rule['Holidays'] != '0'){

                    $holidays_observances = get_holidays_observances($year);

                    $array_holidays = 0;

                    if(!empty($holidays_observances)){

                        $array_holidays = $holidays_observances['Holidays'];

                    }

                    if(empty($array_holidays[$datefrom])){
                        $applicable = 0;
                    }

                }

                $overlap = 0;

                if($applicable == 1){

                    if($rule['TimeFrom'] != '00:00:00' && $rule['TimeTo'] != '00:00:00'){

                        $lastStart = $timefrom >= $rule['TimeFrom'] ? $timefrom : $rule['TimeFrom'];
                        $lastStart = strtotime($lastStart);

                        $firstEnd = $timeto <= $rule['TimeTo'] ? $timeto : $rule['TimeTo'];
                        $firstEnd = strtotime($firstEnd);

                        $overlap = floor( ($firstEnd - $lastStart) / 60 );

                        if($overlap > 0){

                            $supplement_pay = $overlap * ($supplementrate['Rate']/60);

                        }

                    }else{
                        $supplement_pay = $shift_hours * $supplementrate['Rate'];
                    }

                }else{
                    $supplement_pay = 0;
                }


            }else{
                $supplement_pay = $shift_hours * $supplementrate['Rate'];
            }

            $supplementrate_ref['Name'] = $supplementrate['Title'];
            $supplementrate_ref['Time'] = $overlap / 60;
            $supplementrate_ref['Amount'] = $supplement_pay;
            $supplementrate_ref['Currency'] = $supplementrate['Currency'];
            $supplement_total = $supplement_total + $supplement_pay;

        }

        $supplement_return[$supplementrate['Id']] = $supplementrate_ref;

    }

    $supplement_return['Total Amount'] = $supplement_total;

    return $supplement_return;
}

// Plangy
function user_data_by_id($userid){
    global $db;

    $db->where("Id", $userid);
    $user = $db->getOne('users');

    return $user;
}

// Plangy
function shift_today($userid,$date){
    global $db;

    $db->where("UserId", $userid);
    $db->where("Date", $date);
    $shift = $db->getOne('schedule');

    return $shift;
}

// Plangy
function single_shift_data($userid, $date, $departmentid){
    global $db;

    $db->where("UserId", $userid);
    $db->where("Date", $date);
    $db->where("DepartmentId", $departmentid);
    $shift = $db->getOne('schedule');

    return $shift;
}

// Plangy
function single_shift_data_open($departmentid, $shiftid){
    global $db;

    $db->where("Id", $shiftid);
    $db->where("DepartmentId", $departmentid);
    $shift = $db->getOne('schedule');

    return $shift;
}

// Plangy
function count_user_by_department($departmentid){
    global $db;

    $db->where("DepartmentId", $departmentid);
    $users = $db->get('users');

    $count = count($users);

    return $count;
}

// Plangy
function count_user_by_title($departmentid, $titleid){
    global $db;

    if($departmentid == 0){
        $db->where("CustomerId", $_SESSION['CustomerId']);
    }else{
        $db->where("DepartmentId", $departmentid);
    }

    $db->where("Title", $titleid);
    $users = $db->get('users');

    $count = count($users);

    return $count;
}

// Plangy
function count_department_by_customer($customerid){
    global $db;

    $db->where("CustomerId", $customerid);
    $departments = $db->get('departments');

    $count = count($departments);

    return $count;
}

// Plangy
function count_active_department_by_customer($customerid){
    global $db;

    $db->where("CustomerId", $customerid);
    $db->where("Active", 1);
    $departments = $db->get('departments');

    $count = count($departments);

    return $count;
}

// Plangy
function convert_date($date,$country){

    if($country == 'Denmark'){
        $date = date("d/m/Y", strtotime($date));
    }

    return $date;
}

// OPTY
function convertDateTime($date,$country){

    if($country == 'Denmark'){
        $date = date("d/m/Y H:i:s", strtotime($date));
    }else{
        $date = $date;
    }

    return $date;
}

// Plangy
function convert_date_pretty($date,$country){

    if($country == 'Denmark'){
        $date_day = date("l", strtotime($date));
        $date_day_date = date("d", strtotime($date));
        $date_month = date("F", strtotime($date));
        $date_year = date("Y", strtotime($date));

        $date = _($date_day) . " - " . $date_day_date . ". " . _($date_month) . " " . $date_year;
    }

    return $date;
}

// Plangy
function is_leaping($year){

    $start_year = $year - 10;
    $end_year = $year + 10;

    $array_leaping = array();
    $array_leaping_next = array();

    for($i = $start_year; $i <= $end_year; $i++){

        $lastday_year = date('Y-m-t', strtotime($i."-12"));
        $weeks = date('W', strtotime($lastday_year));


        if($weeks == 53){
            $array_leaping[] = $i;
            $array_leaping_next[] = $i + 1;
        }

    }

    $isleaping = 0;
    $isleapingnext = 0;

    if(in_array($year,$array_leaping)){
        $isleaping = 1;
    }

    if(in_array($year,$array_leaping_next)){
        $isleapingnext = 1;
    }

    /*$array = array(
        "Leaping" => $array_leaping,
        "Leaping Next" => $array_leaping_next
    );*/

    $array = array(
        "Leaping" => $isleaping,
        "Leaping Next" => $isleapingnext
    );

    return $array;


}

function year_shifting_rules($year,$month,$startweek,$endweek){

    $loop = 1;

    if($month == 1){
        if($startweek > $endweek){
            $loop = 2;
        }else{
            $loop = 1;
        }
    }elseif($month == 12){
        if($startweek > $endweek){
            $loop = 2;
        }else{
            $loop = 1;
        }
    }

    return $loop;

}

function last_year_weeks($year){

    $prev_year = $year - 1;
    $last_week = date('W', strtotime($prev_year . '-12-31'));

    if($last_week == 1){
        $last_week = date('W', strtotime($prev_year . '-12-24'));
    }

    return $last_week;

}

// Plangy
function convert_day($day){

    if($day == 1){
        $day = _('Monday');
    }elseif($day == 2){
        $day = _('Tuesday');
    }elseif($day == 3){
        $day = _('Wednesday');
    }elseif($day == 4){
        $day = _('Thursday');
    }elseif($day == 5){
        $day = _('Friday');
    }elseif($day == 6){
        $day = _('Saturday');
    }elseif($day == 7){
        $day = _('Sunday');
    }

    return $day;
}

// Plangy
function convert_day_short($day){

    if($day == 1){
        $day = _('Mon');
    }elseif($day == 2){
        $day = _('Tue');
    }elseif($day == 3){
        $day = _('Wed');
    }elseif($day == 4){
        $day = _('Thu');
    }elseif($day == 5){
        $day = _('Fri');
    }elseif($day == 6){
        $day = _('Sat');
    }elseif($day == 7){
        $day = _('Sun');
    }

    return $day;
}


// Plangy
function convert_month($month){

    if($month == 1){
        $month = _('January');
    }elseif($month == 2){
        $month = _('February');
    }elseif($month == 3){
        $month = _('Marts');
    }elseif($month == 4){
        $month = _('April');
    }elseif($month == 5){
        $month = _('May');
    }elseif($month == 6){
        $month = _('June');
    }elseif($month == 7){
        $month = _('July');
    }elseif($month == 8){
        $month = _('August');
    }elseif($month == 9){
        $month = _('September');
    }elseif($month == 10){
        $month = _('October');
    }elseif($month == 11){
        $month = _('November');
    }elseif($month == 12){
        $month = _('December');
    }

    return $month;
}




function get_holidays_observances($year){

    $data = 0;

    $setting_holidays_lang = 'da_DK';

    $path = $_SERVER["DOCUMENT_ROOT"]."/holidays/".$setting_holidays_lang ."/";
    $filepath = $path . $year . '.json';

    if (file_exists($filepath)) {

        $content = file_get_contents($filepath);

        $content_explode = explode("]]", $content);
        $content_striped = str_replace("[[","",$content_explode);
        $content_raw = str_replace('"','',$content_striped);

        $array_holidays = array();
        $array_observances = array();
        foreach($content_raw as $content){

            //$data = array_slice($data, 0, 2);


            //$data_date = preg_replace('/\s+/', '',$data[0]);

            if(!empty($content)){
                $data = explode(',', $content);

                $data_keys = array('Date','Name','Type');
                $data = array_combine($data_keys, $data);

                $data_date = preg_replace('/\s+/', '',$data['Date']);
                $data_name = $data['Name'];
                $data_type = $data['Type'];

                if($data_type == 1){
                // Holidays

                    $array_holidays[$data_date] = $data_name;

                }elseif($data_type == 2){
                // Observances

                    $array_observances[$data_date] = $data_name;

                }

            }

        }

        if($_SESSION['HolidaysSetting'] == 0){
            $array_holidays = 0;
        }

        if($_SESSION['ObservancesSetting'] == 0){
            $array_observances = 0;
        }

        $data = array(
            "Holidays" => $array_holidays,
            "Observances" => $array_observances
        );

    }

    return $data;


}

function searcharray($list, $field, $value){

    $array = array();

    foreach($list as $key => $item)
    {
        if($item[$field] == $value)

            $array[] = $item;
    }

    return $array;
}

function overtime_check($userid, $year, $week){
    global $db;

    $db->where('UserId',$userid);
    $db->where('Year',$year);
    $db->where('Week',$week);
    $overtime = $db->getOne('overtime');

    return $overtime;

}


function products_with_id_as_key(){
    global $db;
    $products_select = $db->get('products');

    $products_with_id_as_key = array();

    for ($i = 0; $i < count($products_select); $i++) {

        $array_key_tmp = array_keys($products_select[$i]);

        $products_with_id_as_key[$products_select[$i]["Id"]] = array();

        for ($j = 0; $j < count($array_key_tmp); $j++) {

            $products_with_id_as_key[$products_select[$i]["Id"]][$array_key_tmp[$j]] = $products_select[$i][$array_key_tmp[$j]];

        }

    }
    return $products_with_id_as_key;
}


function devicesbrands_with_id_as_key(){
    global $db;
    $devicesbrands_select = $db->get('devicebrands');

    $devicesbrands_with_id_as_key = array();

    for ($i = 0; $i < count($devicesbrands_select); $i++) {

        $array_key_tmp = array_keys($devicesbrands_select[$i]);

        $devicesbrands_with_id_as_key[$devicesbrands_select[$i]["Id"]] = array();

        for ($j = 0; $j < count($array_key_tmp); $j++) {

            $devicesbrands_with_id_as_key[$devicesbrands_select[$i]["Id"]][$array_key_tmp[$j]] = $devicesbrands_select[$i][$array_key_tmp[$j]];

        }

    }
    return $devicesbrands_with_id_as_key;
}



function categories_with_id_as_key(){
    global $db;
    $categories_select = $db->get('productcategories');

    $categories_with_id_as_key = array();

    for ($i = 0; $i < count($categories_select); $i++) {

        $array_key_tmp = array_keys($categories_select[$i]);

        $categories_with_id_as_key[$categories_select[$i]["Id"]] = array();

        for ($j = 0; $j < count($array_key_tmp); $j++) {

            $categories_with_id_as_key[$categories_select[$i]["Id"]][$array_key_tmp[$j]] = $categories_select[$i][$array_key_tmp[$j]];

        }

    }
    return $categories_with_id_as_key;
}


function users_with_id_as_key(){
    global $db;
    $users_select = $db->get('users');

    $users_with_id_as_key = array();

    for ($i = 0; $i < count($users_select); $i++) {

        $array_key_tmp = array_keys($users_select[$i]);

        $users_with_id_as_key[$users_select[$i]["Id"]] = array();

        for ($j = 0; $j < count($array_key_tmp); $j++) {

            $users_with_id_as_key[$users_select[$i]["Id"]][$array_key_tmp[$j]] = $users_select[$i][$array_key_tmp[$j]];

        }

    }
    return $users_with_id_as_key;
}

function cms_with_id_as_key(){
    global $db;
    $cms_select = $db->get('cms');

    $cms_with_id_as_key = array();

    for ($i = 0; $i < count($cms_select); $i++) {

        $array_key_tmp = array_keys($cms_select[$i]);

        $cms_with_id_as_key[$cms_select[$i]["Id"]] = array();

        for ($j = 0; $j < count($array_key_tmp); $j++) {

            $cms_with_id_as_key[$cms_select[$i]["Id"]][$array_key_tmp[$j]] = $cms_select[$i][$array_key_tmp[$j]];

        }

    }
    return $cms_with_id_as_key;
}

function mails_with_id_as_key(){
    global $db;
    $mails_select = $db->get('mails');

    $mails_with_id_as_key = array();

    for ($i = 0; $i < count($mails_select); $i++) {

        $array_key_tmp = array_keys($mails_select[$i]);

        $mails_with_id_as_key[$mails_select[$i]["Id"]] = array();

        for ($j = 0; $j < count($array_key_tmp); $j++) {

            $mails_with_id_as_key[$mails_select[$i]["Id"]][$array_key_tmp[$j]] = $mails_select[$i][$array_key_tmp[$j]];

        }

    }
    return $mails_with_id_as_key;
}


function rep_chosen_with_id_as_key(){
    global $db;

    $rep_chosen_select = $db->get('reparations_chosen');

    $rep_chosen_with_id_as_key = array();

    for ($i = 0; $i < count($rep_chosen_select); $i++) {

        $array_key_tmp = array_keys($rep_chosen_select[$i]);

        $rep_chosen_with_id_as_key[$rep_chosen_select[$i]["reparationid"]] = array();

        for ($j = 0; $j < count($array_key_tmp); $j++) {

            $rep_chosen_with_id_as_key[$rep_chosen_select[$i]["reparationid"]][$array_key_tmp[$j]] = $rep_chosen_select[$i][$array_key_tmp[$j]];

        }

    } // wass class
    return $rep_chosen_with_id_as_key;
}

function rep_type_with_id_as_key(){
    global $db;

    $rep_type_select = $db->get('reparationtype');

    $rep_type_with_id_as_key = array();

    for ($i = 0; $i < count($rep_type_select); $i++) {

        $array_key_tmp = array_keys($rep_type_select[$i]);

        $rep_type_with_id_as_key[$rep_type_select[$i]["Id"]] = array();

        for ($j = 0; $j < count($array_key_tmp); $j++) {

            $rep_type_with_id_as_key[$rep_type_select[$i]["Id"]][$array_key_tmp[$j]] = $rep_type_select[$i][$array_key_tmp[$j]];

        }

    } // wass class

    return $rep_type_with_id_as_key;
}


function order_chosen_with_id_as_key(){
    global $db;

    $order_chosen_select = $db->get('orders_chosen');

    $order_chosen_with_id_as_key = array();

    for ($i = 0; $i < count($order_chosen_select); $i++) {

        $array_key_tmp = array_keys($order_chosen_select[$i]);

        $order_chosen_with_id_as_key[$order_chosen_select[$i]["OrderID"]] = array();

        for ($j = 0; $j < count($array_key_tmp); $j++) {

            $order_chosen_with_id_as_key[$order_chosen_select[$i]["OrderID"]][$array_key_tmp[$j]] = $order_chosen_select[$i][$array_key_tmp[$j]];

        }

    } // wass class
    return $order_chosen_with_id_as_key;
}


function categorytitlefromcategoryid($categoryid){

    $categories_with_id_as_key = categories_with_id_as_key();
    $categorytitle = $categories_with_id_as_key[$categoryid]['Title'];

    return $categorytitle;

}

function attributegrouptitleandattributetitlefromattributeid($id)
{
    global $db;
    $db->where("Id", $id);
    $attrtitle = $db->getOne('productattributes');
    $attrgroupid    = $attrtitle['AttributeGroupID'];
    $attributetitle = $attrtitle['Title'];
    $db->where("Id", $attrgroupid);
    $attrtitle = $db->getOne('productattributegroups');
    $attrgrouptitle    = $attrtitle['Title'];
    $returntitle = "<span>";
    $returntitle .= $attrgrouptitle . ": <em>";
    $returntitle .= $attributetitle;
    $returntitle .= "</em></span>";

    return $returntitle;
}





function getproductfromproductid($id)
{
    global $db;
    $db->where("Id", $id);
    $dbinfo = $db->getOne('products');
    $model    = $dbinfo['Title'];
    return $model;
}

function getproductimagefromproductid($id)
{
    global $db;
    $db->where("Id", $id);
    $dbinfo = $db->getOne('products');
    $image    = $dbinfo['img1'];
    return $image;
}


function gettotalorder($orderid){

        // status 0 success, status 1 fail.
    $db = MysqliDb::getInstance();

    $db->where("OrderID", $orderid);
    $allreparations = $db->get('orders_chosen');
        //$deviceid = getproductfromproductid($reparationid); //model id
    $totalprice = 0;
    $reperationtitles = "";
    foreach ($allreparations as $allreparations) {
            # code...

        $products_with_id_as_key = products_with_id_as_key();

        $totalprice+= $allreparations['combinedprice'];

        $ProductID      = $allreparations['ProductID'];
        $Quantity       = $allreparations['qty'];
        $UnitPrice      = $allreparations['unitprice'];
        $CombinedPrice  = $allreparations['combinedprice'];
        $UID            = $allreparations['uid'];

        $ProductTitle   = getproductfromproductid($ProductID);
        $ProductImage   = getproductimagefromproductid($ProductID);
            //$UIDTitles      = gettitelfromuid($UID);

        $attribute_picture = attribute_picture($UID);
        $the_picture = "img".$attribute_picture;

        $productthumbnail = $products_with_id_as_key[$ProductID][$the_picture];


        if($Quantity == '0'){
            $hide = "hide";
        }else{
            $hide = "";
        }


        $orderitems = "<li class='".$hide."'>";
            //$orderitems .= "<div><img src='/uploads/productimages/".$ProductID."/".$productthumbnail."' /></div>";
        $orderitems .= "<div class='vam'><strong>".$ProductTitle."</strong><em></em></div>";
        $orderitems .= "<div class='vam'>".$Quantity."</div>";
        $orderitems .= "<div class='vam'>".$UnitPrice." kr</div>";
        $orderitems .= "<div class='vam right'>".$CombinedPrice." kr</div>";
        $orderitems .= "</li>";

    }

    $orderitems .= "<li><div class='total sub'>Moms: 0 kr</div></li>";
    $orderitems .= "<li><div class='total'>Total: " . $totalprice . " kr</div></li>";

    return $orderitems;
}


function getattributepicture($singleuid)
{
    global $db;
    $db->where("ProductAttributeId", $singleuid);
    $attributeinfo = $db->getOne('productvariations');
    //$picture    = $attributeinfo['FrontId'];
    $picture    = "1";
// echo $picture;
    return $picture;
}

function attribute_picture($uid){

    $attributes = attributeidsfromuid($uid);
$picture = ""; // we should do an array for multiple variations pictures :-)

//d($attributes);
foreach ($attributes as $attribute) {
    //d($attribute);
    $picturex = getattributepicture($attribute);
   // echo $picturex;
    if(!empty($picturex)){
        $picture = $picturex;
    }
}

return $picture;
}

function repairslist(){


    global $db;
// Get all needed info in one request
//$db->where('id', $xxx, 'IN');  // this would optimize the array of info BUT we need all xxx ids anyway soo.... better 1 query :D


/*
$brands = $db->get('devicebrands');

$brands_with_id_as_key = array();

for ($i = 0; $i < count($brands); $i++) {

    $array_key_tmp = array_keys($brands[$i]);

    $brands_with_id_as_key[$brands[$i]["Id"]] = array();

    for ($j = 0; $j < count($array_key_tmp); $j++) {

        $brands_with_id_as_key[$brands[$i]["Id"]][$array_key_tmp[$j]] = $brands[$i][$array_key_tmp[$j]];

    }

}
*/


$devices_with_id_as_key = devices_with_id_as_key();

$rep_chosen_with_id_as_key = rep_chosen_with_id_as_key();

$rep_type_with_id_as_key = rep_type_with_id_as_key();




$db->join("reparations p", "p.userid=u.Id", "LEFT");
//$db->where("p.Id", $reservationid);
//$reparations = $db->get ("reparations p", null, "p. Id");
$reparations = $db->get ("customers u");



//$db->join('t2','t2.id = t1.id','inner')->get("t1",null, Array ("t1.name as t1_name", "t2.name as t2_name"));
//
$db->join("deviceoperations p", "c.reparationstypeid=p.Id", "LEFT");

$all_reps_chosen_ever = $db->get('reparations_chosen c');
//d($all_reps_chosen_ever);

$grouped = array_group_by( $all_reps_chosen_ever, "reparationid" );

//d($grouped);
//
//

//ddd($reparations);
//$reparations = $db->get('reparations');

//var_dump($bookings);

//$brand_ids = array();

//$models_ids = array();

//ddd($reparations);
//
//

//$all_reps_chosen_ever = $db->get('reparations_chosen');
//$grouped = array_group_by( $all_reps_chosen_ever, "reparationid" );

foreach ($reparations as $item) {


    if(!empty($item['Id'])){



   // $brand_ids[] = $item["brand"];

   // $models_ids[] = $item["models"];  //

        $customeridlink = $item['Id'];
        $id = $item['Id'];
        $Price = 0;
        if(isset($grouped[$id])){
            foreach ($grouped[$id] as $pricecalculation) {
                if(isset($pricecalculation['Price'])){
                    $Price = $Price + $pricecalculation['Price'];
                }
            }
         //       $grouped[$id]['array']['Price'];
        }            $type = $item['type'];
        $firstname = $item['firstname'];
        $lastname = $item['lastname'];
        $fullname = "$firstname $lastname";
        $phonenumber = $item['phonenumber'];
        $address = $item['address'];
        $zipcode = $item['zipcode'];
        $city = $item['city'];
        $email = $item['email'];
        $company = $item['company'];
        $vat_number = $item['vat_number'];
        $invoice_email = $item['invoice_email'];
        $ean_number = $item['ean_number'];
        $brand = $item['brand'];
        $models = $item['models'];
        $dato = danishdateandtime($item['CreateDate']);
        $status = $item['status'];
        $statusid = $item['status'];
        $status = statusidtext($status);


        if(isset($rep_chosen_with_id_as_key[$id])){
            $allreparations = $rep_chosen_with_id_as_key[$id];
        }else{
            $allreparations ="";
        }
         //   $allreparations = $rep_chosen_with_id_as_key[$id];


        $reperationtitles = "";
                 //   $allbookingsrep = $grouped;
        if(isset($grouped[$id])){

            $thisrepsreps = $grouped[$id];
        }else{
            $thisrepsreps = [];
        }

                           //     d($thisrepsreps);
        foreach ($thisrepsreps as $thisrepsreps) {
         //               d($allreparations);exit;
            $idx = $thisrepsreps['reparationstypeid'];
                //        d($idx);
             //           exit;
            if($idx){
                if(isset($rep_type_with_id_as_key[$idx]['Title'])){

                    $reperationtitles .= $rep_type_with_id_as_key[$idx]['Title'];
                    $reperationtitles .= ", ";
                }
            }

        }




        $reperations = $reperationtitles;








            //$reperations = 0;
        if($type == 1){
            $type = "Privat";
        }elseif($type == 2){
            $type = "Erhverv";
        }elseif($type == 3){
            $type = "Organisation";
        }else{
            $type = "Ugyldig!";
        }


            //d($devices_with_id_as_key[$models]['Title']);
        if($models !== '0'){
            $models = $devices_with_id_as_key[$models]['Title'];
        }

            //$models =
            //d($devices_with_id_as_key[$models]['Title']);
          //  echo "<br> ID: $id <br> TYPE: $type  <br> Model: $models <br> Rep.: $reperations  <br> Pris: $Price <br> Dato: $dato <br> Status: $status <br> <br> <br> <a href='kundeinformation/$id'>Kunde informationer her </a> <br><br><br>";

        $statusid = intval($statusid);
        if($statusid !== 8){
            $nextstatusid = $statusid + 1;
        }else{
            $nextstatusid = $statusid;
        }
        $nextstatustext = statusidtext($nextstatusid);
        echo "
        <tr>
        <td><a href='/kundeinformation/$id' class='list-item'>
                        #$id</a></td>

        <td>$type</td>
        <td>$fullname</td>
        <td>$models</td>
        <td>$reperations</td>
        <td>$Price,-</td>
        <td>$dato</td>
        <td id='currentstatusforid$id'>$status</td>

        <td class='actions'>
        <a href='#' class='updateclass' data-status='$statusid' data-id='$id'><span class='fa fa-check'></span>$nextstatustext</a>
        </td>
        </tr>
        ";





        /** end more admin * */

/*
// hvis vi skal lave en dropdown, hvor den rigtige allerede er markeret
    for ($i = 0; $i < count($classes_select); $i++) {

        echo "<option value='" . $classes_select[$i]['id'] . "' ";

        if ($classes_select[$i]['id'] == $item['class']) {

            echo " selected";

        }

        echo ">" . $classes_select[$i]['name'] . "</option>";

    }
*/
}


}


}




function admindashboardcontentv3(){


    global $db;

    $devices_with_id_as_key = devices_with_id_as_key();

    $rep_chosen_with_id_as_key = rep_chosen_with_id_as_key();

    $rep_type_with_id_as_key = rep_type_with_id_as_key();




    $db->join("reparations p", "p.userid=u.Id", "LEFT");

    $reparations = $db->get ("customers u");



//$db->join('t2','t2.id = t1.id','inner')->get("t1",null, Array ("t1.name as t1_name", "t2.name as t2_name"));
//
    $db->join("deviceoperations p", "c.reparationstypeid=p.Id", "LEFT");

    $all_reps_chosen_ever = $db->get('reparations_chosen c');
//d($all_reps_chosen_ever);

    $grouped = array_group_by( $all_reps_chosen_ever, "reparationid" );

//d($grouped);

    foreach ($reparations as $item) {


        if(!empty($item['Id'])){


            $customeridlink = $item['Id'];
            $id = $item['Id'];
            $Price = 0;
            if(isset($grouped[$id])){
                foreach ($grouped[$id] as $pricecalculation) {
                  $Price = $Price + $pricecalculation['Price'];
              }
         //       $grouped[$id]['array']['Price'];
          }

          $type = $item['type'];
          $firstname = $item['firstname'];
          $lastname = $item['lastname'];
          $fullname = "$firstname $lastname";
          $phonenumber = $item['phonenumber'];
          $address = $item['address'];
          $zipcode = $item['zipcode'];
          $city = $item['city'];
          $email = $item['email'];
          $company = $item['company'];
          $vat_number = $item['vat_number'];
          $invoice_email = $item['invoice_email'];
          $ean_number = $item['ean_number'];
          $brand = $item['brand'];
          $models = $item['models'];
          $dato = danishdateandtime($item['CreateDate']);
          $status = $item['status'];
          $statusid = $item['status'];
          $status = statusidtext($status);



          if(isset($rep_chosen_with_id_as_key[$id])){
            $allreparations = $rep_chosen_with_id_as_key[$id];
        }else{
            $allreparations ="";
        }



        $reperationtitles = "";


        if(isset($grouped[$id])){

            $thisrepsreps = $grouped[$id];
        }else{
            $thisrepsreps = [];
        }

        foreach ($thisrepsreps as $thisrepsreps) {

            $idx = $thisrepsreps['reparationstypeid'];

            if($idx){
                if(isset($rep_type_with_id_as_key[$idx]['Title'])){

                    $reperationtitles .= $rep_type_with_id_as_key[$idx]['Title'];
                    $reperationtitles .= ", ";
                }
            }

        }




        $reperations = $reperationtitles;


            //$reperations = 0;
        if($type == 1){
            $type = "Privat";
        }elseif($type == 2){
            $type = "Erhverv";
        }elseif($type == 3){
            $type = "Organisation";
        }else{
            $type = "Ugyldig!";
        }


            //d($devices_with_id_as_key[$models]['Title']);
        if($models !== '0'){
            $models = $devices_with_id_as_key[$models]['Title'];
        }


        $statusid = intval($statusid);
        if($statusid !== 8){
            $nextstatusid = $statusid + 1;
        }else{
            $nextstatusid = $statusid;
        }
        $nextstatustext = statusidtext($nextstatusid);
        echo "
        <tr>
        <td><a href='/kundeinformation/$id' class='list-item'>
                #$id</a></td>
        <td><span>$fullname</span><em>$type</em></td>
        <td><span>$models</span><em>$reperations</em></td>
        <td>$Price,-</td>
        <td id='currentstatusforid$id'>$status <a href='#' class='updateclass' data-status='$statusid' data-id='$id'><span class='fa fa-angle-double-right'></span></a></td>
        </tr>
        ";


    }


}


}

/*function getproduct($productid){
    global $db;
    $db->join("products c", "p.Id=c.Id", "LEFT");
    $db->where("p.Id", $productid);
    $productdatainfo = $db->getOne("products p");
    return $productdatainfo;
}


function getproductdata($productid){
    global $db;
    $db->join("productcategories c", "p.ProductCategoryID=c.Id", "LEFT");
    $db->where("p.Id", $productid);
    $productdatainfo = $db->getOne("products p");
    return $productdatainfo;
}*/

function getproductattributedata($productid){
    global $db;
    $db->join("productattributes c", "p.ProductAttributeID=c.Id", "LEFT");
    $db->where("p.Id", $productid);
    $db->groupBy ("c.AttributeGroupID");
    $productattributes = $db->getOne("productvariations p");
    return $productattributes;
}



function getcategoryproducts($categoryid, $pageid){
    global $db;
    $db->where("ProductCategoryID", $categoryid);

    $db->pageLimit = 5; // set page limit to 2 results per page. 20 by default
    $products = $db->arraybuilder()->paginate("products", $page);
   // echo "showing $page out of " . $db->totalPages;
    return $products;
}

function getorderdetails($orderid){

    global $db;
    $db->where("Id", $orderid);
    $orderinfo = $db->getOne("orders");

    return $orderinfo;


}

function getallordersforcustomer(){

    global $db;
  //  global $_SESSION;
    $customerid = $_SESSION['CustomerId'];
   // d($customerid);
    $db->where("CustomerID", $customerid);
    $orderinfo = $db->get("orders");
   // d($orderinfo);
    return $orderinfo;


}

function getallcustomers(){

    global $db;
    $orderinfo = $db->get("customers");

    return $orderinfo;

}

function getallorders(){

    global $db;
    $orderinfo = $db->get("orders");
    $csvarray = array();
    $ii = 0; //counter csv lines.

    $productattributes_with_id_as_key = productattributes_with_id_as_key();
 //   $productattributegroups_with_id_as_key = productattributegroups_with_id_as_key();

    foreach ($orderinfo as $orders) {


        $db->where("OrderID", $orders['Id']);
        $orderdetails = $db->get("orders_chosen");
        foreach ($orderdetails as $orderdetails) {
            $uid = $orderdetails['uid'];
            $productids = attributeidsfromuid($uid);
            $csvarray[$ii]['CustomerID'] = $orders['CustomerID'];
            $csvarray[$ii]['firstname'] = $orders['firstname'];
            $csvarray[$ii]['lastname'] = $orders['lastname'];
            $csvarray[$ii]['phonenumber'] = $orders['phonenumber'];
            $csvarray[$ii]['city'] = $orders['city'];
            $csvarray[$ii]['address'] = $orders['address'];
            $csvarray[$ii]['CustomerID'] = $orders['CustomerID'];
                    //$csvarray[$ii]['uid'] = $uid;
            $csvarray[$ii]['shippingmethod'] = shippingmethodtext(shippingmethodidfromproductid($orderdetails['ProductID']));
            $productdata = getproduct($orderdetails['ProductID']);
            $finaltitleforcolumn = $productdata['Title'];
            foreach ($productids as $productids) {

                if($productids != null){
                        $AttributeTitle = $productattributes_with_id_as_key[$productids]['Title']; //$productids;
                        $finaltitleforcolumn .= " - " . $AttributeTitle;
                    }


                }
                $csvarray[$ii]['producttitle'] = $finaltitleforcolumn;

                $ii++;


            }




        }

        return $csvarray;

   // return $orderinfo;

    }


    function customerdashboarddetails(){


        $allorders = getallordersforcustomer();


        foreach ($allorders as $allorders) {

            //echo "order info: ";
            //d($allorders);

            //echo "chosen items:";
            //echo $allorders[''];


            $chosenitems = getorderschosen($allorders['Id']);
            foreach ($chosenitems as $chosenitems) {

                //echo "Item chosen:";
                //d($chosenitems);

                $productdata = getproduct($chosenitems['ProductID']);

                if($chosenitems['is_member'] == 1){
                    $itemprice = $chosenitems['unitprice_member'];
                }else{
                    $itemprice = $chosenitems['unitprice'];
                }

                echo "<li>
                <span>#".$chosenitems['OrderID']."<em>".$chosenitems['timestamp']."</em></span>
                <span>
                <ul>
                <li>".$productdata['Title']." <span>".$itemprice . " " . $_SESSION['currency']. "</span></li>
                <li class='bold'>Total: <span>".$allorders['totalprice'] . " " . $_SESSION['currency'] . "</span></li>
                <li>Tracking Number: <strong>".$allorders['pkg_no']."</strong></li>
                </ul>
                </span>
                </li>";

            }

        }
    }


    function getorderschosen($orderid)
    {
        global $db;
        $db->where("OrderID", $orderid);
        $orderchosen = $db->get('orders_chosen');
              //  $name    = $zipinfo['city'];
        return $orderchosen;
    }


    function getcart(){

        if(isset($_SESSION['cart'])){


            d($_SESSION['cart']);
            $cart = $_SESSION['cart'];
            if (!empty($cart)) {
                foreach ($cart as $cartkey => $cartvalue) {
        # code...
                }
            }
        }else{
            echo "cart is empty";
        }

    }

    function attributetitlefromid($id)
    {
        global $db;
        $db->where("Id", $id);
        $attrtitle = $db->getOne('productattributes');
        $attrtitle    = $attrtitle['Title'];
        return $attrtitle;
    }


    function attributegrouptitlefromid($id)
    {
        global $db;
        $db->where("Id", $id);
        $attrtitle = $db->getOne('productattributegroups');
        $attrtitle    = $attrtitle['Title'];
        return $attrtitle;
    }


    function attributeskufromproductandattrid($productid, $ProductAttributeId)
    {
        global $db;
        $db->where("ProductId", $productid);
        $db->where("ProductAttributeId", $ProductAttributeId);
        $attrcomboinfo = $db->getOne('productvariations');
             //   $attrtitle    = $attrtitle['Title'];
        return $attrcomboinfo;
    }



    function attributedatafromid($id)
    {
        global $db;
        $db->where("Id", $id);
        $attrtitle = $db->getOne('productattributes');
//                $attrtitle    = $attrtitle['Title'];
        return $attrtitle;
    }





    function producttitlefromid($id)
    {
        global $db;
        $db->where("Id", $id);
        $attrtitle = $db->getOne('products');
        $attrtitle    = $attrtitle['Title'];
        return $attrtitle;
    }
    function productdatafromid($id)
    {
        global $db;
        $db->where("Id", $id);
        $attrtitle = $db->getOne('products');
             //   $attrtitle    = $attrtitle['Title'];
        return $attrtitle;
    }


    function productidfromuid($uid){


        $exploded = explode('-', $uid);
        $productid = $exploded['0'];

        return $productid;
    }

    function productidsfromuid($uid){


        $exploded = explode('-', $uid);
   // $productid = $exploded['0'];

        return $exploded;
    }

    function attributeidsfromuid($uid){

    $uid = substr($uid, 2); // Removes X-   where X is productid. So we only get attribute id's  : - )

    $exploded = explode('-', $uid);
   // $productid = $exploded['0'];

    return $exploded;
}



function getpricefromuid($uid){

    $productids = productidsfromuid($uid);

    $counter = 0;
    $totalprice = 0;
    $productid = productidfromuid($uid);
    foreach ($productids as $key => $value) {
        # code...
        if($counter == 0){
            $totalprice =  $totalprice + getpricefromid($value);
            $counter++;
        }else{
            $totalprice =  $totalprice + getpricefromsubid($productid, $value);
            $counter++;
        }
    }

    return $totalprice;

}

function getvippricefromuid($uid){

    $productids = productidsfromuid($uid);

    $counter = 0;
    $totalprice = 0;
    $productid = productidfromuid($uid);
    foreach ($productids as $key => $value) {
        # code...
        if($counter == 0){
            $totalprice =  $totalprice + getvippricefromid($value);
            $counter++;
        }else{
            $totalprice =  $totalprice + getvippricefromsubid($productid, $value);
            $counter++;
        }
    }

    return $totalprice;

}




function getvippricefromid($id){
    global $db;
    $db->where("Id", $id);
    $products = $db->getOne('products');
    $Price    = $products['PriceMember'];
    return $Price;

}

function getvippricefromsubid($productid, $subid){
    global $db;
    $db->where("ProductId", $productid);
    $db->where("ProductAttributeId", $subid);

    $subidinfo = $db->getOne('productvariations');
    $Price    = $subidinfo['PriceMember'];
             //   if($Price == 0){
             //   $Price    = $subidinfo['Price'];
            //    }
    return $Price;

}



function totalcartsavingsforvip(){



  $subtotal = 0;
  $subtotalvip = 0;

  $totalshipping = 0;

  $completeprice = 0;
  $completepricevip = 0;



  foreach ($_SESSION['cart'] as $uid => $values) {
    $subtotal = $subtotal + $values['totalpriceallquantity'];
    $subtotalvip = $subtotalvip + $values['VIPtotalpriceallquantity'];
    $totalshipping = $totalshipping + $values['totalshippingpriceforuid'];
}

$savings = $subtotalvip - $subtotal;


return $savings;

}




function getpricefromid($id){
    global $db;
    $db->where("Id", $id);
    $products = $db->getOne('products');
    $Price    = $products['Price'];
    return $Price;

}


function shippingmethodidfromproductid($productid){
    global $db;
    $db->where("Id", $productid);
    $products = $db->getOne('products');
    $shippingmethodid    = $products['shippingmethod'];
    return $shippingmethodid;

}



function getpricefromsubid($productid, $subid){
    global $db;
    $db->where("ProductId", $productid);
    $db->where("ProductAttributeId", $subid);

    $subidinfo = $db->getOne('productvariations');
    $Price    = $subidinfo['Price'];
    return $Price;

}

function getpricefromsubid_with_id_as_key(){
    global $db;
    $productvariations_select = $db->get('productvariations');

    $getpricefromsubid_with_id_as_key = array();

    for ($i = 0; $i < count($productvariations_select); $i++) {

        $array_key_tmp = array_keys($productvariations_select[$i]);

        $getpricefromsubid_with_id_as_key[$productvariations_select[$i]["Id"]] = array();

        for ($j = 0; $j < count($array_key_tmp); $j++) {

            $getpricefromsubid_with_id_as_key[$productvariations_select[$i]["Id"]][$array_key_tmp[$j]] = $productvariations_select[$i][$array_key_tmp[$j]];

        }

    }
    return $getpricefromsubid_with_id_as_key;
}


function shippingmethodprice($shippingmethodid){


   $shippingmethodprice = 0;
   if($shippingmethodid == 1){
       $shippingmethodprice = 11;
   }
   if($shippingmethodid == 2){
       $shippingmethodprice = 22;
   }
   if($shippingmethodid == 3){
       $shippingmethodprice = 33;
   }
   if($shippingmethodid == 4){
       $shippingmethodprice = 44;
   }
   if($shippingmethodid == 5){
       $shippingmethodprice = 55;
   }


   return $shippingmethodprice;

}

function shippingmethodtext($shippingmethodid){


   $shippingmethodprice = 0;
   if($shippingmethodid == 1){
       $shippingmethodtext = 'POST DANMARK';
   }
   if($shippingmethodid == 2){
       $shippingmethodtext = 'GLS';
   }
   if($shippingmethodid == 3){
       $shippingmethodtext = 'DHL';
   }
   if($shippingmethodid == 4){
       $shippingmethodtext = 'SOME RANDOM';
   }
   if($shippingmethodid == 5){
       $shippingmethodtext = 'SOME 5 RANDOM';
   }


   return $shippingmethodtext;

}

function is_vip(){

    if(isset($_SESSION['VIP'])){

        if($_SESSION['VIP'] == 1){
            return true;
        }else{
            return false;
        }
    }
    return false;

}

function getcartlisting(){
    global $basehref;

 // getproductdata($productid){

 // getproductattributedata($productid){

    if(isset($_SESSION['cart'])){
        $products_with_id_as_key = products_with_id_as_key();

        foreach ($_SESSION['cart'] as $uid => $values) {
//    print "$uid {\n";
            if(isset($values['quantity'])){
                $quantity = $values['quantity'];
            }else{
                $quantity = '1';
            }


  //  $totalprice = getpricefromuid($uid);
  //  $totalpricecombined = $totalprice * $quantity;

            if(isset($_SESSION['VIP'])){




                if($_SESSION['VIP'] == 1){
                    $totalprice = getvippricefromuid($uid);
                    $totalpricecombined = $totalprice * $quantity;
                }else{
                    $totalprice = getpricefromuid($uid);
                    $totalpricecombined = $totalprice * $quantity;
                }
            }else{
                $totalprice = getpricefromuid($uid);
                $totalpricecombined = $totalprice * $quantity;
            }

            $producttitle = $values['producttitle'];
            $variationstitles = $values['variationtitles'];
            $productid = productidfromuid($uid);
            $productthumbnail = $products_with_id_as_key[$productid]['img1'];


            echo "
            <tr id='uid$uid' data-uid='$uid'>
            <td class='item-image'><div class='image'><img src='$basehref/uploads/productimages/$productid/$productthumbnail'></div></td>
            <td class='item-qty'>$quantity</td>
            <td class='item-desc'><strong>$producttitle</strong> $variationstitles</td>
            <td class='item-unitprice'>$totalprice USD</td>
            <td class='item-price'>$totalpricecombined USD</td>
            <td class='item-action'>
            <span class='fa fa-plus addqty'></span>
            <span class='fa fa-minus decreaseqty'></span>
            <span class='fa fa-trash removefromcart'></span>
            </td>
            </tr>

            ";



        /*        echo "test start"; echo $values['quantity']; echo "test end";
    foreach ($values as $key => $value) {
        print "    $key => $value\n";



    }
    print "}\n"; */
}

}else{
    echo " kurven er tom. prøv at tilføje noget i den. ";
}
}


function getcartheaderlisting(){
    global $basehref;

 // getproductdata($productid){

 // getproductattributedata($productid){

    if(isset($_SESSION['cart'])){


        $products_with_id_as_key = products_with_id_as_key();

        foreach ($_SESSION['cart'] as $uid => $values) {
//    print "$uid {\n";
            if(isset($values['quantity'])){
                $quantity = $values['quantity'];
            }else{
                $quantity = '1';
            }
            $productid = productidfromuid($uid);


            if(isset($_SESSION['VIP'])){

                if($_SESSION['VIP'] == 1){
                    $totalprice = getvippricefromuid($uid);
                    $totalpricecombined = $totalprice * $quantity;
                }else{
                    $totalprice = getpricefromuid($uid);
                    $totalpricecombined = $totalprice * $quantity;
                }

            }else{
              $totalprice = getpricefromuid($uid);
              $totalpricecombined = $totalprice * $quantity;
          }

          $producttitle = $values['producttitle'];
          $variationstitles = $values['variationtitles'];

          $displayquantity = $values['quantity'];

          if(isset($_SESSION['VIP'])){

            if($_SESSION['VIP'] == 1){

                $displaytotalprice = $values['VIPtotalprice'];
                $displaytotalpriceallquantity = $values['VIPtotalpriceallquantity'];
            }else{
                $displaytotalprice = $values['totalprice'];
                $displaytotalpriceallquantity = $values['totalpriceallquantity'];
            }

        }else{
            $displaytotalprice = $values['totalprice'];
            $displaytotalpriceallquantity = $values['totalpriceallquantity'];
        }

        $productthumbnail = $products_with_id_as_key[$productid]['img1'];


        echo "

        <tr id='uidheaduid' data-uid='$uid'>
        <td class='image'><img src='$basehref/uploads/productimages/$productid/$productthumbnail'></td>
        <td class='title'><span>$displayquantity x</span><strong>$producttitle</strong><em>$variationstitles</em></td>
        <td class='combinedprice'><strong>$displaytotalpriceallquantity DKK</strong></td>

        <td class='actions'><span class='fa fa-trash deletetopcartitem'></span></td>
        </tr>

        ";


    }

}else{
    echo "<p class='cart-empty'>Kurven er tom.</p>";
}
}




function searchforproducts($str){

    global $db;

    $db->where('Title', '%' . $str . '%', 'like');
    $rows = $db->get('products p', null, 'p.Title, p.ShortDescription, p.img1, p.Id, p.Price, p.PriceMember, p.slug');

    return json_encode($rows);


}


function generateprettybookingidfromid($id){

    $prettyid = sprintf("%08d", $id);
    $prettyid = "B$prettyid";

    return $prettyid;
}


function dayofweekfromdate($date){
    $dw = date("N", strtotime($date));
    return $dw;
}


function randomPassword
(
//autor: Femi Hasani [www.vision.to]
$length=7, //string length
$uselower=1, //use lowercase letters
$useupper=1, // use uppercase letters
$usespecial=0, //use special characters
$usenumbers=1, //use numbers
$prefix=''
) {
    $key = $prefix;
// Seed random number generator
    srand((double) microtime() * rand(1000000, 9999999));
    $charset = "";
    if ($uselower == 1){
        $charset .= "abcdefghijkmnopqrstuvwxyz";
    }
    if ($useupper == 1){
        $charset .= "ABCDEFGHIJKLMNPQRSTUVWXYZ";
    }
    if ($usenumbers == 1){
        $charset .= "0123456789";
    }
    //if ($usespecial == 1) $charset .= "#%^*()_+-{}][";
    if ($usespecial == 1){
        $charset .= "#*_+-";
    }
    while ($length > 0) {
        $key .= $charset[rand(0, strlen($charset) - 1)];
        $length--;
    }
    return $key;
}















/* START BOOOKING */



function getInterval($shopid){

//sql at some point?
    $interval = '15';
    return $interval;
}

function getMaxBooking(){
    // sql at some point??
    $maxbookings = '4';
    return $maxbookings;
}


function getMinBooking(){
    // sql at some point??
    $minbookings = '4';
    return $minbookings;
}
function getServiceSettings($shopid, $action){

    if($action == 'show_multiple_spaces'){
        return '1';
    }
    if($action == 'spaces_available'){
        return '4';
    }
    if($action == 'show_multiple_spaces'){

    }



} //check option for multiple timeBooking



/*
function getMaxBooking($serviceID=1) {
    $q = "SELECT * FROM bs_service_settings WHERE serviceId ='{$serviceID}'";
    $res = mysql_query($q);
    $rr = mysql_fetch_assoc($res);
    return $rr["allow_times"];
}

function getMinBooking($serviceID=1) {
    $q = "SELECT * FROM bs_service_settings WHERE serviceId ='{$serviceID}'";
    $res = mysql_query($q);
    $rr = mysql_fetch_assoc($res);
    return $rr["allow_times_min"];
}

 */


/*function getLangList() {
    $langList = array();

    $path = MAIN_PATH . "\languages";
    $path1 = MAIN_PATH . "/languages";
    if(is_dir($path)){
            foreach (scandir($path) as $lang) {
                //print $lang;
                if (strpos($lang, "lang") !== false) {
                    $langList[] = substr($lang, 0, strpos($lang, "."));
                }
            }
        }elseif(is_dir($path1)){

            foreach (scandir($path1) as $lang) {
                //print $lang;
                if (strpos($lang, "lang") !== false) {
                    $langList[] = substr($lang, 0, strpos($lang, "."));
                }
            }
        }

    return $langList;
}
*/


function getScheduleTable($date, $serviceID=4) {
    global $baseDir;
    global $db;
    ####################################### PREPARE AVAILABILITY TABLE ##############################################
    $int = getInterval($serviceID); //interval in minutes.
    $reservedArray = array();
    $reservationData = array();
    $seconds = 0;
    $availability = "";
    $availebleSpaces = getServiceSettings($serviceID, 'spaces_available');
    $show_multiple_spaces = getServiceSettings($serviceID, 'show_multiple_spaces');
    ##########################################################################################################################
    #    GET RESERVED TIME / RESERVED ARRAY
    /*
    //$query="SELECT * FROM bs_events WHERE eventDate LIKE '%".$date."' AND serviceID='{$serviceID}' ORDER BY eventDate ASC ";
    $query = "SELECT * FROM bs_events WHERE eventDate <= '" . $date . " 23:59' AND eventDateEnd >= '" . $date . " 00:00' AND serviceID='{$serviceID}' ORDER BY eventDate ASC ";
    $result = mysql_query($query);
    if (mysql_num_rows($result) > 0) {
        $availability .= "<div class='eventWrapper'>";
        //we have events for this day!
        $event_num = mysql_num_rows($result);
        //we need to check if at least one event has spaces. if yes then { $bgClass="cal_reg_on";  } else { $bgClass="cal_reg_off"; }
        $event_available = false;
        $event_count = 0;
        $text = "";
        $curr = getAdminPaypal();
        while ($row = mysql_fetch_assoc($result)) {
            $spaces_left = getSpotsLeftForEvent($row["id"]);
            $availability .="<div class='eventContainer'>";
            if ($row["path"] != "") {
                $availability .="<div class='eventImage' style='text-align:center;'><img src='" . $baseDir."/".$row["path"] . "' alt='" . $row["title"] . "' /></div>";
            }

            $availability .="<div class='event'><b>" . $row["title"] . "</b><br />";
            $availability .= TXT_EVENT_START." <b>" . getDateFormat($row["eventDate"]) . "&nbsp;&nbsp;" . date((getTimeMode()) ? " g:i a" : " H:i", strtotime($row["eventDate"])) . "</b><br> ";
            $availability .= TXT_EVENT_ENDS." <b>" . getDateFormat($row["eventDateEnd"]) . "&nbsp;&nbsp;" . date((getTimeMode()) ? " g:i a" : " H:i", strtotime($row["eventDateEnd"])) . "</b>.<br>";
            $availability .=$spaces_left . SPC_LEFT."</b><br />" . $row["description"];
            $q2 = "SELECT * FROM bs_reservations WHERE eventID='" . $row["id"] . "'";
            $res2 = mysql_query($q2);
            if (mysql_num_rows($res2) > 0) {
                $availability .= "<br /><br /><b>Attendees:</b>";
                while ($r2 = mysql_fetch_assoc($res2)) {
                    $availability .= "<br />" . $r2["name"] . " " . $r2["phone"] . " (" . $r2["qty"] . " ticket" . ($r2["qty"] > 1 ? "s" : "") . ")";
                }
            }
            $availability .="</div><br clear='all'></div>";
        }
        if ($event_count == 1) {

        } else if ($event_count > 1) {
            $text = "<p>".TXT_PLSSELECT."</p>";
        } else {
            $text = "";
        }

        $availability .="</div>";
    }

*/


/*
    //ADMIN RESERVED TIME
    $query = "SELECT rti.*,rt.serviceID FROM bs_reserved_time_items rti
            INNER JOIN bs_reserved_time rt ON rt.id=rti.reservedID
            WHERE dateFrom LIKE '" . $date . "%' AND rt.serviceID={$serviceID} ORDER BY dateFrom ASC ";
    //$query="SELECT * FROM bs_reserved_time_items WHERE dateFrom LIKE '".$date."%' ORDER BY dateFrom ASC ";
    $result = mysql_query($query);
    if (mysql_num_rows($result) > 0) {
        while ($rr = mysql_fetch_assoc($result)) {
            //IF ADMIN SELECTED FROM 12:00 to 18:00 (more than 1 interval time between 2 spots)
            if (isset($reservedArray[date("Y-m-d", strtotime($rr["dateFrom"]))][date("H:i", strtotime($rr["dateFrom"]))])) {
                $reservedArray[date("Y-m-d", strtotime($rr["dateFrom"]))][date("H:i", strtotime($rr["dateFrom"]))] = $rr["qty"] + $reservedArray[date("Y-m-d", strtotime($rr["dateFrom"]))][date("H:i", strtotime($rr["dateFrom"]))];
            } else {
                $reservedArray[date("Y-m-d", strtotime($rr["dateFrom"]))][date("H:i", strtotime($rr["dateFrom"]))] = $rr["qty"];
            }
            # TODO - question: what if i had intervals was 30m, and we had bookings but then time passes and we changed interval to be 1h. What will be displayed.
            # on front - we can block past dates, however If somebody booked something in future, and we suddenly changed the interval time - for now we can
            # simply state in admin that if you changed it - you have to manually advice customers and manually change their bookings (1 by 1)
        }
    }
    */


    //ACTUAL CUSTOMER BOOKINGS

    $query = $db->rawQuery("SELECT * from pg_bookings
        WHERE   pg_bookings.bookingdate LIKE '" . $date . "%' ORDER BY pg_bookings.bookingdate ASC ");
    $result = $query;
    if (!empty($result)){
        while ($rr = $result) {
            if (isset($reservedArray[date("Y-m-d", strtotime($rr["bookingdate"]))][date("H:i", strtotime($rr["bookingdate"]))])) {
                $reservedArray[date("Y-m-d", strtotime($rr["bookingdate"]))][date("H:i", strtotime($rr["bookingdate"]))] = $rr["qty"] + $reservedArray[date("Y-m-d", strtotime($rr["bookingdate"]))][date("H:i", strtotime($rr["bookingdate"]))];
            } else {
                $reservedArray[date("Y-m-d", strtotime($rr["bookingdate"]))][date("H:i", strtotime($rr["bookingdate"]))] = $rr["qty"];
            }
            $reservationInfo = "<div><a href='wington-bookings-edit.php?id=" . $rr["rid"] . "'>" . $rr["name"] . "&nbsp; (phone:" . $rr["phone"] . "; qty=" . $rr['qty'] . ")</a></div>";
            if (isset($reservationData[date("Y-m-d", strtotime($rr["bookingdate"]))][date("H:i", strtotime($rr["bookingdate"]))])) {
                $reservationData[date("Y-m-d", strtotime($rr["bookingdate"]))][date("H:i", strtotime($rr["bookingdate"]))] =
                $reservationData[date("Y-m-d", strtotime($rr["bookingdate"]))][date("H:i", strtotime($rr["bookingdate"]))] . $reservationInfo;
            } else {
                $reservationData[date("Y-m-d", strtotime($rr["bookingdate"]))][date("H:i", strtotime($rr["bookingdate"]))] = $reservationInfo;
            }
        } // end while
    } // end if exists
    //dump($reservationData);
    //dump($reservedArray);
    ##########################################################################################################################
    ##########################################################################################################################
    # PREPARE AVAILABILITY ARRAY
    $schedule = getScheduleService($serviceID, $date);
    $availabilityArr = $schedule['availability'];
    $events = $schedule['events'];
    $n = $schedule['countItems'];
    $admins = $schedule['admins'];
    $users = $schedule['users'];

    //dump($availabilityArr);
    //$ww= date("w",strtotime($date));
    //$tt = getStartEndTime($ww,$serviceID);
    if (!count($availabilityArr)) {
        $availability .= 'crack';//ADM_NONWORKING;
    } else {
        $availability .= "<table width=\"500\" border=\"0\" align=\"left\" cellpadding=\"0\" cellspacing=\"0\"><tr><td valign='top'>";
        $n = ($n - ($n % 2)) / 2;
        $count = 0;
        foreach ($availabilityArr as $k => $v) { //$v= date  (  2010-10-05 )
            foreach ($v as $kk => $vv) { //$vv = time slot in above date
                if ($count == $n) {
                    $availability .= "</td><td align='left' valign='top'>";
                    $count = 0;
                }
                $bookLink = "<a class='book' href='wington-reserve.php?serviceID={$serviceID}&reserveDateFrom={$date}&reserveDateTo={$date}&1_from_h=".date("H", strtotime($vv))."&1_from_m=".date("i", strtotime($vv))."&2_from_h=".date("H", strtotime($vv. " +" . $int . " minutes"))."&2_from_m=".date("i", strtotime($vv. " +" . $int . " minutes"))."' ></a>";
                if (isset($events[$k]) && in_array($vv, $events[$k])) {
                    $availability .="<tr class='schedule_na'><td width='100' valign='top' class='time'><div>" . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . "</div></td><td valign='top'>".TXT_EVENT2."</td></tr>";
                } elseif (isset($admins[$k]) && array_key_exists($vv, $admins[$k])) {
                    //print(checkForAdminReserv("$k $vv",date("Y-m-d H:m;i",strtotime("$k $vv +$int minutes")),$serviceID));
                    $spacesBookedUser = isset($users[$k][$vv])?$users[$k][$vv]:0;
                    $spacesBooked = $admins[$k][$vv];
                    $adminReserveData = "<br><a href='javascript:;'>Manual Reservation (qty = $spacesBooked)</a>";
                    $spacesAllowed = $availebleSpaces - $spacesBooked-$spacesBookedUser;
                    if ($spacesAllowed >= 1) {
                        $msm = ((int) substr($vv, 0, 2)) * 60 + ((int) substr($vv, -2)); //minutes since miodnight of current day.
                        $spacesAllowed = $show_multiple_spaces ? $spacesAllowed : 1;
                        $availability .="<tr class='schedule_av'><td width='100' valign='top' class='time'><div>" . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . "</div></td><td valign='top'><span class='space'>{$spacesAllowed}</span> {$bookLink}". SPC_LEFT. $adminReserveData .(isset($reservationData[$k][$vv])?$reservationData[$k][$vv]:""). "</td></tr>";
                    } else {

                        $availability .="<tr class='schedule_av empty'><td width='100' valign='top' class='time'><div>" . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . "</div></td><td valign='top'><span class='space'>{$spacesAllowed}</span>". SPC_LEFT. $adminReserveData . (isset($reservationData[$k][$vv])?$reservationData[$k][$vv]:"")."</td></tr>";
                    }
                } elseif (isset($users[$k]) || (isset($users[$k]) && !array_key_exists($vv, $users[$k]))) {
                    $msm = ((int) substr($vv, 0, 2)) * 60 + ((int) substr($vv, -2)); //minutes since miodnight of current day.
                    //$availebleSpaces;
                    $spacesBooked = $users[$k][$vv];
                    $spacesAllowed = $availebleSpaces - $spacesBooked;
                    $availability .="<tr class='schedule_av ".($spacesAllowed==0?"empty":"")."'><td width='100' valign='top' class='time'><div>" . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . "</div></td><td valign='top'><span class='space'>{$spacesAllowed}</span>".($spacesAllowed?$bookLink:"") .SPC_LEFT . $reservationData[$k][$vv] . "</td></tr>";
                } else {
                    $availebleSpaces = $show_multiple_spaces ? $availebleSpaces : 1;
                    $availability .= "<tr class='schedule_av'><td width='100' valign='top' class='time'><div>" . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . "</div></td><td valign='top'><span class='space'>{$availebleSpaces}</span>". SPC_LEFT. $reservationData[$k][$vv] . "{$bookLink}</td></tr>";
                }


                $count++;
            }
        }
        $availability .="</td></tr></table>";
    }
    ##########################################################################################################################

    return $availability;
}



function checkQtyForTimeBooking($serviceID, $time, $date, $interval, $qty) {
    //print "$serviceID<br>$date<br>$interval<br>$qty";
    $availebleSpaces = getServiceSettings($serviceID, 'spaces_available');
    $error = false;
    $qtyTmp = 0;
    $sumQty = 0;
   // foreach ($time as $k => $v) {
    $from = date("Y-m-d H:i:s", strtotime($date . " +" . $time . " minutes"));
    $to = date("Y-m-d H:i:s", strtotime($from . " +" . $interval . " minutes"));
    $adminQTY = checkForAdminReserv($from, $to, $serviceID);
        //print gettype($qtyTmp)."<br>";
    $sumQty = $adminQTY;
        /* $query="SELECT bs_reservations_items.* FROM `bs_reservations_items`
          INNER JOIN bs_reservations on bs_reservations_items.reservationID = bs_reservations.id
          WHERE (bs_reservations.status='1' OR bs_reservations.status='4') AND
          bs_reservations_items.reserveDateFrom LIKE '".$startTime."%' AND
          bs_reservations_items.reserveDateTo LIKE '".$endTime."%' AND
          bs_reservations.serviceID={$serviceID} ORDER BY bs_reservations_items.reserveDateFrom ASC "; */
        //print $query;
          $sSQL = "SELECT bri.* FROM `bs_reservations_items` bri
          INNER JOIN bs_reservations br on bri.reservationID = br.id
          WHERE br.serviceID='{$serviceID}' AND (
          (bri.reserveDateFrom < '{$to}' AND bri.reserveDateTo >= '{$to}') OR
          (bri.reserveDateTo > '{$from}' AND bri.reserveDateFrom <= '{$from}') OR
          (bri.reserveDateFrom <= '{$from}' AND bri.reserveDateTo >= '{$to}') OR
          (bri.reserveDateFrom >= '{$from}' AND bri.reserveDateTo <= '{$to}'))
          AND (br.status='1' OR br.status='4')
          ORDER BY bri.reserveDateFrom ASC";

          $result = mysql_query($sSQL);
          if (mysql_num_rows($result) > 0) {
            if (mysql_num_rows($result) > 1) {

                while ($row = mysql_fetch_assoc($result)) {
                    $qtyTmp+=$row['qty'];
                }
                $sumQty+=$qtyTmp + $qty;
                if ($sumQty > $availebleSpaces) {
                    $error = true;
                }
            } else {
                $qtyTmp = mysql_fetch_assoc($result);
                $sumQty+=$qtyTmp['qty'] + $qty;
                if ($sumQty > $availebleSpaces) {
                    $error = true;
                }
            }
        }
    //}

        return $error;
    }


    function getAvailableBookingsTable($date, $serviceID=4, $time=null, $qty=1) {
    ####################################### PREPARE AVAILABILITY TABLE ##############################################
    $int = getInterval($serviceID); //interval in minutes.

    $show_multiple_spaces = getServiceSettings($serviceID, 'show_multiple_spaces'); //check option for multiple timeBooking
    $availebleSpaces = $show_multiple_spaces ? getServiceSettings($serviceID, 'spaces_available') : 1;
    $spot_price = getServiceSettings($serviceID, 'spot_price');
    $seconds = 0;
    $availability = "";

    ##########################################################################################################################
    # PREPARE AVAILABILITY ARRAY

    $schedule = getScheduleService($serviceID, $date);
    $availabilityArr = $schedule['availability'];
    $events = $schedule['events'];
    $admins = $schedule['admins'];
    $users = $schedule['users'];
    $n = $schedule['countItems'];
    //print dump($availabilityArr);
    //dump($admins);
    //dump($users);
    //print $n;
    $availability .= "<div class='timeEvCont'><select id='time_select' class='time_select' name='time' ><option value='0'>Vælg venligst en tid</option>";

    $n = ($n - ($n % 2)) / 2;
    $count = 0;
    foreach ($availabilityArr as $k => $v) { //$v= date  (  2010-10-05 )
        //var_dump($availabilityArr);
        //out($v);die;
        foreach ($v as $kk => $vv) { //$vv = time slot in above date
            if ($time == null) {
                $time = array();
            }

            //current timestamp
            $currTime = strtotime(date("Y-m-d H:i"));
            //timestamp on start time interval
            $spotTimeStart = strtotime(date("Y-m-d", strtotime($date)) . " " . $vv . " -" . (5) . " minutes"); //5-minutes befo select interval in past

            //$availability .="<div class='timeItem'>";
            //select intervat to past
            if (isset($events[$k]) && in_array($vv, $events[$k])) {
                //$availability .="<option disabled >" . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . " - ".TXT_EVENT.".</option>";
                $availability .="<option disabled >" . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " ".TXT_EVENT.".</option>";
            } elseif ($spotTimeStart < $currTime) {
                //$availability .="<option disabled >" . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . " - ".TXT_PAST.".</option>";
            } elseif ((isset($admins[$k]) && array_key_exists($vv, $admins[$k]))) {
                $spacesBookedUser = isset($users[$k][$vv])?$users[$k][$vv]:0;
                $spacesBooked = $admins[$k][$vv];
                $spacesAllowed = $availebleSpaces - $spacesBooked-$spacesBookedUser;
                if ($spacesAllowed >= 1) {
                    $msm = ((int) substr($vv, 0, 2)) * 60 + ((int) substr($vv, -2)); //minutes since miodnight of current day.
                    //$txt = $show_multiple_spaces ? "&nbsp;-&nbsp;<span class='spaces'>({$spacesAllowed} ".SPACES.")</span>" : "";
                    $txt = $show_multiple_spaces ? "&nbsp;<span class='spaces'>({$spacesAllowed} ".SPACES.")</span>" : "";
                    //$txt='';
                    //$availability .="<option" . (in_array($msm, $time) ? "selected" : "") . " value=\"" . $msm . "\"  rel=\"$spacesAllowed\">" . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . "{$txt}</option>";
                    $availability .="<option" . (in_array($msm, $time) ? "selected" : "") . " value=\"" . $msm . "\"  rel=\"$spacesAllowed\">" . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . "{$txt}</option>";
                } else {
                    //$txt = $show_multiple_spaces ? '&nbsp;-&nbsp;<span class="spaces">('.ZERO_SPACES2.')</span>' : "";
                    $txt = $show_multiple_spaces ? '&nbsp;<span class="spaces">('.ZERO_SPACES2.')</span>' : "";
                    //$txt='';
                    //$availability .="<option disabled>" . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . "{$txt}</option>";
                    $availability .="<option disabled>" . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . "{$txt}</option>";
                }
            } elseif ((isset($users[$k]) && array_key_exists($vv, $users[$k]))) {

                $spacesBooked = $users[$k][$vv];

                $spacesAllowed = $availebleSpaces - $spacesBooked;

                if ($spacesAllowed >= 1) {
                    $msm = ((int) substr($vv, 0, 2)) * 60 + ((int) substr($vv, -2)); //minutes since miodnight of current day.
                    //$txt = $show_multiple_spaces ? "&nbsp;-&nbsp;<span class='spaces'>({$spacesAllowed} ".SPACES.")</span>" : "";
                    $txt = $show_multiple_spaces ? "&nbsp;<span class='spaces'>({$spacesAllowed} ".SPACES.")</span>" : "";
                    //$txt='';
                    //$availability .="<option" . (in_array($msm, $time) ? "selected" : "") . " value=\"" . $msm . "\"  rel=\"$availebleSpaces\">" . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . "{$txt}</option>";
                    $availability .="<option" . (in_array($msm, $time) ? "selected" : "") . " value=\"" . $msm . "\"  rel=\"$spacesAllowed\">" . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . "{$txt}</option>";
                } else {

                    //$txt = $show_multiple_spaces ? '&nbsp;-&nbsp;<span class="spaces">('.ZERO_SPACES2.')</span>' : "";
                    $txt = $show_multiple_spaces ? '&nbsp;<span class="spaces">('.ZERO_SPACES2.')</span>' : "";
                    //$txt='';
                    //$availability .="<option   disabled='disabled'>" . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . "{$txt}</option>";
                    $availability .="<option   disabled='disabled'>" . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . "{$txt}</option>";
                }
                /*$msm = ((int) substr($vv, 0, 2)) * 60 + ((int) substr($vv, -2)); //minutes since miodnight of current day.
                $txt = $show_multiple_spaces ? "&nbsp;-&nbsp;<span class='spaces'>({$availebleSpaces} ".SPACES.")</span>" : "";
                $availability .="<input type=\"checkbox\"" . (in_array($msm, $time) ? "checked" : "") . " value=\"" . $msm . "\" name=\"time[]\" rel=\"$availebleSpaces\"> - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . "{$txt}<br />";*/
            } else {
                $msm = ((int) substr($vv, 0, 2)) * 60 + ((int) substr($vv, -2)); //minutes since miodnight of current day.
                //$txt = $show_multiple_spaces ? "&nbsp;-&nbsp;<span class='spaces'>(".$availebleSpaces.SPACES.")</span>" : "&nbsp;-&nbsp;<span class='spaces'>(1 ".SPACES.")</span>";
                $txt = $show_multiple_spaces ? "&nbsp;<span class='spaces'>(".$availebleSpaces.SPACES.")</span>" : "&nbsp;-&nbsp;<span class='spaces'>(1 ".SPACES.")</span>";
                //$txt='';
                //$availability .="<option   " . (in_array($msm, $time) ? "selected" : "") . " value=\"" . $msm . "\"  rel=\"$availebleSpaces\">" . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . "{$txt}</option>";
                $availability .="<option   " . (in_array($msm, $time) ? "selected" : "") . " value=\"" . $msm . "\"  rel=\"$availebleSpaces\">" . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . "{$txt}</option>";
            }
            //$availability .="</div>";
            $count++;
        }
    };
    $currencyPos = getOption('currency_position');
    $cuurency = getOption('currency');
    $availability .="</select>";
    //$availability .=$show_multiple_spaces ?"<span>".TXT_QTY." <span class='spinner'><input type='text' name='qty' id='qty' value='$qty' style='width:40px'></span></span>" : "";
    for($i = 1; $i <= $availebleSpaces; $i++) {
    /*$selected = ($i == $qty) ? 'selected' : '';
    $selectOptions .="<option value='$i' $selected>$i</option>";*/
    $selectOptions .="<option value='$i'>$i</option>";
}
$availability .=$show_multiple_spaces ?"<select name='qty' id='qty' class='qty_select'><option value=''>Vælg antal personer</option>$selectOptions</select>" : "";
$availability .=$spot_price ? "&nbsp;<span id='feeValue'>".$cuurency . "&nbsp;</span>" : "";
$availability .="</div>";
    ##########################################################################################################################

return $availability;
}


function getScheduleService($ShopID, $date) {
    global $db;
    $availabilityArr = array();
    $events = array();
    $admins = array();
    $users = array();
    //$int = getInterval($idService);
    $idService = $ShopID;
    $int = 15;

    //$dayOfWeek = date("w", strtotime($date));
    $dayOfWeek = dayofweekfromdate($date);
//    $res = $db->rawQuery("SELECT * FROM pg_stores_openinghours
//            WHERE ShopID='{$idService}' AND day='{$dayOfWeek}' ORDER BY start ASC");

    $db->where ("ShopID", $idService);
    $db->where ("day", $dayOfWeek);

    $res = $db->getOne ("stores_openinghours");

            //print $sql;
   // $res = mysql_query($sql) or die(mysql_error() . "<br>" . $sql);

    ddd($res);
    $n = 0;
    //while ($row = mysql_fetch_assoc($res)) {
    while ($res){
        //$schedule[]=array("start"=>$row['startTime'],"end"=>$row['endTime']);

        $st = date("Y-m-d H:i", strtotime($date . " +" . $res['start'] . " minutes"));
        $et = date("Y-m-d H:i", strtotime($date . " +" . $res['end'] . " minutes"));
        $a = $st;

        //layout counter
        $b = date("Y-m-d H:i", strtotime($a . " +" . $int . " minutes")); //default value for B is start time.
        for ($a = $st; $b <= $et; $b = date("Y-m-d H:i", strtotime($a . " +" . $int . " minutes"))) {
            //echo "a: ".$a." // "."b: ".$b."<br />";
         /*   if (checkForEvents($a, $b, $idService)) {
                $events[date("Y-m-d", strtotime($a))][] = date("H:i", strtotime($a));
            }
*/

         /*   $qtyAdminReservation = checkForAdminReserv($a, $b, $idService); //print "<br>".$qtyAdminReservation."<br>";
            if ($qtyAdminReservation > 0) {
                      $admins[date("Y-m-d", strtotime($a))][date("H:i", strtotime($a))] =
                isset($admins[date("Y-m-d", strtotime($a))][date("H:i", strtotime($a))]) ?
                      $admins[date("Y-m-d", strtotime($a))][date("H:i", strtotime($a))]+= $qtyAdminReservation :
                      $admins[date("Y-m-d", strtotime($a))][date("H:i", strtotime($a))]+= $qtyAdminReservation;
                  } */
                  $qtyUserReservation = checkForUserReserv($a, $b, $idService);
                  if ($qtyUserReservation !== false) {
                //$users[date("Y-m-d", strtotime($a))][date("H:i", strtotime($a))] = $qtyUserReservation;
                    $users[date("Y-m-d", strtotime($a))][date("H:i", strtotime($a))] = isset($users[date("Y-m-d", strtotime($a))][date("H:i", strtotime($a))]) ? $users[date("Y-m-d", strtotime($a))][date("H:i", strtotime($a))]+=$qtyUserReservation : $users[date("Y-m-d", strtotime($a))][date("H:i", strtotime($a))]+= $qtyUserReservation;
                }
                $availabilityArr[date("Y-m-d", strtotime($a))][] = date("H:i", strtotime($a));
                $a = $b;
                $n++;
            }
        }
    /*echo "<pre style='float:left'>";
    var_dump(array("availability" => $availabilityArr, "events" => $events, "admins" => $admins, "users" => $users, "countItems" => $n));
    echo "</pre>";*/
    return array("availability" => $availabilityArr, "events" => $events, "admins" => $admins, "users" => $users, "countItems" => $n);
}


function checkForUserReserv($from, $to, $serviceID) {

    $qty = 0;
    /*$sSQL = "SELECT bri.* FROM `bs_reservations_items` bri
            INNER JOIN bs_reservations br on bri.reservationID = br.id
                WHERE br.serviceID='{$serviceID}' AND (
                (bri.reserveDateFrom < '{$to}' AND bri.reserveDateTo >= '{$to}') OR
                (bri.reserveDateTo > '{$from}' AND bri.reserveDateFrom <= '{$from}') OR
                (bri.reserveDateFrom <= '{$from}' AND bri.reserveDateTo >= '{$to}') OR
                (bri.reserveDateFrom >= '{$from}' AND bri.reserveDateTo <= '{$to}'))
                AND (br.status='1' OR br.status='4')
                ORDER BY bri.reserveDateFrom ASC";*/
                $sSQL = $db->rawQueryValue ("SELECT bri.* FROM `pg_bookings`
                    WHERE br.shopid='{$serviceID}' AND (
                    (bri.bookingdate < '{$to}' AND bri.bookingdate >= '{$to}') OR
                    (bri.bookingdate > '{$from}' AND bri.bookingdate <= '{$from}') OR
                    (bri.bookingdate <= '{$from}' AND bri.bookingdate >= '{$to}') OR
                    (bri.bookingdate >= '{$from}' AND bri.bookingdate <= '{$to}'))
                    AND (br.status='1' OR br.status='2' OR br.status='3')
                ORDER BY bri.bookingdate ASC"); // bri INNER JOIN bs_reservations br on bri.reservationID = br.id
    //print $sSQL;
    $res = $sSQL;//mysql_query($sSQL);
    if (!empty($res)){ //mysql_num_rows($res) > 0) {
        while ($res){//$row = mysql_fetch_assoc($res)) {
            $qty += 1; //$row['qty'];
        }
        return $qty;
    } else {
        return false;
    }
}

function checkSpotsLeft($date, $serviceID=4) {
    $spots = 0; //print $serviceID;
    $show_multiple_spaces = getServiceSettings($serviceID, 'show_multiple_spaces'); //check option for multiple timeBooking
    $availebleSpaces = $show_multiple_spaces ? getServiceSettings($serviceID, 'spaces_available') : 1;

    ##########################################################################################################################
    ##########################################################################################################################
    # PREPARE AVAILABILITY ARRAY
    $schedule = getScheduleService($serviceID, $date);
    $availabilityArr = $schedule['availability'];
    $events = $schedule['events'];
    $admins = $schedule['admins'];
    $users = $schedule['users'];

    $n = $schedule['countItems'];



    foreach ($availabilityArr as $k => $v) { //$v= date  (  2010-10-05 )
        foreach ($v as $kk => $vv) { //$vv = time slot in above date
            //echo $vv;
            if (isset($events[$k]) && in_array($vv, $events[$k])) {

            } elseif (isset($admins[$k]) && array_key_exists($vv, $admins[$k])) {

                //current timestamp
                $spacesBooked = $admins[$k][$vv];
                $spacesAllowed = $availebleSpaces - $spacesBooked;
                $currTime = strtotime(date("Y-m-d H:i"));
                //timestamp on start time interval

//                    echo $k."_curtime_".$spacesAllowed." ".$spacesBooked."<br />";
                $spotTimeStart = strtotime(date("Y-m-d", strtotime($k)) . " " . $vv . " -" . (5) . " minutes"); //5-minutes befo select interval in past
                if ($spotTimeStart < $currTime) {
                    //this interval passed already.
                } elseif ($spacesAllowed >= 1) {
                    $spots+=$spacesAllowed;
                }

                if (isset($users[$k]) && array_key_exists($vv, $users[$k])) {

                    //current timestamp
                    $spacesBooked = $users[$k][$vv];
                    $spacesAllowed = $spacesAllowed - $spacesBooked;
                    $currTime = strtotime(date("Y-m-d H:i"));
                    //timestamp on start time interval
                    $spotTimeStart = strtotime(date("Y-m-d", strtotime($k)) . " " . $vv . " -" . (5) . " minutes"); //5-minutes befo select interval in past
                    if ($spotTimeStart < $currTime) {
                        //this interval passed already.
                    } elseif ($spacesAllowed >= 1) {
                        $spots+=$spacesAllowed;
                    }
                }
            }elseif (isset($users[$k]) && array_key_exists($vv, $users[$k])) {

                //current timestamp
                $spacesBooked = $users[$k][$vv];
                $spacesAllowed = $availebleSpaces - $spacesBooked;
                $currTime = strtotime(date("Y-m-d H:i"));
                //timestamp on start time interval
                $spotTimeStart = strtotime(date("Y-m-d", strtotime($k)) . " " . $vv . " -" . (5) . " minutes"); //5-minutes befo select interval in past
                if ($spotTimeStart < $currTime) {
                    //this interval passed already.
                } elseif ($spacesAllowed >= 1) {
                    $spots+=$spacesAllowed;
                }
            } else {
                $spots+=$availebleSpaces;
            }
        }
    }

    return $spots;
}

function getBookings($date, $time, $serviceID=4) {
    $text = "";
    //if($time<10){ $time = "0".$time; }
    $q = "SELECT a.*, b.reason FROM bs_reserved_time_items a, bs_reserved_time b WHERE a.dateFrom LIKE '" . $date . " " . $time . "%' AND a.reservedID=b.id AND b.serviceID={$serviceID} ORDER BY a.dateFrom ASC LIMIT 1";
    $res = mysql_query($q);
    if (mysql_num_rows($res) > 0) {
        while ($rr = mysql_fetch_assoc($res)) {
            $text .= TXT_RESERVED . $rr["reason"] . "<br/>";
        }
    }
    $q = "SELECT bs_reservations.* FROM `bs_reservations` INNER JOIN bs_reservations_items  on bs_reservations_items.reservationID = bs_reservations.id  WHERE (bs_reservations.status='1' OR bs_reservations.status='4') AND bs_reservations_items.reserveDateFrom LIKE '" . $date . " " . $time . "%' AND `bs_reservations`.serviceID={$serviceID} ORDER BY bs_reservations_items.reserveDateFrom ASC  LIMIT 1";
    $res = mysql_query($q);
    if (mysql_num_rows($res) > 0) {
        while ($rr = mysql_fetch_assoc($res)) {
            $text .= "<a href='wington-bookings-edit.php?id=" . $rr["id"] . "'>" . $rr["name"] . " (" . $rr["phone"] . ")</a><br/>";
        }
    }
    return $text;
}


function getStartEndTime($day, $serviceID=4) {
    $tt = array();
    $tt[0] = 0;
    $tt[1] = 0;
    $q = "SELECT * FROM bs_service_settings WHERE serviceId='{$serviceID}'";
    $res = mysql_query($q);
    $rr = mysql_fetch_assoc($res);
    /* if(!empty($rr[$day."_from"]) && $rr[$day."_from"]!="N/A"){ $from = explode(":",$rr[$day."_from"]); } else { $from[0]=0; }
      if(!empty($rr[$day."_to"]) && $rr[$day."_to"]!="N/A"){ $to = explode(":",$rr[$day."_to"]);} else { $to[0]=0; } */ //LEFTOVERS FROM V2
    /* if(!empty($rr[$day."_from"]) && $rr[$day."_from"]!="N/A" && $rr[$day."_from"]!="0"){ $from = $rr[$day."_from"]/60; } else { $from=0; }
    if(!empty($rr[$day."_to"]) && $rr[$day."_to"]!="N/A" && $rr[$day."_to"]!="0"){ $to = $rr[$day."_to"]/60;} else { $to=0; } */
    /* $tt[0]= $from;
      $tt[1]=$to;
      $tt[2]= $from*60;
      $tt[3]=$to*60; */
      if (!empty($rr[$day . "_from"]) && $rr[$day . "_from"] != "N/A" && $rr[$day . "_from"] != "0") {
        $from = $rr[$day . "_from"];
    } else {
        $from = 0;
    }
    if (!empty($rr[$day . "_to"]) && $rr[$day . "_to"] != "N/A" && $rr[$day . "_to"] != "0") {
        $to = $rr[$day . "_to"];
    } else {
        $to = 0;
    }

    $tt[0] = ($from - ($from % 60)) / 60;
    $tt[1] = ($to - ($to % 60)) / 60;
    $tt[2] = $from;
    $tt[3] = $to;
    //print var_dump($tt);
    return $tt;
}

function setupCalendar1($iMonth, $iYear, $serviceID=4) {
    global $baseDir;
    $calendar="";
    $startDay = getFirstDay($serviceID);
    $thismonth = false;

    $iMonth2 = date('m', strtotime(date("Y") . "-" . $iMonth . "-01"));
    if (!$iMonth || !$iYear) {
        $iMonth = date('n');
        $iYear = date('Y');
    }

    ############################## BUILD BASE CALENDAR ################################
    $aCalendar = buildCalendar($iMonth, $iYear, $serviceID);
    list($iPrevMonth, $iPrevYear) = prevMonth($iMonth, $iYear);
    list($iNextMonth, $iNextYear) = nextMonth($iMonth, $iYear);
    $iCurrentMonth = date('n');
    $iCurrentYear = date('Y');
    $iCurrentDay = '';
    if (($iMonth == $iCurrentMonth) && ($iYear == $iCurrentYear)) {
        $iCurrentDay = date('d');
        $thismonth = true;
    }
    $iNextMonth = mktime(0, 0, 0, $iNextMonth, 1, $iNextYear);
    $iPrevMonth = mktime(0, 0, 0, $iPrevMonth, 1, $iPrevYear);
    $iCurrentDay = $iCurrentDay;
    $iCurrentMonth = mktime(0, 0, 0, $iMonth, 1, $iYear);

    ############################ PREPARE CALENDAR DATA #############################
    foreach ($aCalendar as $aWeek) {
        $calendar .= "<tr>";
        foreach ($aWeek as $iDay => $mDay) {
            if ($iDay == '') {
                $calendar .= "<td colspan=\"" . $mDay . "\"  class=\"cal_reg_off\">&nbsp;</td>";
            } else {
                if (strlen($iDay) == 1) {
                    $iDay = '0' . $iDay;
                }
                $datetocheck = $iYear . "-" . $iMonth2 . "-" . $iDay;



                if ($datetocheck < date("Y-m-d")) {
                    $calendar .= "<td id=\"" . $iDay . "\" class='cal_reg_off'>" . $iDay . "</td>";
                } else {

                    $admReserve = checkAdminReserve($datetocheck, $serviceID);
                    //we need to check reserved time by admin, in case this day is booked off by him.
                    ######################### EVENT CHECKER ###################################################################################################
                    $query = "SELECT * FROM bs_events WHERE eventDate LIKE '%" . $datetocheck . "%' AND serviceID='{$serviceID}' ORDER BY eventDate ASC ";

                    $result = mysql_query($query);
                    if (mysql_num_rows($result) > 0) {
                        //we have events for this day!

                        if (!empty($admReserve)) {
                            $calendar .= "<td id=\"" . $iDay . "\" class='cal_reg_off'>" . $iDay . "</td>";
                        } else {
                            $event_num = mysql_num_rows($result);
                            //we need to check if at least one event has spaces. if yes then { $bgClass="cal_reg_on";  } else { $bgClass="cal_reg_off"; }
                            $event_available = false;
                            $event_count = 0;
                            $text = "";

                            while ($row = mysql_fetch_assoc($result)) {
                                $spaces_left = getSpotsLeftForEvent($row["id"]);
                                if ($spaces_left > 0) {
                                    $event_available = true;
                                    $event_count++;
                                }

                                if (getServiceSettings($serviceID, 'show_event_titles')) {
                                    $text.="<div>{$row['title']}</div>";
                                }
                                if (getServiceSettings($serviceID, 'show_event_image') && !empty($row['path'])) {

                                    $text.="<div><img src='{$baseDir}{$row['path']}' width='40'></div>";
                                }
                            }

                            $text = empty($text) ? $event_num . EVENTS_SCHEDULED : $text;

                            if ($event_available) {
                                $bgClass = "cal_reg_on";
                            } else {
                                $bgClass = "cal_reg_off";
                            }

                            $calendar .= "<td id=\"" . $iDay . "\"";
                            if ($iCurrentDay != $iDay) {
                                $var = "";
                            } else {
                                $var = "_today";
                            }
                            $click = getOption('use_popup') ? "getLightbox2('" . $datetocheck . "'," . $serviceID . ");" : "location.href='event-booking.php?date=" . urlencode($datetocheck) . "&serviceID=" . $serviceID . "'";
                            if ($iCurrentDay != $iDay && $bgClass != "cal_reg_off") {
                                $calendar .= "onmouseover=\"getElementById('" . $iDay . "').className='mainmenu5';\" onmouseout=\"getElementById('" . $iDay . "').className='" . $bgClass . "';\" onClick=\"{$click}\"";
                            } else if ($iCurrentDay == $iDay && $bgClass != "cal_reg_off") {
                                $calendar .= "onmouseover=\"getElementById('" . $iDay . "').className='mainmenu5';\" onmouseout=\"getElementById('" . $iDay . "').className='" . $bgClass . $var . "';\" onClick=\"{$click}\"";
                            }
                            $calendar .= "class=\"" . $bgClass . $var . "\">" . $iDay;
                            //check if this day available for booking or not.
                            if ($bgClass == "cal_reg_off") {
                                $calendar .= "<span class='hide-me-for-nojs'><br/>".ZERO_SPACES."</span><noscript><br/>".ZERO_SPACES."</noscript>";
                            } else {
                                $calendar .= "<div class='cal_text hide-me-for-nojs'>" . $text . "</div><noscript><br/><a href='event-booking-nojs.php?date=" . $datetocheck . "'>" . $text . "</a></noscript>";
                            }
                            $calendar .= "</td>";
                        }
                    } else {
                        //we dont have events for this day, lets check bookings.
                        $cur_spots = checkSpotsLeft($datetocheck, $serviceID);
                        if ($cur_spots > 0) {
                            $bgClass = "cal_reg_on";
                        } else {
                            $bgClass = "cal_reg_off";
                        }
                        $calendar .= "<td id=\"" . $iDay . "\"";
                        if ($iCurrentDay != $iDay) {
                            $var = "";
                        } else {
                            $var = "_today";
                        }
                        $click = getOption('use_popup') ? "getLightbox('" . $datetocheck . "'," . $serviceID . ");" : "location.href='booking.php?date=" . urlencode($datetocheck) . "&serviceID=" . $serviceID . "'";
                        if ($iCurrentDay != $iDay && $bgClass != "cal_reg_off") {
                            $calendar .= "onmouseover=\"getElementById('" . $iDay . "').className='mainmenu5';\" onmouseout=\"getElementById('" . $iDay . "').className='" . $bgClass . "';\" onClick=\"{$click}\"";
                        } else if ($iCurrentDay == $iDay && $bgClass != "cal_reg_off") {
                            $calendar .= "onmouseover=\"getElementById('" . $iDay . "').className='mainmenu5';\" onmouseout=\"getElementById('" . $iDay . "').className='" . $bgClass . $var . "';\" onClick=\"{$click}\"";
                        }
                        $calendar .= "class=\"" . $bgClass . $var . "\">" . $iDay;
                        //check if this day available for booking or not.
                        $showSpaces = getServiceSettings($serviceID, 'show_spaces_left');
                        if ($bgClass == "cal_reg_off") {
                            $calendar .= "<span class='hide-me-for-nojs'><br/>" . ($showSpaces ? ZERO_SPACES : "") . "</span><noscript><br/>" . ($showSpaces ? ZERO_SPACES : "") . "</noscript>";
                        } else {
                            $spotsText = ($showSpaces) ? $cur_spots . SPC_AVAIL : "";
                            $calendar .= "<div class='cal_text hide-me-for-nojs'>" . $spotsText . "</div><noscript><br/><a href='booking-nojs.php?date=" . $datetocheck . "'>" . $spotsText . "</a></noscript>";
                        }
                        $calendar .= "</td>";
                    } // end EVENT CHECKER.
                    ########################################################################################################################################
                } //end if iDay
            }
        }
        $calendar .= "</tr>";
    } //end foreach
    ############################## END PREPARE CALENDAR DATA ################################

    return $calendar;
}

function setupSmallCalendar($iMonth, $iYear, $serviceID=4) {
    global $baseDir;
    $linkPrefix = "http://" . $_SERVER['SERVER_NAME'] . $baseDir;
    $startDay = getFirstDay($serviceID);
    $thismonth = false;

    $iMonth2 = date('m', strtotime(date("Y") . "-" . $iMonth . "-01"));
    if (!$iMonth || !$iYear) {
        $iMonth = date('n');
        $iYear = date('Y');
    }

    ############################## BUILD BASE CALENDAR ################################
    $aCalendar = buildCalendar($iMonth, $iYear, $serviceID);
    list($iPrevMonth, $iPrevYear) = prevMonth($iMonth, $iYear);
    list($iNextMonth, $iNextYear) = nextMonth($iMonth, $iYear);
    $iCurrentMonth = date('n');
    $iCurrentYear = date('Y');
    $iCurrentDay = '';
    if (($iMonth == $iCurrentMonth) && ($iYear == $iCurrentYear)) {
        $iCurrentDay = date('d');
        $thismonth = true;
    }
    $iNextMonth = mktime(0, 0, 0, $iNextMonth, 1, $iNextYear);
    $iPrevMonth = mktime(0, 0, 0, $iPrevMonth, 1, $iPrevYear);
    $iCurrentDay = $iCurrentDay;
    $iCurrentMonth = mktime(0, 0, 0, $iMonth, 1, $iYear);

    ############################ PREPARE CALENDAR DATA #############################
    foreach ($aCalendar as $aWeek) {
        $calendar .= "<tr>";
        foreach ($aWeek as $iDay => $mDay) {
            if ($iDay == '') {
                $calendar .= "<td colspan=\"" . $mDay . "\"  class=\"cal_reg_off\">&nbsp;</td>";
            } else {
                if (strlen($iDay) == 1) {
                    $iDay = '0' . $iDay;
                }
                $datetocheck = $iYear . "-" . $iMonth2 . "-" . $iDay;



                if ($datetocheck < date("Y-m-d")) {
                    $calendar .= "<td id=\"" . $iDay . "\" class='cal_reg_off'><div class='day_number'>" . $iDay . "</div></td>";
                } else {


                    //we need to check reserved time by admin, in case this day is booked off by him.
                    ######################### EVENT CHECKER ###################################################################################################
                    $query = "SELECT * FROM bs_events WHERE eventDate <= '" . $datetocheck . " 23:59' AND eventDateEnd >= '" . $datetocheck . " 00:00' AND serviceID='{$serviceID}' ORDER BY eventDate ASC ";

                    $result = mysql_query($query);

                    $bgClass = "cal_reg_off";
                    $text = "";
                    $textTime = "";
                    if (mysql_num_rows($result) > 0) {
                        //we have events for this day!


                        $bgClass = "cal_reg_on";
                        $event_num = mysql_num_rows($result);
                        //we need to check if at least one event has spaces. if yes then { $bgClass="cal_reg_on";  } else { $bgClass="cal_reg_off"; }
                        $event_available = false;
                        $event_count = 0;





                        while ($row = mysql_fetch_assoc($result)) {

                            $spaces_left = getSpotsLeftForEvent($row["id"]);
                            //$click = "location.href='{$linkPrefix}event-booking.php?eventID=" . urlencode($row['id']) . "&serviceID=" . $serviceID . "'";
                            $click = "getLightbox2('" . $row['id'] . "'," . $serviceID . ",'" . $datetocheck . "');";
                            if ($spaces_left > 0) {
                                $event_available = true;
                                $event_count++;
                            }
                            $text.="<div onclick=\"{$click};return false;\" class='eventConteiner'>";
                            if (getServiceSettings($serviceID, 'show_event_titles')) {
                                $text.="<div>{$row['title']}</div>";
                            }
                            if (getServiceSettings($serviceID, 'show_event_image') && !empty($row['path'])) {

                                $text.="<div><img src='{$baseDir}{$row['path']}' width='40'></div>";
                            }
                            $text.="</div>";
                        }
                    }
                    //we dont have events for this day, lets check bookings.
                    $cur_spots = checkSpotsLeft($datetocheck, $serviceID);
                    $showSpaces = getServiceSettings($serviceID, 'show_spaces_left');

                    if ($cur_spots > 0) {
                        $bgClass = "cal_reg_on";
                        //$clickTime = "location.href='{$linkPrefix}booking.php?date=" . urlencode($datetocheck) . "&serviceID=" . $serviceID . "'";
                        $clickTime = "getLightbox('" . $datetocheck . "'," . $serviceID . ");";
                        $spotsText = ($showSpaces) ?  $cur_spots . " pladser ledige" : 'book';
                        $spotsText = '<div class="cal_text hide-me-for-nojs" onclick="' . $clickTime . '">' . $spotsText . "</div>";
                        //$text .="<div class='eventConteiner' onclick=\"{$clickTime}\">{$spotsText}</div>";
                        $textTime .=$spotsText;
                    } else {
                        $spotsText = "";
                        //$text .="<div class='eventConteiner' onclick=\"{$clickTime}\">{$spotsText}</div>";
                        $textTime .=$spotsText;
                        $clickTime = '';
                    }

                    // end EVENT CHECKER.
                    ########################################################################################################################################

                    $calendar .= "<td id=\"" . $iDay . "\"";
                    if ($iCurrentDay != $iDay) {
                        $var = "";
                    } else {
                        $var = "_today";
                    }

                    if ($iCurrentDay != $iDay && $bgClass != "cal_reg_off") {
                        $calendar .= "onmouseover=\"this.className='mainmenu5';\" onmouseout=\"this.className='" . $bgClass . "';\" ";
                    } else if ($iCurrentDay == $iDay && $bgClass != "cal_reg_off") {
                        $calendar .= "onmouseover=\"this.className='mainmenu5';\" onmouseout=\"this.className='" . $bgClass . $var . "' \"";
                    }
                    $calendar .= "class=\"" . $bgClass . $var . "\"><div class='day_number'>" . $iDay;
                    if (!empty($textTime) || !empty($text)) {
                        $calendar .="<div class='showInfo'>" . $textTime . $text . "<b></b></div>";
                    }
                    //check if this day available for booking or not.
                    /* if(Empty($text)){
                      $calendar .= "<span class='hide-me-for-nojs'><br/>0 spaces available</span><noscript><br/>0 spaces available</noscript>";
                      } else {
                      $calendar .= "<div class='cal_text hide-me-for-nojs'>".$text."</div><noscript><br/><a href='event-booking-nojs.php?date=".$datetocheck."'>".$text."</a></noscript>";
                  } */
                  $calendar .= "</div></td>";
                } //end if iDay
            }
        }
        $calendar .= "</tr>";
    } //end foreach
    ############################## END PREPARE CALENDAR DATA ################################

    return $calendar;
}

function setupCalendar($iMonth, $iYear, $serviceID=4) {
    $calendar="";
    global $baseDir;
    $startDay = getFirstDay($serviceID);
    $thismonth = false;

    $iMonth2 = date('m', strtotime(date("Y") . "-" . $iMonth . "-01"));
    if (!$iMonth || !$iYear) {
        $iMonth = date('n');
        $iYear = date('Y');
    }

    ############################## BUILD BASE CALENDAR ################################
    $aCalendar = buildCalendar($iMonth, $iYear, $serviceID);
    list($iPrevMonth, $iPrevYear) = prevMonth($iMonth, $iYear);
    list($iNextMonth, $iNextYear) = nextMonth($iMonth, $iYear);
    $iCurrentMonth = date('n');
    $iCurrentYear = date('Y');
    $iCurrentDay = '';
    if (($iMonth == $iCurrentMonth) && ($iYear == $iCurrentYear)) {
        $iCurrentDay = date('d');
        $thismonth = true;
    }
    $iNextMonth = mktime(0, 0, 0, $iNextMonth, 1, $iNextYear);
    $iPrevMonth = mktime(0, 0, 0, $iPrevMonth, 1, $iPrevYear);
    $iCurrentDay = $iCurrentDay;
    $iCurrentMonth = mktime(0, 0, 0, $iMonth, 1, $iYear);

    ############################ PREPARE CALENDAR DATA #############################
    foreach ($aCalendar as $aWeek) {
        $calendar .= "<tr>";
        foreach ($aWeek as $iDay => $mDay) {
            if ($iDay == '') {
                $calendar .= "<td colspan=\"" . $mDay . "\"  class=\"cal_reg_off\">&nbsp;</td>";
            } else {
                if (strlen($iDay) == 1) {
                    $iDay = '0' . $iDay;
                }
                $datetocheck = $iYear . "-" . $iMonth2 . "-" . $iDay;



                if ($datetocheck < date("Y-m-d")) {
                    $calendar .= "<td id=\"" . $iDay . "\" class='cal_reg_off'>" . $iDay . "</td>";
                } else {


                    //we need to check reserved time by admin, in case this day is booked off by him.
                    ######################### EVENT CHECKER ###################################################################################################
                    $query = "SELECT * FROM bs_events WHERE eventDate <= '" . $datetocheck . " 23:59' AND eventDateEnd >= '" . $datetocheck . " 00:00' AND serviceID='{$serviceID}' ORDER BY eventDate ASC ";
                    //print $query;
                    $result = mysql_query($query);
                    //$calendar .= "<td id=\"".$iDay."\" class='cal_reg_off'>".$iDay."</td>";
                    $bgClass = "cal_reg_off";
                    $text = "";
                    $textTime = "";
                    if (mysql_num_rows($result) > 0) {
                        //we have events for this day!


                        $bgClass = "cal_reg_on";
                        $event_num = mysql_num_rows($result);
                        //we need to check if at least one event has spaces. if yes then { $bgClass="cal_reg_on";  } else { $bgClass="cal_reg_off"; }
                        $event_available = false;
                        $event_count = 0;





                        while ($row = mysql_fetch_assoc($result)) {

                            $spaces_left = getSpotsLeftForEvent($row["id"]);
                            $click = getOption('use_popup') ? "getLightbox2('" . $row['id'] . "'," . $serviceID . ",'".$datetocheck."');" : "location.href='event-booking.php?eventID=" . urlencode($row['id']) . "&serviceID=" . $serviceID . "&date=".$datetocheck."'";
                            if ($spaces_left > 0) {
                                $event_available = true;
                                $event_count++;

                            }else{
                                $click = "javascript:;";
                            }
                            $text.="<div onclick=\"{$click};return false;\" class='eventConteiner ".($spaces_left<1?"disabled":"")."'>";
                            if (getServiceSettings($serviceID, 'show_event_titles')) {
                                $text.="<div>{$row['title']}</div>";
                            }else{
                                $text.="<div>Event</div>";
                            }
                            if (getServiceSettings($serviceID, 'show_event_image') && !empty($row['path'])) {

                                $text.="<div><img src='{$baseDir}{$row['path']}' width='40'></div>";
                            }
                            $text.="</div>";
                        }
                    }
                    //we dont have events for this day, lets check bookings.
                    $cur_spots = checkSpotsLeft($datetocheck, $serviceID);
                    $showSpaces = getServiceSettings($serviceID, 'show_spaces_left');
                    if ($cur_spots > 0) {
                        $bgClass = "cal_reg_on";
                        $clickTime = getOption('use_popup') ? "getLightbox('" . $datetocheck . "'," . $serviceID . ");" : "location.href='booking.php?date=" . urlencode($datetocheck) . "&serviceID=" . $serviceID . "'";
                        $spotsText = ($showSpaces) ? '<div class="cal_text hide-me-for-nojs" onclick="' . $clickTime . '">' . $cur_spots . SPC_AVAIL ."</div>" : '<div class="cal_text hide-me-for-nojs" onclick="' . $clickTime . '">' . BOOK_NOW . "</div>";
                        //$text .="<div class='eventConteiner' onclick=\"{$clickTime}\">{$spotsText}</div>";
                        $textTime .=$spotsText;
                    } else {
                        $spotsText = ($showSpaces) ? "<span class='hide-me-for-nojs'><br/>" . $cur_spots . SPC_AVAIL."</span>" : "";
                        //$text .="<div class='eventConteiner' onclick=\"{$clickTime}\">{$spotsText}</div>";
                        $textTime .=$spotsText;
                        $clickTime = '';
                    }

                    // end EVENT CHECKER.
                    ########################################################################################################################################

                    $calendar .= "<td attr='".$cur_spots."' id=\"" . $iDay . "\"";
                    if ($iCurrentDay != $iDay) {
                        $var = "";
                    } else {
                        $var = "_today";
                    }

                    if ($iCurrentDay != $iDay && $bgClass != "cal_reg_off") {
                        $calendar .= "onclick=\" " . $clickTime . " \" onmouseover=\"getElementById('" . $iDay . "').className='mainmenu5';\" onmouseout=\"getElementById('" . $iDay . "').className='" . $bgClass . "';\" ";
                    } else if ($iCurrentDay == $iDay && $bgClass != "cal_reg_off") {
                        $calendar .= "onclick=\" " . $clickTime . " \" onmouseover=\"getElementById('" . $iDay . "').className='mainmenu5';\" onmouseout=\"getElementById('" . $iDay . "').className='" . $bgClass . $var . "' \"";
                    }
                    $calendar .= "class=\"" . $bgClass . $var . "\">" . $iDay;
                    $calendar .=$textTime . $text;
                    //check if this day available for booking or not.
                    /* if(Empty($text)){
                      $calendar .= "<span class='hide-me-for-nojs'><br/>0 pladser ledige </span><noscript><br/>0 pladser ledige</noscript>";
                      } else {
                      $calendar .= "<div class='cal_text hide-me-for-nojs'>".$text."</div><noscript><br/><a href='event-booking-nojs.php?date=".$datetocheck."'>".$text."</a></noscript>";
                  } */
                  $calendar .= "</td>";
                } //end if iDay
            }
        }
        $calendar .= "</tr>";
    } //end foreach
    ############################## END PREPARE CALENDAR DATA ################################

    return $calendar;
}

function buildCalendar($iMonth, $iYear, $serviceID=4) {
    $myFirstDay = getFirstDay($serviceID);
    $iFirstDayTimeStamp = mktime(0, 0, 0, $iMonth, 1, $iYear);
    $iFirstDayNum = date('w', $iFirstDayTimeStamp);
    $iFirstDayNum++;
    $iDayCount = date('t', $iFirstDayTimeStamp);
    $aCalendar = array();
    if ($myFirstDay == "0") {
        //SUNDAY
        if ($iFirstDayNum > 1) {
            $aCalendar[1][''] = $iFirstDayNum - 1; // how many empty squares before actual day 1.
        }
        $i = 1;
        $j = 1;

        while ($j <= $iDayCount) {
            $aCalendar[$i][$j] = $j;
            if (floor(($j + $iFirstDayNum - 1) / 7) >= $i) {
                $i++;
            }
            $j++;
        }
        if ((isset($aCalendar[$i])) && ($iM = count($aCalendar[$i])) < 7) {
            $aCalendar[$i][''] = 7 - $iM;
        }
    } else if ($myFirstDay == "1") {
        //MONDAY
        $iFirstDayNum--;
        if ($iFirstDayNum > 1 && $iFirstDayNum < 6) {
            //echo "off1";
            $tmp = 1;
            $aCalendar[1][''] = $iFirstDayNum - $tmp;
            $i = 1;
            $j = 1;

            while ($j <= $iDayCount) {
                $aCalendar[$i][$j] = $j;
                if (floor(($j + $iFirstDayNum - $tmp) / 7) >= $i) {
                    $i++;
                }
                $j++;
            }
            if ((isset($aCalendar[$i])) && ($iM = count($aCalendar[$i])) < 7) {
                $aCalendar[$i][''] = 7 - $iM; //last row - how many empty squares.
            }
        } else if ($iFirstDayNum == 0) {

            //echo "off2";
            $tmp = 1;
            $aCalendar[1][''] = 6;
            $i = 1;
            $j = 1;

            while ($j <= $iDayCount) {
                $aCalendar[$i][$j] = $j;
                if (floor(($j + $iFirstDayNum + 6) / 7) >= $i) {
                    $i++;
                }
                $j++;
            }
            if ((isset($aCalendar[$i])) && ($iM = count($aCalendar[$i])) < 7) {
                $aCalendar[$i][''] = 7 - $iM; //last row - how many empty squares.
            }
        } else if ($iFirstDayNum == 6) {

            //echo "off2";
            $tmp = 1;
            $aCalendar[1][''] = 5;
            $i = 1;
            $j = 1;

            while ($j <= $iDayCount) {
                $aCalendar[$i][$j] = $j;
                if (floor(($j + $iFirstDayNum - 1) / 7) >= $i) {
                    $i++;
                }
                $j++;
            }
            if ((isset($aCalendar[$i])) && ($iM = count($aCalendar[$i])) < 7) {
                $aCalendar[$i][''] = 7 - $iM; //last row - how many empty squares.
            }
        } else {
            //echo "off3";
            //echo $iFirstDayNum;
            $tmp = 1;
            $i = 1;
            $j = 1;

            while ($j <= $iDayCount) {
                $aCalendar[$i][$j] = $j;
                if (floor(($j + $iFirstDayNum - $tmp) / 7) >= $i) {
                    $i++;
                }
                $j++;
            }
            if ((isset($aCalendar[$i])) && ($iM = count($aCalendar[$i])) < 7) {
                $aCalendar[$i][''] = 7 - $iM; //last row - how many empty squares.
            }
        }
    }
    return $aCalendar;
}


function admindisplaycalendarbookings($shopid, $month){
    global $basehref;
    global $db;
  //  $month = "";//temp
//    $month = date("m");


    $db->where("shopid", $shopid);
  //  $db->where ("MONTH(bookingdate) = $month"); enable this again sometime

    $booking = $db->get('bookings');
    $bookingcopy = $booking;
             //   d($booking);
                //$booking['bookingdate'];
                //$booking['bookingtime'];

    $devices_with_id_as_key = devices_with_id_as_key();
    foreach ($booking as $booking) {
                    # code...

        $bookingid = $booking['Id'];
        $bookingbrand = $booking['brand'];
        $bookingmodels = $booking['models'];
        $bookingtime = $booking['bookingtime'];
                $bookingtime = substr_replace($bookingtime, ':', 2, 0); // http://stackoverflow.com/questions/19452392/adding-a-character-in-the-middle-of-a-string
                $bookingtime .= ":00";
                $bookinguserid = $booking['userid'];
                $bookingdate = $booking['bookingdate'];
                $bookingfulltime = "$bookingdate"."T"."$bookingtime";

                if($bookingmodels !== '0'){
                    $bookingmodels = $devices_with_id_as_key[$bookingmodels]['Title'];
                }
                echo "
                {
                    title: '$bookingmodels $bookinguserid $bookingtime $bookingbrand',
                    url: '$basehref/booking-item/$bookingid',
                    start: '$bookingfulltime'
                }";

                if (next($bookingcopy )) {
                            echo ','; // Add comma for all elements instead of last
                        }

                    }

                }









                function getcarttotalvalue(){


                    $totalvalue = 0;
                    if(isset($_SESSION['cart'])){


                        foreach ($_SESSION['cart'] as $uid => $values) {
    //    print "$uid {\n";
                            if(isset($values['quantity'])){
                                $quantity = $values['quantity'];
                            }else{
                                $quantity = '1';
                            }


                            if(isset($_SESSION['VIP'])){


                                if($_SESSION['VIP'] == 1){
                                    $totalprice = getvippricefromuid($uid);
                                }else{
                                    $totalprice = getpricefromuid($uid);
                                }
                            }else{
                                $totalprice = getpricefromuid($uid);
                            }

                            $totalpricecombined = $totalprice * $quantity;
                            $totalvalue = $totalvalue + $totalpricecombined;
                        }

                    }
                    return $totalvalue;

                }




                function productpagevariationsdropdowns($productid){
                    global $db;
                    $db->orderBy("ProductAttributeGroupId","asc");
//$db->groupBy ("ProductAttributeGroupId");
                    $db->where ("ProductId", $productid);
//$products = $db->get ("productvariations pv", null, "u.name, p.productName");
                    $variations = $db->get("productvariations");
//d($variations);

                    return $variations;

                }


                function productattributes_with_id_as_key(){
                    global $db;
                    $productattributes_select = $db->get('productattributes');

                    $productattributes_with_id_as_key = array();

                    for ($i = 0; $i < count($productattributes_select); $i++) {

                        $array_key_tmp = array_keys($productattributes_select[$i]);

                        $productattributes_with_id_as_key[$productattributes_select[$i]["Id"]] = array();

                        for ($j = 0; $j < count($array_key_tmp); $j++) {

                            $productattributes_with_id_as_key[$productattributes_select[$i]["Id"]][$array_key_tmp[$j]] = $productattributes_select[$i][$array_key_tmp[$j]];

                        }

                    }
                    return $productattributes_with_id_as_key;
                }

                function productattributegroups_with_id_as_key(){
                    global $db;
                    $productattributegroups_select = $db->get('productattributegroups');

                    $productattributegroups_with_id_as_key = array();

                    for ($i = 0; $i < count($productattributegroups_select); $i++) {

                        $array_key_tmp = array_keys($productattributegroups_select[$i]);

                        $productattributegroups_with_id_as_key[$productattributegroups_select[$i]["Id"]] = array();

                        for ($j = 0; $j < count($array_key_tmp); $j++) {

                            $productattributegroups_with_id_as_key[$productattributegroups_select[$i]["Id"]][$array_key_tmp[$j]] = $productattributegroups_select[$i][$array_key_tmp[$j]];

                        }

                    }
                    return $productattributegroups_with_id_as_key;
                }



                function productpagedisplayvariations($productid){

                    $productvariations = productpagevariationsdropdowns($productid);
//d($productvariations);
                    $grouped = array_group_by( $productvariations, "ProductAttributeGroupId" );
//d($grouped);

                    $productattributes_with_id_as_key = productattributes_with_id_as_key();
                    $productattributegroups_with_id_as_key = productattributegroups_with_id_as_key();




                    $i = 0;
                    foreach ($grouped as $grouped) {
    # code...
   // var_dump($grouped);
 //   echo $grouped[0]['Id'];
   // d($grouped);
    //$Price = $grouped[$i]['Price'];
    //$DisplayPriceInTitle = $grouped[$i]['DisplayPriceInTitle'];
                        $GroupID =  $grouped[$i]['ProductAttributeGroupId'];
                        $GroupIDTitle = $productattributegroups_with_id_as_key[$GroupID]['Title'];
  //  echo $AttributeID;
                        echo "
                        <select id='attrgroup$GroupID' class='attributegroupdropdown form-control'>
                        <option data-price='0' selected>$GroupIDTitle</option>
                        ";

                        foreach($grouped as $groupednested){
                            $AttributeID = $groupednested['ProductAttributeId'];
                            $Price = $groupednested['Price'];
                            $DisplayPriceInTitle = $groupednested['DisplayPriceInTitle'];

                            $AttributeTitle = $productattributes_with_id_as_key[$AttributeID]['Title'];

                            if($DisplayPriceInTitle == 0){
                                echo "<option value='$AttributeID' data-price='$Price'>$AttributeTitle</option> ";
                            }else{
                                echo "<option value='$AttributeID' data-price='$Price'>$AttributeTitle ($Price) DKK</option> ";
                            }

                        }
                        echo "
                        </select>
                        ";
                        $i = $i + 1;

                    }


                }






                /* quickpay related */


                function checkpaymentstatusfromorderid($orderid){
                    global $db;
                    $db->where("order_id", $orderid);
                    $dbinfo = $db->getOne('quickpay_transactions');
           //     $name    = $zipinfo['city'];
                    return $dbinfo;
                }

/*
function flatten_params($obj, $result = array(), $path = array()) {
    if (is_array($obj)) {
        foreach ($obj as $k => $v) {
            $result = array_merge($result, flatten_params($v, $result, array_merge($path, array($k))));
        }
    } else {
        $result[implode("", array_map(function($p) { return "[{$p}]"; }, $path))] = $obj;
    }

    return $result;
}



function sign($params, $api_key) {
    $flattened_params = flatten_params($params);
    ksort($flattened_params);
    $base = implode(" ", $flattened_params);

    return hash_hmac("sha256", $base, $api_key);
}
*/

function sign_callback($base, $private_key) {
  return hash_hmac("sha256", $base, $private_key);
}












/* LOGIN RELATED */


// PLANGY
function loginactioncustomer($username, $password, $destination, $cookie, $isPartner = 0){
    global $db;
    global $basehref;
    $passwordhashed = doubleSalt($password);

    // echo $passwordhashed; exit;
    $db->where("Email", $username);
    $db->where("PasswordHash", $passwordhashed);
    $db->where("EmployeeGroup",3,'!=');
    $db->where("is_instructor",0);
    $db->where("is_verify", 1);
    $userinfo = $db->getOne('users');
    if(!empty($userinfo)){
        $_SESSION['errormsg'] = FALSE;
        $_SESSION['UserId']             = $userinfo['Id'];
        $_SESSION['Key']                = $userinfo['Key'];
        $_SESSION['Language']           = $userinfo['Language'];
        $_SESSION['Firstname']          = $userinfo['Firstname'];
        $_SESSION['Lastname']           = $userinfo['Lastname'];
        $_SESSION['Initials']           = $userinfo['Initials'];
        $_SESSION['AvatarBackgroundColor'] = $userinfo['AvatarBackgroundColor'];
        $_SESSION['AvatarTextColor']    = $userinfo['AvatarTextColor'];
        $_SESSION['Email']              = $userinfo['Email'];
        $_SESSION['CustomerId']         = $userinfo['CustomerId'];
        $_SESSION['Admin']              = $userinfo['Admin'];
        $_SESSION['Superadmin']         = $userinfo['Superadmin'];
        $_SESSION['Type']               = $userinfo['Type'];
        $_SESSION['EmployeeGroup']      = $userinfo['EmployeeGroup'];
        $_SESSION['CreateDate']         = $userinfo['CreateDate'];
        $_SESSION['ParentId']           = $userinfo['ParentId'];
        $_SESSION['is_partner']         = $userinfo['is_partner'];
        $_SESSION['whatnow_access']         = 0;
        // $_SESSION['coach_id'] = ($userinfo['EmployeeGroup'] == 1) ? 0 : '970';
        if($userinfo['EmployeeGroup'] == 4){
            $_SESSION['coach_id'] =  0;
        }elseif($userinfo['special_page_access']>=2){
            if($userinfo['coach_ids']){
                $coach_ids = explode(',',$userinfo['coach_ids']);
                $_SESSION['coach_id'] =   (isset($coach_ids[0])) ? $coach_ids[0] : 0;
            }else{
                $_SESSION['coach_id'] =   0;
            }
        }else{
            $_SESSION['coach_id'] = 0;
        }
        //$_SESSION['DashShiftlistSetting']         = $userinfo['DashShiftlistSetting'];

        $customerinfo   = customer_data_by_id($userinfo['CustomerId']);

        //$_SESSION['Country']            = $customerinfo['Country'];
        //$_SESSION['StripeCustomerId']   = $customerinfo['StripeCustomerId'];
        //$_SESSION['Active']             = $customerinfo['Active'];
        //$_SESSION['CustomerType']       = $customerinfo['Type'];

        //Alter department and current department
        //$leaderinfo     = departments_by_leader($userinfo['CustomerId'],$_SESSION['UserId'],$userinfo['Type']);

        /*if($leaderinfo != false){
            $_SESSION['DepartmentId'] = $leaderinfo[0]['Id'];
            $_SESSION['CurrentDepartment'] = $leaderinfo[0]['Id'];
        }*/

        $data = Array (
            'LastSigninTimestamp' => $db->now(),
            'LastSigninIP'  => $_SERVER['REMOTE_ADDR']
        );

        $db->where ('Id', $userinfo['Id']);

        if ($db->update ('customers', $data)){
        }

        //setcookie('visitor', serialize($cookie), time() + (86400 * 30), "/");



        // Set login cookie
        $check_logintoken = false;
        if(isset($_COOKIE['accesstoken'])){

            $db->where('Token',$_COOKIE['accesstoken']);
            $check_logintoken = $db->has('login');

        }

        if($check_logintoken == true){
            // Do nothing
        }else{
            // Create new token

            $token_userid = $_SESSION['UserId'];
            $token_device = $_SERVER['HTTP_USER_AGENT'];
            $token_cookie_value = 0;

            $token_cookie_date            = date('Y-m-d');
            $token_cookie_datetime        = date('Y-m-d H:i:s');
            $token_cookie_active_until    = date('Y-m-d',strtotime($token_cookie_date . "+30 days"));

            $token_cookie_ip              = $_SERVER['REMOTE_ADDR'];

            $token_cookie                 = rand(100,999).rand(100,999);
            $token_cookie                 = hash('md5', $token_cookie . 'sgEHrtole3rrESt35op' . $token_cookie_datetime . $token_cookie_ip);
            $token_cookie_until           = date('Y-m-d',strtotime($token_cookie_date . "+90 days"));


            $token_cookie_name            = "accesstoken";
            $token_cookie_value           = $token_cookie;
            setcookie($token_cookie_name, $token_cookie_value, time() + (86400 * 90), "/");


            $token_cookie_data = array(
                "UserId"  => $token_userid,
                "Token"   => $token_cookie_value,
                "Device"  => $token_device
            );

            $db->insert('login',$token_cookie_data);

        }


        // Login Updates
        logaction(2,0,0);
        loginattempt($username, $password, '0');


        if($_SESSION['EmployeeGroup']>0){
            // header("Location:".$basehref."trainer");
            header("Location:".$basehref."edit-user/".$userinfo['Id']);
        }else{
            if($isPartner == 1){

                // http://fitforkids:4200/login?token&MTQ1Nw==
                header("Location:".$basehref."edit-user/".$userinfo['Id']);
            }
            else{
            header("Location:".$basehref."overview");
        }
        }

    }else{
        $db->where("Email", $username);
        $db->where("EmployeeGroup",3,'!=');
        $db->where("is_instructor",0);
        $useremail_check = $db->getOne('users');

        $db->where("Email", $username);
        $db->where("PasswordHash", $passwordhashed);
        $db->where("EmployeeGroup",3,'!=');
        $db->where("is_instructor",0);
        $usercredential_check = $db->getOne('users');

        $db->where("Email", $username);
        $db->where("PasswordHash", $passwordhashed);
        $db->where("is_verify", 1);
        $db->where("EmployeeGroup",3,'!=');
        $db->where("is_instructor",0);
        $user_unverified = $db->getOne('users');

        if(empty($useremail_check)){
            $_SESSION['errormsg'] = 'invalid-emailId';
            header("Location:".$basehref."login");
        }elseif(empty($usercredential_check)){
            $_SESSION['errormsg'] = 'invalid-password';
            header("Location:".$basehref."login");
        }elseif(empty($user_unverified)){
              $_SESSION['errormsg'] = 'unverified-account';
              header("Location:".$basehref."login");
        }else{
            $_SESSION['errormsg'] = 'invalid-credential';
            header("Location:".$basehref."login");
        }
       // $_SESSION['errormsg'] = 'error1';
        loginattempt($username, $password, '1');
        //header("Location:".$basehref."login");
    }

    exit;
}

function loginactioninstructor($username, $password, $destination, $cookie, $isPartner = 0){
    global $db;
    global $basehref;
    $passwordhashed = doubleSalt($password);

    // echo $passwordhashed; exit;
    $db->where("Email", $username);
    $db->where("PasswordHash", $passwordhashed);
    $db->where("EmployeeGroup",3);
    $db->where("is_verify", 1);
    $userinfo = $db->getOne('users');
    if(empty($userinfo)){
        $db->where("Email", $username);
        $db->where("PasswordHash", $passwordhashed);
        $db->where("EmployeeGroup",0);
        $db->where("is_instructor",1);
        $db->where("is_verify", 1);
        $userinfo = $db->getOne('users');
    }
    if(!empty($userinfo)){
        $_SESSION['errormsg'] = FALSE;
        $_SESSION['UserId']             = $userinfo['Id'];
        $_SESSION['Key']                = $userinfo['Key'];
        $_SESSION['Language']           = $userinfo['Language'];
        $_SESSION['Firstname']          = $userinfo['Firstname'];
        $_SESSION['Lastname']           = $userinfo['Lastname'];
        $_SESSION['Initials']           = $userinfo['Initials'];
        $_SESSION['AvatarBackgroundColor'] = $userinfo['AvatarBackgroundColor'];
        $_SESSION['AvatarTextColor']    = $userinfo['AvatarTextColor'];
        $_SESSION['Email']              = $userinfo['Email'];
        $_SESSION['CustomerId']         = $userinfo['CustomerId'];
        $_SESSION['Admin']              = $userinfo['Admin'];
        $_SESSION['Superadmin']         = $userinfo['Superadmin'];
        $_SESSION['Type']               = $userinfo['Type'];
        $_SESSION['EmployeeGroup']      = $userinfo['EmployeeGroup'];
        $_SESSION['CreateDate']         = $userinfo['CreateDate'];
        $_SESSION['ParentId']           = $userinfo['ParentId'];
        $_SESSION['is_partner']         = $userinfo['is_partner'];
        $_SESSION['whatnow_access']         = 0;
        $_SESSION['questionnaries'] = 0;
        if($userinfo['EmployeeGroup'] == 4){
            $_SESSION['coach_id'] =  0;
        }elseif($userinfo['special_page_access']>=2){
            if($userinfo['coach_ids']){
                $coach_ids = explode(',',$userinfo['coach_ids']);
                $_SESSION['coach_id'] =   (isset($coach_ids[0])) ? $coach_ids[0] : 0;
            }else{
                $_SESSION['coach_id'] =   0;
            }
        }else{
            $_SESSION['coach_id'] = 0;
        }
        $customerinfo   = customer_data_by_id($userinfo['CustomerId']);
        $data = Array (
            'LastSigninTimestamp' => $db->now(),
            'LastSigninIP'  => $_SERVER['REMOTE_ADDR']
        );

        $db->where ('Id', $userinfo['Id']);

        if ($db->update ('customers', $data)){
        }
      // Set login cookie
        $check_logintoken = false;
        if(isset($_COOKIE['accesstoken'])){
            $db->where('Token',$_COOKIE['accesstoken']);
            $check_logintoken = $db->has('login');
        }
        if($check_logintoken == true){
            // Do nothing
        }else{
            // Create new token
            $token_userid = $_SESSION['UserId'];
            $token_device = $_SERVER['HTTP_USER_AGENT'];
            $token_cookie_value = 0;
            $token_cookie_date            = date('Y-m-d');
            $token_cookie_datetime        = date('Y-m-d H:i:s');
            $token_cookie_active_until    = date('Y-m-d',strtotime($token_cookie_date . "+30 days"));

            $token_cookie_ip              = $_SERVER['REMOTE_ADDR'];

            $token_cookie                 = rand(100,999).rand(100,999);
            $token_cookie                 = hash('md5', $token_cookie . 'sgEHrtole3rrESt35op' . $token_cookie_datetime . $token_cookie_ip);
            $token_cookie_until           = date('Y-m-d',strtotime($token_cookie_date . "+90 days"));


            $token_cookie_name            = "accesstoken";
            $token_cookie_value           = $token_cookie;
            setcookie($token_cookie_name, $token_cookie_value, time() + (86400 * 90), "/");


            $token_cookie_data = array(
                "UserId"  => $token_userid,
                "Token"   => $token_cookie_value,
                "Device"  => $token_device
            );

            $db->insert('login',$token_cookie_data);
        }
        // Login Updates
        logaction(2,0,0);
        loginattempt($username, $password, '0');
        if($_SESSION['EmployeeGroup']>0){
            // header("Location:".$basehref."trainer");
            header("Location:".$basehref."edit-user/".$userinfo['Id']);
        }else{
            header("Location:".$basehref."edit-user/".$userinfo['Id']);
        }

    }else{
        $db->where("Email", $username);
        $db->where("EmployeeGroup",3);
        $useremail_check = $db->getOne('users');

        $db->where("Email", $username);
        $db->where("PasswordHash", $passwordhashed);
        $db->where("EmployeeGroup",3);
        $usercredential_check = $db->getOne('users');

        $db->where("Email", $username);
        $db->where("PasswordHash", $passwordhashed);
        $db->where("EmployeeGroup",3);
        $db->where("is_verify", 1);
        $user_unverified = $db->getOne('users');

        if(empty($useremail_check)){
            $_SESSION['errormsg'] = 'invalid-emailId';
            header("Location:".$basehref."login-instructor");
        }elseif(empty($usercredential_check)){
            $_SESSION['errormsg'] = 'invalid-password';
            header("Location:".$basehref."login-instructor");
        }elseif(empty($user_unverified)){
              $_SESSION['errormsg'] = 'unverified-account';
              header("Location:".$basehref."login-instructor");
        }else{
            $_SESSION['errormsg'] = 'invalid-credential';
            header("Location:".$basehref."login-instructor");
        }
        loginattempt($username, $password, '1');

    }

    exit;
}

// PLANGY
function loginactioncustomertoken($userid){
    global $db;
    global $basehref;
    $db->where("Id", $userid);
    $userinfo = $db->getOne('users');


    if(!empty($userinfo)){


        $_SESSION['UserId']             = $userinfo['Id'];
        $_SESSION['Key']                = $userinfo['Key'];
        $_SESSION['Language']           = $userinfo['Language'];
        $_SESSION['Firstname']          = $userinfo['Firstname'];
        $_SESSION['Lastname']           = $userinfo['Lastname'];
        $_SESSION['Initials']           = $userinfo['Initials'];
        $_SESSION['AvatarBackgroundColor'] = $userinfo['AvatarBackgroundColor'];
        $_SESSION['AvatarTextColor']    = $userinfo['AvatarTextColor'];
        $_SESSION['Email']              = $userinfo['Email'];
        $_SESSION['CustomerId']         = $userinfo['CustomerId'];
        $_SESSION['Admin']              = $userinfo['Admin'];
        $_SESSION['Superadmin']         = $userinfo['Superadmin'];
        $_SESSION['Type']               = $userinfo['Type'];
        $_SESSION['UserTitle']          = $userinfo['Title'];
        $_SESSION['DepartmentId']       = $userinfo['DepartmentId'];
        $_SESSION['CurrentDepartment']  = $userinfo['DepartmentId'];
        $_SESSION['CurrentYear']        = date('Y');
        $_SESSION['CurrentWeek']        = date('W');
        $_SESSION['CreateDate']         = $userinfo['CreateDate'];

        $_SESSION['DashShiftlistSetting']         = $userinfo['DashShiftlistSetting'];

        $customerinfo   = customer_data_by_id($userinfo['CustomerId']);

        $_SESSION['Country']            = $customerinfo['Country'];
        $_SESSION['StripeCustomerId']   = $customerinfo['StripeCustomerId'];
        $_SESSION['Active']             = $customerinfo['Active'];
        $_SESSION['CustomerType']       = $customerinfo['Type'];


        $_SESSION['ShiftList']['CurrentYear']   = date('Y');
        $_SESSION['ShiftList']['CurrentMonth']  = date('m');

        //Alter department and current department
        //$leaderinfo     = departments_by_leader($userinfo['CustomerId'],$_SESSION['UserId'],$userinfo['Type']);

        /*if($leaderinfo != false){
            $_SESSION['DepartmentId'] = $leaderinfo[0]['Id'];
            $_SESSION['CurrentDepartment'] = $leaderinfo[0]['Id'];
        }*/

        $data = Array (
            'LastSigninTimestamp' => $db->now(),
            'LastSigninIP'  => $_SERVER['REMOTE_ADDR']

        );

        $db->where ('Id', $userinfo['Id']);

        if ($db->update ('customers', $data)){
        }

        //setcookie('visitor', serialize($cookie), time() + (86400 * 30), "/");

        // Login Updates
        //logaction(2,0,0);
        //loginattempt($username, $password, '0');

        if($_SESSION['Superadmin'] == 1){
            header("Location:".$basehref."dashboard");
        }else{
            header("Location:".$basehref);
        }

    }else{
        $_SESSION['errormsg'] = 'error1';
        //loginattempt($username, $password, '1');
    }

    exit;
}

// PLANGY
function loginactioncustomersilent($guard, $cookie){
    global $db;
    global $basehref;
    $db->where("Cookie", $guard);
    $userinfo = $db->getOne('users');

    if($userinfo['CookieEnd'] < date('Y-m-d')){
        $_SESSION['errormsg'] = 'error1';
        //loginattempt($username, $password, '1');
        exit;
    }



    if(!empty($userinfo)){


        $_SESSION['UserId']             = $userinfo['Id'];
        $_SESSION['Key']                = $userinfo['Key'];
        $_SESSION['Language']           = $userinfo['Language'];
        $_SESSION['Firstname']          = $userinfo['Firstname'];
        $_SESSION['Lastname']           = $userinfo['Lastname'];
        $_SESSION['Initials']           = $userinfo['Initials'];
        $_SESSION['AvatarBackgroundColor'] = $userinfo['AvatarBackgroundColor'];
        $_SESSION['AvatarTextColor']    = $userinfo['AvatarTextColor'];
        $_SESSION['Email']              = $userinfo['Email'];
        $_SESSION['CustomerId']         = $userinfo['CustomerId'];
        $_SESSION['Admin']              = $userinfo['Admin'];
        $_SESSION['Superadmin']         = $userinfo['Superadmin'];
        $_SESSION['Type']               = $userinfo['Type'];
        $_SESSION['UserTitle']          = $userinfo['Title'];
        $_SESSION['DepartmentId']       = $userinfo['DepartmentId'];
        $_SESSION['CurrentDepartment']  = $userinfo['DepartmentId'];
        $_SESSION['CurrentYear']        = date('Y');
        $_SESSION['CurrentWeek']        = date('W');
        $_SESSION['CreateDate']         = $userinfo['CreateDate'];

        $_SESSION['DashShiftlistSetting']         = $userinfo['DashShiftlistSetting'];

        $customerinfo   = customer_data_by_id($userinfo['CustomerId']);

        $_SESSION['Country']            = $customerinfo['Country'];
        $_SESSION['StripeCustomerId']   = $customerinfo['StripeCustomerId'];
        $_SESSION['Active']             = $customerinfo['Active'];
        $_SESSION['CustomerType']       = $customerinfo['Type'];


        $_SESSION['ShiftList']['CurrentYear']   = date('Y');
        $_SESSION['ShiftList']['CurrentMonth']  = date('m');

        //Alter department and current department
        $leaderinfo     = departments_by_leader($userinfo['CustomerId'],$_SESSION['UserId'],$userinfo['Type']);

        if($leaderinfo != false){
            $_SESSION['DepartmentId'] = $leaderinfo[0]['Id'];
            $_SESSION['CurrentDepartment'] = $leaderinfo[0]['Id'];
        }

        $data = Array (
            'LastSigninTimestamp' => $db->now(),
            'LastSigninIP'  => $_SERVER['REMOTE_ADDR']

        );

        $db->where ('Id', $userinfo['Id']);

        if ($db->update ('customers', $data)){
        }

        setcookie('visitor', serialize($cookie), time() + (86400 * 30), "/");



        // Set login cookie
        $check_logintoken = false;
        if(isset($_COOKIE['accesstoken'])){

            $db->where('Token',$_COOKIE['accesstoken']);
            $check_logintoken = $db->has('login');

        }

        if($check_logintoken == true){
            // Do nothing
        }else{
            // Create new token

            $token_userid = $_SESSION['UserId'];
            $token_device = $_SERVER['HTTP_USER_AGENT'];
            $token_cookie_value = 0;

            $token_cookie_date            = date('Y-m-d');
            $token_cookie_datetime        = date('Y-m-d H:i:s');
            $token_cookie_active_until    = date('Y-m-d',strtotime($token_cookie_date . "+30 days"));

            $token_cookie_ip              = $_SERVER['REMOTE_ADDR'];

            $token_cookie                 = rand(100,999).rand(100,999);
            $token_cookie                 = hash('md5', $token_cookie . 'sgEHrtole3rrESt35op' . $token_cookie_datetime . $token_cookie_ip);
            $token_cookie_until           = date('Y-m-d',strtotime($token_cookie_date . "+90 days"));


            $token_cookie_name            = "accesstoken";
            $token_cookie_value           = $token_cookie;
            setcookie($token_cookie_name, $token_cookie_value, time() + (86400 * 90), "/");


            $token_cookie_data = array(
                "UserId"  => $token_userid,
                "Token"   => $token_cookie_value,
                "Device"  => $token_device
            );

            $db->insert('login',$token_cookie_data);

        }



        // Login Updates
        //logaction(2,0,0);
        //loginattempt($username, $password, '0');

        if($_SESSION['Superadmin'] == 1){
            header("Location:".$basehref."masterview");
        }else{
            header("Location:".$basehref."dashboard");
        }

    }else{
        $_SESSION['errormsg'] = 'error1';
        //loginattempt($username, $password, '1');
    }

    exit;
}


function formatorderid($orderid){

    $orderid = sprintf('%06d', $orderid);
    return $orderid;


}