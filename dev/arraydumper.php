<?php
require "../includes/config.php";

//use StathisG\GreekSlugGenerator\GreekSlugGenerator;
//echo GreekSlugGenerator::getSlug('The class can be used for ENGLISH-ONLY titles as well');

//use League\Csv\Reader;
use League\Csv\Writer;

//$reader = Reader::createFromPath('/path/to/your/csv/file.csv');
//the $reader object will use the 'r+' open mode as no `open_mode` parameter was supplied.
//
//
//$writer = Writer::createFromPath(new SplFileObject('/path/to/your/csv/file.csv', 'a+'), 'w');
//the $writer object open mode will be 'w'!!



header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="name-for-your-file.csv"');
//$reader = Reader::createFromString('john,doe,john.doe@example.com');
//$writer = Writer::createFromString('john,doe,john.doe@example.com');

echo $writer;
$rows = getallcustomers();
$writer = Writer::createFromFileObject(new SplTempFileObject());
$writer->setDelimiter(';');

$writer->insertAll($rows); //using an array

//$reader = Reader::createFromPath('/path/to/my/file.csv');
//$reader->output();
$writer->setOutputBOM(Writer::BOM_UTF8); // ensure Æ Ø Å to work
$writer->output("if-no-header-filename-this-will-be-used.csv");

//d(get_declared_classes());



/*
?>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">


        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

        <!-- Bootstrap -->
     <!--   <link href="http://pg.iomedia.dk/design/css/bootstrap.min.css" rel="stylesheet"> -->
        <!-- Font Awesome -->


<!-- js files -->
<!-- uploader scriptz -->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="http://pg.iomedia.dk/assets/uploader/js/vendor/jquery.ui.widget.js"></script>



  <!--   <script language="JavaScript" type="text/javascript" src="//code.jquery.com/jquery-1.12.4.min.js"></script> -->
        <script language="JavaScript" type="text/javascript" src="//code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>


        <script src="<?php echo $basehref; ?>assets/js/jquery.ui.core.js"></script>
        <script src="<?php echo $basehref; ?>assets/js/jquery.ui.widget.js"></script>




<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<!-- Add fancyBox -->


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>


<!-- adam start -->
                    <div class='md-col-4'>
                        <label for='reperations'>Reperationer</label>
                            <select name='reperations[]' id='reperations' multiple="multiple">


                <?php
                $model = '49';
                $db->where("ModelID",$model);
                $result = $db->get("deviceoperations");

                if($result){
                    foreach ($result as $row)
                    {

                    $reperationdata = reperationdatefromid($row['ReperationID']); // OPTIMIZE this, load all to an array and choose from that to minimize db connections

                    $reperationtitle = $reperationdata['Title'];
                    $reperationdescription = $reperationdata['Description'];
                   // $reperationprice = getreperationtypeprice($row['ReperationID'],$model);


                    //check if the below option is part of the $repschosen array, then put selected if true.
                    echo "<option data-title='".$reperationtitle."' reperationid='".$row['ReperationID']."' price='".$row['Price']."' value='".$row['Id']."'>" . $reperationtitle . " kr. " . $row['Price'] . "</option>";

                    }
                        }else{
                            echo "<option selected> Ingen reperationer fundet </option>";
                        }


                ?>


                            </select>
                            </div>


            <script>


      $(document).ready(function() {
            $('#reperations').multiselect({
                enableFiltering: true,
                //      includeSelectAllOption: true,
                maxHeight: 900,
                dropUp: false
            });
            $('#reperations').multiselect('rebuild');
      });


        </script>
                            <!-- adam end -->

<?php

//admindashboardcontentv3();
//echo "status:5 repid:90";
//$repid = '90';

//$function1 = getpricefromreparationid($repid);
//d($function1);


//$customerinfo = customerinfo('37');
//d($customerinfo);

//changestatuswithmailandsms('4', '38');
//
//

//d(getproductdata('1'));

//d(getproductattributedata('1'));


/*
$date = $_REQUEST['date'];

d(dayofweekfromdate($date));

$month=date("m");
//echo $month;


//admindisplaycalendarbookings('1','1');


define('TXT_MINUTES_MAX',  'max minutter');
define('TXT_HOURSS',  'max timer');
define('TXT_AND',  ' og ');
define('TXT_MINUTES',  ' minutter ');
define('TXT_MAX',  ' max ');
define('TXT_MIN',  ' min ');
define('TXT_MINUTES_MIN',  ' minuter minimum ');
*/


//d(getBookingText($shopid=1));

//d(getScheduleTable($date, $serviceID=1));

//d(getScheduleService('4', $date));


/*

$productvariations = productpagevariationsdropdowns('1');
d($productvariations);
$grouped = array_group_by( $productvariations, "ProductAttributeGroupId" );
d($grouped);

$productattributes_with_id_as_key = productattributes_with_id_as_key();
$productattributegroups_with_id_as_key = productattributegroups_with_id_as_key();




$i = 0;
foreach ($grouped as $grouped) {
    # code...
   // var_dump($grouped);
 //   echo $grouped[0]['Id'];
    d($grouped);
    $GroupID =  $grouped[$i]['ProductAttributeGroupId'];
    $GroupIDTitle = $productattributegroups_with_id_as_key[$GroupID]['Title'];
  //  echo $AttributeID;
    echo "
    <select id='attrgroup$GroupID' class='attributegroupdropdown'>
    <option selected>$GroupIDTitle</option>
    ";

      foreach($grouped as $groupednested){
        $AttributeID = $groupednested['ProductAttributeId'];
        $AttributeTitle = $productattributes_with_id_as_key[$AttributeID]['Title'];
        echo "<option value='$AttributeID'>$AttributeTitle</option> ";
      }
    echo "
    </select>
    ";
$i = $i + 1;

}


*/