<?php 
include("../../includes/config.php");

$period = $_GET['period'];

if(is_numeric($period)){

    $from_date = date('Y-m-d',strtotime('-'. $period + 1 .' Days'));

}else{

    if($period == 'thismonth'){
        $from_date = date('Y-m-1');
        $period = date('t');
    }elseif($period == 'lastmonth'){
        $from_date = date('Y-m-1',strtotime('-1 Months'));
        $period = date('t',strtotime('-1 Months'));
    }

}

$date = date('Y-m-d');

$date_month = sprintf("%02d", date('m'));
$date_year  = date('Y');

$current_date = date('Y_m');
$past_date = date('Y_m',strtotime('-1 Months'));


// GET ALL CUSTOMERS LANDING PAGES
$db->where('CustomerId',$_SESSION['CustomerId']);
$landingpages = $db->get('landingpages');


$content_raw_total = array();

foreach($landingpages as $landingpage){

  $path = $_SERVER["DOCUMENT_ROOT"]."/var/blackbox/";

  // GET DATA THIS MONTH
  $filepath = $path . $date_year . '/' . $date_month  . '/'.$landingpage['Slug'].'-'.$landingpage['OptyKey'].'.json';

  $exist = file_exists($filepath);

  if($exist == true){
      $content = file_get_contents($filepath);
      $content_explode = explode("]]", $content);
      $content_striped = str_replace("[[","",$content_explode);
      $content_raw_current = str_replace('"','',$content_striped);

      $log_current = array();

      foreach($content_raw_current as $item){

          if(strlen($item) > 4){
              $values = explode(',',$item);

              $keys = array('IP','Slug','Date','Cookie','DateEnd');

              $item_combined = array_combine($keys, $values);
              $log_current[] = $item_combined;

          }

      }

  }else{
      $log_current = array();
  }



  // GET DATA LAST MONTH
  $filepath_last = $path . $date_year . '/' . $date_month  . '/'.$landingpage['Slug'].'-'.$landingpage['OptyKey'].'.json';

  $exist = file_exists($filepath_last);

  if($exist == true){
      $content_last = file_get_contents($filepath_last);

      $content_explode_last = explode("]]", $content_last);
      $content_striped_last = str_replace("[[","",$content_explode_last);
      $content_raw_last = str_replace('"','',$content_striped_last);

      $log_last = array();

      foreach($content_raw_last as $item){

          if(strlen($item) > 4){
              $values = explode(',',$item);

              $keys = array('IP','Slug','Date','Cookie','DateEnd');

              $item_combined = array_combine($keys, $values);
              $log_last[] = $item_combined;

          }

      }

  }else{
      $log_last = array();
  }

  $content_raw = array_merge($log_current, $log_last);
  $content_raw_total  = array_merge($content_raw_total,$content_raw);

}

// Get Leads
$db->where('CreateDate',$from_date,'<');
$leads_raw = $db->get('leads');
$leads_cleaned = array();

foreach($leads_raw as $item){

    $item_date = substr($item['CreateDate'],0,-9);
    $leads_cleaned[$item_date] = $item;

}



/*$array = array();
foreach($content_raw_total as $content){

    if (strlen($content) > 2){
        $array[] = explode(',', $content);
    }

}*/


$array_relevant =  array();


foreach($content_raw_total as $item){

    $item['Date'] = substr($item['Date'], 0, -9);

    if($item['Date'] > $from_date){
        $array_relevant[$item['Date']][] = $item;
    }

}





/*foreach($content_raw_total as $item){

    $content_raw_unique_total[$date] = $item;

    $exist_ip = in_array($item['IP'],$content_raw_unique_ips);
    $exist_cookie = in_array($item['Cookie'],$content_raw_unique_cookies);

    if($exist_ip == false && $exist_cookie == false){
        // COMPLETELY NEW USER
        $content_raw_unique_total[] = $item;

        $content_raw_unique_ips[] = $item['IP'];
        $content_raw_unique_cookies[] = $item['Cookie'];

    }elseif($exist_ip == true && $exist_cookie == false){
        // COMPLETELY NEW USER FROM SAME NETWORK
        $content_raw_unique_total[] = $item;

        $content_raw_unique_ips[] = $item['IP'];
        $content_raw_unique_cookies[] = $item['Cookie'];

    }elseif($exist_ip == false && $exist_cookie == 0){
        // COMPLETELY NEW USER WITH UNDEFINED COOKIE
        $content_raw_unique_total[] = $item;

        $content_raw_unique_ips[] = $item['IP'];
        $content_raw_unique_cookies[] = $item['Cookie'];

    }elseif($exist_ip == true && $exist_cookie == true){
        // WE KNOW THIS MAN!
    }

}*/

