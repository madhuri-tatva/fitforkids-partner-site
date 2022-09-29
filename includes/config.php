<?php

session_cache_limiter('private');
session_cache_expire(0);
error_reporting(0);
ini_set('session.gc_maxlifetime', 21600);
session_set_cookie_params(21600);

session_start();

// INIT Settings
$development = 'no';
// $basehref = "http://fitforkidspartnersite.web9.anasource.com:60109/";
   $basehref = "http://fitforkids.com/";
// $basehref = "https://www.fitforkids.dk/";

$uploadpath = "/uploads/productimages/"; // only for uploadhandler.php

if($development == 'yes'){
	error_reporting(E_ALL); // display all errors
	ini_set('display_errors', 1);  // display all errors
	error_reporting(-1);  // display all errors
	ini_set('error_reporting', E_ALL);  // display all errors
}else{
	error_reporting(0); // disables error reporting!
}

date_default_timezone_set("Europe/Copenhagen");
ini_set('memory_limit', '512M');
//ini_set('SMTP','localhost'); //temp
//ini_set('smtp_port',25); //temp

//Connect DB
require_once(dirname(__FILE__).'/plugins/PHP-MySQLi-Database-Class-master/MysqliDb.php');
$db = new Mysqlidb (Array (
	'host' => 'mysql104.unoeuro.com',
	'username' => 'fitforkids_com',
	'password' => '3g49fmRyxEkr',
	'db'=> 'fitforkids_com_db_new',
	'port' => 3306,
	'charset' => 'utf8'));
	$db->setPrefix('pg_');

// echo '<pre>';
// print_r($db);
// exit;

define('MAIN_PATH', dirname(dirname(__FILE__))); //main path
define("CONTACT_EMAIL", "coaching@fitforkids.dk");
define("KOSTAN_EMAIL", "kostvejledning@fitforkids.dk");

//zoom client ID, Secret and Redirect URI
// from bhagyesh.koshti@cand-it.dk account
define("ZOOM_CLIENT_ID", "QvpzhTMrT9uceimVMpyurw");
define("ZOOM_CLIENT_SECRET", "p8s54OJz6L1FuddofW0wQ2T40ooKTZYm");
define("ZOOM_REDIRECT_URI", "https://www.fitforkids.dk/zoomCallback.php");

//Nexmo keys
define("NEXMO_KEY", 'xxx');
define("NEXMO_SECRET", 'xxx');
define("BASE_HREF", $basehref);

$membershipprice = 0;

$version_front = '0.07';
$version_back = '0.27';

$currency = "USD";
$_SESSION['currency'] = $currency;

//Google Analytics
$GOOGLE_ANALYTICS = "XXX";

//HASH KEYs Security
$toHash = 'aHGM/!#ads!ogt%5am61';
$salt =  'xGhg25NDswHFvdpiGdts7A25$tFXeTICmzF!r9ze&2qFVKsk1ItUGmV_sX2Li39I';
$sitewide_key =   '@kek9qRO4AuIGnwkgPTM)f5UlWCB_kXB@N#GVi&JK-*qVIVsF%*1Sc_Htxi5LZ$h';
/* END KEYS */


//https://github.com/thephpleague/csv
// if (!ini_get("auto_detect_line_endings")) {
//     ini_set("auto_detect_line_endings", '1');
// }



//includes
require(dirname(__FILE__).'/functions.php');
require(dirname(__FILE__).'/plugins/kint-master/Kint.class.php');
//require(dirname(__FILE__).'/plugins/Nexmo-PHP-lib-master/src/NexmoMessage.php');
require(dirname(__FILE__).'/plugins/greek-slug-generator-master/src/GreekSlugGenerator.php');
use \StathisG\GreekSlugGenerator\GreekSlugGenerator;

require(dirname(__FILE__).'/plugins/pakkelabels-php-sdk-master/src/Pakkelabels.php');

require(dirname(__FILE__).'/Function.Array-Group-By.php');

//require(dirname(__FILE__).'/plugins/mpdf60/mpdf.php');


require(dirname(__FILE__).'/plugins/quickpay-php-client-master/QuickPay/QuickPay.php');
require(dirname(__FILE__).'/plugins/quickpay-php-client-master/QuickPay/API/Client.php');
require(dirname(__FILE__).'/plugins/quickpay-php-client-master/QuickPay/API/Constants.php');
require(dirname(__FILE__).'/plugins/quickpay-php-client-master/QuickPay/API/Exception.php');
require(dirname(__FILE__).'/plugins/quickpay-php-client-master/QuickPay/API/Request.php');
require(dirname(__FILE__).'/plugins/quickpay-php-client-master/QuickPay/API/Response.php');

require(dirname(__FILE__).'/plugins/PHP-Error-master/src/php_error.php');

if(($development == 'yes')){

	\php_error\reportErrors();

}


require(dirname(__FILE__).'/plugins/csv-master/autoload.php');


require(dirname(__FILE__).'/plugins/stripe-php-4.0.0/init.php');


require(dirname(__FILE__).'/plugins/PHPMailer-master/src/Exception.php');
require(dirname(__FILE__).'/plugins/PHPMailer-master/src/PHPMailer.php');
require(dirname(__FILE__).'/plugins/PHPMailer-master/src/SMTP.php');

//Stripe test keys
//$secret_key         = "sk_test_ozH5oQS9aeNAbc5oEErGBvkF";
//$publishable_key    = 'pk_test_R2TPSwObCfOcJKrL6I3EHEtf';
$secret_key         = "xxx";
$publishable_key    = 'xxx';


//\Stripe\Stripe::setApiKey($secret_key);


$_SESSION['currentpage'] = $_SERVER['REQUEST_URI'];

$domian_url = $_SERVER['SERVER_NAME'];

//Language
if($domian_url == 'fitforkids.com'){

	$_SESSION['CurrentLanguage'] = 'English';

    if(($_SESSION['Language'] == 'da_DK')){
		
		putenv('LC_ALL=da_DK');
		setlocale(LC_ALL, 'da_DK');

		bindtextdomain('da_DK', "./locale");
		bind_textdomain_codeset('da_DK', 'UTF-8'); 

		textdomain('da_DK');

    }elseif($_SESSION['Language'] == 'en_GB'){
        
        putenv('LC_ALL=en_GB');
        setlocale(LC_ALL, 'en_GB');

        bindtextdomain('en_GB', "./locale");
        bind_textdomain_codeset('en_GB', 'UTF-8'); 

        textdomain('en_GB');

    }

	$GOOGLE_ANALYTICS = 'UA-xxx';

}
require(dirname(__FILE__).'/plugins/SMS.php');
// require(dirname(__FILE__).'/plugins/mpdf60/mpdf.php');
$sms_token = 'Rml0Zm9yS2lkczo2ZmQyOWU4OS1lOGUzLTRiNGMtYjU3Ni1kMjYwNGQzYjY3MGE=';