<?php
include("../includes/config.php");



/*
http://stackoverflow.com/questions/14104304/mysql-select-where-datetime-matches-day-and-not-necessarily-time
SELECT * FROM tablename
WHERE columname BETWEEN '2012-12-25 00:00:00' AND '2012-12-25 23:59:59'
fastes query :-)  !


 */

$firstdayofqueries = date("Y-m-d", strtotime('first day of January '.date('Y') )); // First day of queries. Hardcoded to first day of the year.
//d($firstdayofqueries);

$lastdayofqueries = date("Y-m-d", strtotime('last day of December '.date('Y') )); // Last day of queries. Hardcoded to last day of the year.
//d($lastdayofqueries);





//Number of Sales & Total amount

    //total sales: pg_orders     Id   (num rows) between dates  CreateDate
    $Sales = $db->rawQuery("SELECT * FROM pg_orders
    WHERE CreateDate BETWEEN '$firstdayofqueries' AND '$lastdayofqueries'");
    $NumberOfSales = count($Sales);

    //total amount: orders_chosen   combinedprice   between dates   timestamp
    $SalesAmount = $db->rawQuery("SELECT * FROM pg_orders_chosen
    WHERE timestamp BETWEEN '$firstdayofqueries' AND '$lastdayofqueries'");
    $TotalSalesAmount = 0;
    foreach ($SalesAmount as $SalesAmount) {
    $TotalSalesAmount = $TotalSalesAmount + $SalesAmount['combinedprice'];
    }




// Number of reperations & Total amount
//
    //total sales & amount: Id  (num rows) &  Price  between dates  CreateDate
    $RepsAmount = $db->rawQuery("SELECT * FROM pg_reparations
    WHERE CreateDate BETWEEN '$firstdayofqueries' AND '$lastdayofqueries'");
    $NumberOfReps = count($RepsAmount);
    $TotalRepsAmount = 0;
    foreach ($RepsAmount as $RepsAmount) {
    $TotalRepsAmount = $TotalRepsAmount + $RepsAmount['Price'];
    }


// Total number of bookings
    //total bookings & amount: Id  (num rows) &  Price  between dates  CreateDate
    $BookingsAmount = $db->rawQuery("SELECT * FROM pg_bookings
    WHERE CreateDate BETWEEN '$firstdayofqueries' AND '$lastdayofqueries'");
    $NumberOfBookings = count($BookingsAmount);
    $TotalBookingsAmount = 0;
    foreach ($BookingsAmount as $BookingsAmount) {
    $TotalBookingsAmount = $TotalBookingsAmount + $BookingsAmount['Price'];
    }




//  Total number of customers
    //total customers
    $CustomersAmount = $db->rawQuery("SELECT * FROM pg_customers
    WHERE CreateDate BETWEEN '$firstdayofqueries' AND '$lastdayofqueries'");
    $NumberOfCustomers = count($CustomersAmount);


Echo " Alle tal indenfor år 2016.
Total antal salg: $NumberOfSales   <br>
Total beløb salg: $TotalSalesAmount <br>
Total antal reps: $NumberOfReps <br>
Total beløb reps: $TotalRepsAmount <br>
Total antal bookinger: $TotalBookingsAmount <br>
Total antal customers: $NumberOfCustomers <br>





";



//Total earnings this week (monday - søndag)
    //chart data
    $currentday = date("Y-m-d", strtotime("now")); //strtotime("now"); // this can be replaced to find weekly data for a given date
    $monday = date( 'Y-m-d', strtotime( 'monday this week', strtotime($currentday) ) ); //beginning week
    $tuesday = date( 'Y-m-d', strtotime( 'tuesday this week', strtotime($currentday) ) );
    $wednesday = date( 'Y-m-d', strtotime( 'wednesday this week', strtotime($currentday) ) );
    $thursday = date( 'Y-m-d', strtotime( 'thursday this week', strtotime($currentday) ) );
    $friday = date( 'Y-m-d', strtotime( 'friday this week', strtotime($currentday) ) );
    $saturday = date( 'Y-m-d', strtotime( 'saturday this week', strtotime($currentday) ) );
    $sunday = date( 'Y-m-d', strtotime( 'sunday this week', strtotime($currentday) ) ); //end week

  //  d($currentday);
  //  d($monday);
  //  d($sunday);

        //total amount: orders_chosen   combinedprice   between dates   timestamp
        $SalesAmountThisWeek = $db->rawQuery("SELECT * FROM pg_orders_chosen
        WHERE timestamp BETWEEN '$monday' AND '$sunday'");//GROUP BY timestamp DESC");
        $TotalSalesAmountThisWeek = 0;
        $TotalSalesAmountThisWeekmonday = 0;
        $TotalSalesAmountThisWeektuesday = 0;
        $TotalSalesAmountThisWeekwednesday = 0;
        $TotalSalesAmountThisWeekthursday = 0;
        $TotalSalesAmountThisWeekfriday = 0;
        $TotalSalesAmountThisWeeksaturday = 0;
        $TotalSalesAmountThisWeeksunday = 0;

        foreach ($SalesAmountThisWeek as $SalesAmountThisWeek) {
            $currentdateday = date( 'Y-m-d', strtotime($SalesAmountThisWeek['timestamp']));

            if($currentdateday == $monday){
            $TotalSalesAmountThisWeekmonday = $TotalSalesAmountThisWeekmonday + $SalesAmountThisWeek['combinedprice'];
            }
            if($currentdateday == $tuesday){
            $TotalSalesAmountThisWeektuesday = $TotalSalesAmountThisWeektuesday + $SalesAmountThisWeek['combinedprice'];
            }
            if($currentdateday == $wednesday){
            $TotalSalesAmountThisWeekwednesday = $TotalSalesAmountThisWeekwednesday + $SalesAmountThisWeek['combinedprice'];
            }
            if($currentdateday == $thursday){
            $TotalSalesAmountThisWeekthursday = $TotalSalesAmountThisWeekthursday + $SalesAmountThisWeek['combinedprice'];
            }
            if($currentdateday == $friday){
            $TotalSalesAmountThisWeekfriday = $TotalSalesAmountThisWeekfriday + $SalesAmountThisWeek['combinedprice'];
            }
            if($currentdateday == $saturday){
            $TotalSalesAmountThisWeeksaturday = $TotalSalesAmountThisWeeksaturday + $SalesAmountThisWeek['combinedprice'];
            }
            if($currentdateday == $sunday){
            $TotalSalesAmountThisWeeksunday = $TotalSalesAmountThisWeeksunday + $SalesAmountThisWeek['combinedprice'];
            }
            $TotalSalesAmountThisWeek = $TotalSalesAmountThisWeek + $SalesAmountThisWeek['combinedprice'];
        }

   // d($SalesAmountThisWeek);




   // echo "SELECT * FROM pg_orders_chosen WHERE timestamp BETWEEN '$monday' AND '$sunday'";

        $TotalRepsAmountThisWeek = $db->rawQuery("SELECT * FROM pg_reparations
        WHERE CreateDate BETWEEN '$monday' AND '$sunday'"); // > DATE_SUB(NOW(), INTERVAL 1 WEEK) query uses sunday as first day.. we live in denmark soo...

        $TotalRepsAmountThisWeekMoney = 0;
        $TotalRepsAmountThisWeekmonday = 0;
        $TotalRepsAmountThisWeektuesday = 0;
        $TotalRepsAmountThisWeekwednesday = 0;
        $TotalRepsAmountThisWeekthursday = 0;
        $TotalRepsAmountThisWeekfriday = 0;
        $TotalRepsAmountThisWeeksaturday = 0;
        $TotalRepsAmountThisWeeksunday = 0;


        if(is_array($TotalRepsAmountThisWeek)){


            foreach ($TotalRepsAmountThisWeek as $TotalRepsAmountThisWeek) {
                $currentdateday = date( 'Y-m-d', strtotime($TotalRepsAmountThisWeek['CreateDate']));

                if($currentdateday == $monday){
                $TotalRepsAmountThisWeekmonday = $TotalRepsAmountThisWeekmonday + $TotalRepsAmountThisWeek['Price'];
                }
                if($currentdateday == $tuesday){
                $TotalRepsAmountThisWeektuesday = $TotalRepsAmountThisWeektuesday + $TotalRepsAmountThisWeek['Price'];
                }
                if($currentdateday == $wednesday){
                $TotalRepsAmountThisWeekwednesday = $TotalRepsAmountThisWeekwednesday + $TotalRepsAmountThisWeek['Price'];
                }
                if($currentdateday == $thursday){
                $TotalRepsAmountThisWeekthursday = $TotalRepsAmountThisWeekthursday + $TotalRepsAmountThisWeek['Price'];
                }
                if($currentdateday == $friday){
                $TotalRepsAmountThisWeekfriday = $TotalRepsAmountThisWeekfriday + $TotalRepsAmountThisWeek['Price'];
                }
                if($currentdateday == $saturday){
                $TotalRepsAmountThisWeeksaturday = $TotalRepsAmountThisWeeksaturday + $TotalRepsAmountThisWeek['Price'];
                }
                if($currentdateday == $sunday){
                $TotalRepsAmountThisWeeksunday = $TotalRepsAmountThisWeeksunday + $TotalRepsAmountThisWeek['Price'];
                }
                $TotalRepsAmountThisWeekMoney = $TotalRepsAmountThisWeekMoney + $TotalRepsAmountThisWeek['Price'];
            }

        }
  //  d($TotalRepsAmountThisWeek);


//echo "$TotalSalesAmountThisWeek,$TotalSalesAmountThisWeekmonday,$TotalSalesAmountThisWeektuesday,$TotalSalesAmountThisWeekwednesday,$TotalSalesAmountThisWeekthursday,$TotalSalesAmountThisWeekfriday,$TotalSalesAmountThisWeeksaturday,$TotalSalesAmountThisWeeksunday";

//echo "$TotalRepsAmountThisWeekmonday,$TotalRepsAmountThisWeektuesday,$TotalRepsAmountThisWeekwednesday,$TotalRepsAmountThisWeekthursday,$TotalRepsAmountThisWeekfriday,$TotalRepsAmountThisWeeksaturday,$TotalRepsAmountThisWeeksunday";




?>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.1/animate.min.css">

        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/v/bs-3.3.6/jqc-1.12.3/dt-1.10.12/r-2.1.0/rr-1.1.2/datatables.min.css"/>

        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.6.1/fullcalendar.min.css">


        <link rel="stylesheet" href="<?php echo $basehref;?>/assets/css/cssLoader.css">
        <link rel="stylesheet" href="<?php echo $basehref;?>/assets/css/chartist.css">
        <link rel="stylesheet" href="<?php echo $basehref;?>/assets/css/base.css">

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

        <!-- Bootstrap -->
     <!--   <link href="<? echo $basehref;?>design/css/bootstrap.min.css" rel="stylesheet"> -->
        <!-- Font Awesome -->


<!-- js files -->
<!-- uploader scriptz -->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?php echo $basehref; ?>assets/uploader/js/vendor/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="//blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<!-- blueimp Gallery script -->
<script src="//blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo $basehref; ?>assets/uploader/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="<?php echo $basehref; ?>assets/uploader/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="<?php echo $basehref; ?>assets/uploader/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="<?php echo $basehref; ?>assets/uploader/js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="<?php echo $basehref; ?>assets/uploader/js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="<?php echo $basehref; ?>assets/uploader/js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="<?php echo $basehref; ?>assets/uploader/js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="<?php echo $basehref; ?>assets/uploader/js/jquery.fileupload-ui.js"></script>

<!-- end uploader -->


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<!-- Add fancyBox -->
<link rel="stylesheet" href="<?php echo $basehref;?>/assets/css/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo $basehref;?>/assets/js/jquery.fancybox.pack.js?v=2.1.5"></script>

       <!-- <script src="https://code.jquery.com/jquery-2.2.1.min.js"></script> -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.6.1/fullcalendar.min.js"></script>
        <script src="//cdn.bootcss.com/fullcalendar/2.6.1/lang/da.js"></script>

        <script src="<?php echo $basehref;?>/assets/js/chartist.js"></script>


<script src="<?php echo $basehref;?>/assets/js/js.js"></script>


<!-- end js  -->



        <div class="tile col-md-12 grey-light">
            <strong>Total omsætning</strong>
            <em><?php $totalrevenue = $TotalSalesAmountThisWeek + $TotalRepsAmountThisWeekMoney; echo $totalrevenue; ?>  DKK</em>
            <strong>Total Salg i kr</strong>
            <em><?php echo $TotalSalesAmountThisWeek; ?>  DKK</em>
            <strong>Total Reps i kr</strong>
            <em><?php echo $TotalRepsAmountThisWeekMoney; ?>  DKK</em>
                        <div class="ct-chart" id="dashboard-chart"></div>

            <script type="text/javascript">
            new Chartist.Line('.ct-chart', {
              labels: ['Mandag', 'Tirsdag', 'Onsdag', 'Torsdag', 'Fredag', 'Lørdag', 'Søndag'],
              series: [
                [<?php echo "$TotalSalesAmountThisWeek,$TotalSalesAmountThisWeekmonday,$TotalSalesAmountThisWeektuesday,$TotalSalesAmountThisWeekwednesday,$TotalSalesAmountThisWeekthursday,$TotalSalesAmountThisWeekfriday,$TotalSalesAmountThisWeeksaturday,$TotalSalesAmountThisWeeksunday"; ?>],
                [<?php echo "$TotalRepsAmountThisWeekmonday,$TotalRepsAmountThisWeektuesday,$TotalRepsAmountThisWeekwednesday,$TotalRepsAmountThisWeekthursday,$TotalRepsAmountThisWeekfriday,$TotalRepsAmountThisWeeksaturday,$TotalRepsAmountThisWeeksunday"; ?>]
              ]
            }, {
              fullWidth: true,
              chartPadding: {
                right: 40
              }
            });
            </script>
        </div>
    </div>

