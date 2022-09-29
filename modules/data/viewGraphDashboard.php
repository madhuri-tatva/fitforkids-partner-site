<?php 
include("../../includes/config.php");

$period = $_GET['period'];
$settingViews = $_GET['views'];
$settingUsers = $_GET['users'];
$settingConversions = $_GET['conversions'];
$settingConversionRate= $_GET['conversionrate'];

if(is_numeric($period)){

    $from_date = date('Y-m-d',strtotime('-'. $period + 1 .' Days'));

}else{

    if($period == 'thismonth'){
        $from_date = date('Y-m-01');
        $period = date('t');
    }elseif($period == 'lastmonth'){
        $from_date = date('Y-m-01',strtotime('-1 Months'));
        $period = date('t',strtotime('-1 Months'));
    }

}

$from_date_time = date('Y-m-d H:i:s',strtotime($from_date));

$date = date('Y-m-d');

$date_month = sprintf("%02d", date('m'));
$date_year  = date('Y');

$current_date = date('Y_m');
$date_month_last = sprintf("%02d",date('m',strtotime('-1 Months')));
$date_year_last = date('Y',strtotime('-1 Months'));


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

              $keys = array('IP','Slug','Date','Cookie','DateEnd','Platform','Browser','BrowserVer');

              $item_combined = array_combine($keys, $values);
              $log_current[] = $item_combined;

          }

      }

  }else{
      $log_current = array();
  }



  // GET DATA LAST MONTH
  $filepath_last = $path . $date_year_last . '/' . $date_month_last  . '/'.$landingpage['Slug'].'-'.$landingpage['OptyKey'].'.json';


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

              $keys = array('IP','Slug','Date','Cookie','DateEnd','Platform','Browser','BrowserVer');

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
$db->where('CustomerId',$_SESSION['CustomerId']);
$db->where('CreateDate',$from_date_time,'>');
$leads_raw = $db->get('leads');
$leads_cleaned = array();