//d($content_raw_unique_total);



$content_raw_unique_total = array();
$content_raw_unique_ips = array();
$content_raw_unique_cookies = array();


$array_datapoints =  array();
$array_dates =  array();


$graph_datapoints_users = '';
$graph_datapoints_leads = '';
$graph_datapoints = '';
$graph_dates = '';

$data_views_total = 0;
$data_leads_total = 0;
$data_users_total = 0;


for ($i = 0; $i < $period; $i++) {

    $this_date = date('Y-m-d',strtotime($from_date . '+' . $i . ' Days'));

    $array_dates[] = $this_date;
    $graph_dates = $graph_dates . ',"' . $this_date . '"';


    // Unique

    $content_raw_unique_ips[$this_date][] = array();
    $content_raw_unique_cookies[$this_date][] = array();

    if(array_key_exists($this_date, $array_relevant)){

        foreach($array_relevant[$this_date] as $item){

            $exist_ip = in_array($item['IP'],$content_raw_unique_ips[$this_date]);
            $exist_cookie = in_array($item['Cookie'],$content_raw_unique_cookies[$this_date]);

            if($exist_ip == false && $exist_cookie == false){
                // COMPLETELY NEW USER
                $content_raw_unique_total[$this_date][] = $item;

                $content_raw_unique_ips[$this_date][] = $item['IP'];
                $content_raw_unique_cookies[$this_date][] = $item['Cookie'];


            }elseif($exist_ip == true && $exist_cookie == false){
                // WE KNOW THIS MAN!
            }elseif($exist_ip == false && $exist_cookie == 0){
                // COMPLETELY NEW USER WITH UNDEFINED COOKIE
                $content_raw_unique_total[$this_date][] = $item;

                $content_raw_unique_ips[$this_date][] = $item['IP'];
                $content_raw_unique_cookies[$this_date][] = $item['Cookie'];

            }elseif($exist_ip == true && $exist_cookie == true){
                // WE KNOW THIS MAN!
            }

        }


        $graph_datapoints_users = $graph_datapoints_users . ',' . count($content_raw_unique_total[$this_date]);

        $data_users_total += count($content_raw_unique_total[$this_date]);

    }else{

        $graph_datapoints_users = $graph_datapoints_users . ',0';

    }

    //d($content_raw_unique_total);

    // Views
    if(array_key_exists($this_date, $array_relevant)){

        $array_datapoints[] = count($array_relevant[$this_date]);
        $graph_datapoints = $graph_datapoints . ',' . count($array_relevant[$this_date]);

        $data_views_total += count($array_relevant[$this_date]);

    }else{

        $array_datapoints[] = 0;
        $graph_datapoints = $graph_datapoints . ',0';

    }

    // Leads
    if(array_key_exists($this_date, $leads_cleaned)){

        $graph_datapoints_leads = $graph_datapoints_leads . ',' . count($leads_cleaned[$this_date]);

    }else{

        $graph_datapoints_leads = $graph_datapoints_leads . ',0';

    }


}


//d($content_raw_unique_total);



$graph_datapoints_users = substr($graph_datapoints_users,1);
$graph_datapoints_leads = substr($graph_datapoints_leads,1);
$graph_datapoints = substr($graph_datapoints,1);
$graph_dates = substr($graph_dates,1);

?>

    <div class="ct-chart-frontend-activity chart-type-1" id="" style="height: 300px;"></div>

    <script type="text/javascript">
    var chart = new Chartist.Line('.ct-chart-frontend-activity', {
      labels: [<?php echo $graph_dates; ?>],
      series: [
        [<?php echo $graph_datapoints_leads; ?>],
        [<?php echo $graph_datapoints; ?>],
        [<?php echo $graph_datapoints_users; ?>],
      ]
    }, {

      axisX: {
        offset: 50,
        showGrid: false,
      },
      axisY: {
        offset: 40,
        showGrid: false,
      },
      fullWidth: true,
      low: 0,
      showArea: true,
      showLine: true,
    showGridBackground: false,
      plugins: [
        Chartist.plugins.tooltip({        
            appendToBody: true,
            pointClass: 'opty-point'
        })
      ]
    });

    </script>