foreach($leads_raw as $item){

    $item_date = substr($item['CreateDate'],0,-9);
    $leads_cleaned[$item_date][] = $item;

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

    if($item['Date'] >= $from_date){
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


$graph_datapoints_conversionrate = '';
$graph_datapoints_users = '';
$graph_datapoints_leads = '';
$graph_datapoints = '';
$graph_dates = '';

$data_views_total = 0;
$data_leads_total = 0;
$data_users_total = 0;
$data_conversionrate_total = 0;
$data_conversionrate_to_count = 0;

for ($i = 0; $i < $period; $i++) {

    $this_date = date('Y-m-d',strtotime($from_date . '+' . $i . ' Days'));

    $array_dates[] = $this_date;
    $graph_dates = $graph_dates . ',"' . $this_date . '"';

    // Unique

    $content_raw_unique_ips[$this_date][] = array();
    $content_raw_unique_cookies[$this_date][] = array();

    if(array_key_exists($this_date, $array_relevant)){

        foreach($array_relevant[$this_date] as $item){

            $exist_ip = in_array(array($item['IP'],$item['Slug']),$content_raw_unique_ips[$this_date]);
            $exist_cookie = in_array(array($item['Cookie'],$item['Slug']),$content_raw_unique_cookies[$this_date]);

            if($exist_ip == false && $exist_cookie == false){
                // COMPLETELY NEW USER
                $content_raw_unique_total[$this_date][] = $item;

                $content_raw_unique_ips[$this_date][] = array($item['IP'],$item['Slug']);
                $content_raw_unique_cookies[$this_date][] = array($item['Cookie'],$item['Slug']);

            }elseif($exist_ip == true && $exist_cookie == false){
                // WE KNOW THIS MAN!
            }elseif($exist_ip == true && $exist_cookie == true){
                // WE KNOW THIS MAN!
            }

        }

        //d($content_raw_unique_cookies[$this_date]);
        //d($content_raw_unique_total[$this_date]);

        $graph_datapoints_users = $graph_datapoints_users . ',' . count($content_raw_unique_total[$this_date]);

        $data_users_total += count($content_raw_unique_total[$this_date]);

    }else{

        $graph_datapoints_users = $graph_datapoints_users . ',0';

    }

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

        $data_leads_total += count($leads_cleaned[$this_date]);

        // Conversion Rate
        $conversionrate = (count($leads_cleaned[$this_date]) / count($array_relevant[$this_date]) * 100);

        $graph_datapoints_conversionrate = $graph_datapoints_conversionrate . ',' . str_replace(',','.',$conversionrate);

        $data_conversionrate_total += $conversionrate;

    }else{

        $graph_datapoints_leads = $graph_datapoints_leads . ',0';

        $conversionrate = 0;

        $graph_datapoints_conversionrate = $graph_datapoints_conversionrate . ',0';

    }

}


$graph_datapoints_users = substr($graph_datapoints_users,1);
$graph_datapoints_leads = substr($graph_datapoints_leads,1);
$graph_datapoints_conversionrate = substr($graph_datapoints_conversionrate,1);
$graph_datapoints = substr($graph_datapoints,1);
$graph_dates = substr($graph_dates,1);

if($data_users_total != 0){
    $data_conversionrate_average = str_replace(',','.',$data_conversionrate_total/$data_users_total);
    $data_conversionrate_average = number_format($data_conversionrate_average,2,'.','');
}else{
    $data_conversionrate_average = 0;
}


?>

<div class="ct-chart-frontend-activity chart-type-1" id="" style="height: 300px;"></div>

<script type="text/javascript">
var chart = new Chartist.Line('.ct-chart-frontend-activity', {
  labels: [<?php echo $graph_dates; ?>],
  series: [
    [<?php if($settingViews == 1){ echo $graph_datapoints; }else{ echo '0'; } ?>],
    [<?php if($settingUsers == 1){ echo $graph_datapoints_users; }else{ echo '0'; } ?>],
    [<?php if($settingConversions == 1){ echo $graph_datapoints_leads; }else{ echo '0'; } ?>],
    [<?php if($settingConversionRate == 1){ echo $graph_datapoints_conversionrate; }else{ echo '0'; } ?>],
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


<div class="legends legends-stats legends-4" style="margin-top:0;">
    <span 
      id="legend-views" 
      class='legend purple <?php if($settingViews == 1){ ?>active<?php } ?>' 
      data-chart="views" 
      data-chart-status="<?php if($settingViews == 1){ echo 'active'; }else{ echo 'deactive'; } ?>">
      <em><?php echo $data_views_total; ?></em> 
      <?php echo _('Views'); ?>
    </span>
    <span 
      id="legend-users" 
      class='legend purpledark <?php if($settingUsers == 1){ ?>active<?php } ?>' 
      data-chart="users" 
      data-chart-status="<?php if($settingUsers == 1){ echo 'active'; }else{ echo 'deactive'; } ?>">
      <em><?php echo $data_users_total; ?></em> 
      <?php echo _('Users'); ?>
    </span>
    <span 
      id="legend-conversions" 
      class='legend blue <?php if($settingConversions == 1){ ?>active<?php } ?>' 
      data-chart="conversions" 
      data-chart-status="<?php if($settingConversions == 1){ echo 'active'; }else{ echo 'deactive'; } ?>">
      <em><?php echo $data_leads_total; ?></em> 
      <?php echo _('Conversions'); ?>
    </span>
    <span 
      id="legend-conversionrate" 
      class='legend bluedark <?php if($settingConversionRate == 1){ ?>active<?php } ?>' 
      data-chart="conversionrate" 
      data-chart-status="<?php if($settingConversionRate == 1){ echo 'active'; }else{ echo 'deactive'; } ?>">
      <em><?php echo $data_conversionrate_average; ?>%</em> 
      <?php echo _('Conversion rate'); ?>
    </span>
</div>

<script>

$('.legends .legend').click(function(){

    var chartStatus = $(this).attr('data-chart-status');

    if(chartStatus == 'active'){

        $(this).removeClass('active');
        $(this).attr('data-chart-status','deactive');

    }else{

        $(this).addClass('active');
        $(this).attr('data-chart-status','active');

    }



    var chartStatusViews = 0;
    var chartStatusUsers = 0;
    var chartStatusConversions = 0;
    var chartStatusConversionRate = 0;

    var chartStatus = $('#legend-views').attr('data-chart-status');
    if(chartStatus == 'active'){
        chartStatusViews = 1;
    }
    var chartStatus = $('#legend-users').attr('data-chart-status');
    if(chartStatus == 'active'){
        chartStatusUsers = 1;
    }
    var chartStatus = $('#legend-conversions').attr('data-chart-status');
    if(chartStatus == 'active'){
        chartStatusConversions = 1;
    }
    var chartStatus = $('#legend-conversionrate').attr('data-chart-status');
    if(chartStatus == 'active'){
        chartStatusConversionRate = 1;
    }

    loadDashboardGraph(<?php echo $period; ?>,chartStatusViews,chartStatusUsers,chartStatusConversions,chartStatusConversionRate);



});

chart.on('draw', function(data) {
  if(data.type === 'point') {
    var circle = new Chartist.Svg('circle', {
      cx: [data.x],
      cy: [data.y],
      r: [5],
      'ct:value': data.value.y,
      'ct:meta': data.meta,
      class: 'opty-point',
    }, 'ct-circle');
    data.element.replace(circle);

  }
});

chart.on('draw', function(data) {
  if(data.type === 'line' || data.type === 'area') {
    data.element.animate({
      d: {
        begin: 500 * data.index,
        dur: 1500,
        from: data.path.clone().scale(1, 0).translate(0, data.chartRect.height()).stringify(),
        to: data.path.clone().stringify(),
        easing: Chartist.Svg.Easing.easeOutQuint
      }
    });
  }
});
</script>