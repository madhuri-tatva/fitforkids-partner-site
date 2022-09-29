<?php
include("../../../includes/config.php");



    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'getattributes' && isset($_REQUEST['categoryid'])){

        $categoryid = $_REQUEST['categoryid'];
        $db->where("AttributeGroupID", $categoryid);
        $productattributesdata = $db->get("productattributes");

        echo "<option selected> Vælg venligst et produkt attribut </option>";

        foreach ($productattributesdata as $productattributesdata) {
            $productattributesdataId = $productattributesdata['Id'];
            $productattributesdataTitle  = $productattributesdata['Title'];
            echo "
            <option value='$productattributesdataId'>$productattributesdataTitle</option>
            ";

        }
    }

    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'updatevariationcombination'){


                $attributecombinationid = $_POST['attributecombinationid'];
                $attributeprice = $_POST['attributeprice'];
                $attributeid = $_POST['attributeid'];
                $attributegroupid = $_POST['attributegroupid'];
                $attributedisplayprice = $_POST['attributedisplayprice'];


                    $query3 = Array (
                    "ProductAttributeId" => $attributeid,
                    "ProductAttributeGroupId" => $attributegroupid,
                    "DisplayPriceInTitle" => $attributedisplayprice,
                    "Price" => $attributeprice
                    );

                    $db->where("Id", $attributecombinationid);

                        $stockquery3 = $db->update ('productvariations', $query3);

                        if($stockquery3){
                            // some confirmation on success? or not yet because no time xD
                            echo "Query 3 er fuldført";
                            }else{
                        $errormsg = 'insert failed: ' . $db->getLastError();
                        echo $errormsg;
                    }
    }



    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'updatevariationcombinationuid'){


                $attributecombinationiduid = $_POST['attributecombinationiduid'];
                $uidleftinstock = $_POST['uidleftinstock'];
                $uidPrice = $_POST['uidPrice'];
                $uidimage = $_POST['uidimage'];


                    $query3 = Array (
                    "leftinstock" => $uidleftinstock,
                    "image" => $uidimage,
                    "Price" => $uidPrice
                    );

                    $db->where("Id", $attributecombinationiduid);

                        $stockquery3 = $db->update ('stock', $query3);

                        if($stockquery3){
                            // some confirmation on success? or not yet because no time xD
                            echo "Query 3x62s er fuldført";
                            }else{
                        $errormsg = 'insert failed: ' . $db->getLastError();
                        echo $errormsg;
                    }
    }



    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'deletevariationcombination'){


                $attributecombinationid = $_POST['attributecombinationid'];

                    $db->where("Id", $attributecombinationid);

                        $stockquery3 = $db->delete ('productvariations');

                        if($stockquery3){
                            // some confirmation on success? or not yet because no time xD
                            echo "Query 3 er fuldført";
                            }else{
                        $errormsg = 'insert failed: ' . $db->getLastError();
                        echo $errormsg;
                    }
    }


    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'deletevariationcombinationuid'){


                $attributecombinationid = $_POST['attributecombinationid'];

                    $db->where("Id", $attributecombinationid);

                        $stockquery3 = $db->delete ('stock');

                        if($stockquery3){
                            // TODO  ADD SOME LOG IN DB THAT WE DELETED THE UID!
                            echo "Query 35 er fuldført";
                            }else{
                        $errormsg = 'insert failed: ' . $db->getLastError();
                        echo $errormsg;
                    }
    }



    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'newcombination'){


            ?>

<div class="combination">

    <div class="form-group">
        <label class="control-label col-sm-4 col-md-2" for="">
            Attribut
            <small>Vælg egenskab</small>
        </label>
        <div class="col-sm-8 col-md-5 attributegroup">
            <select class="categories form-control">
                <option selected>Vælg en egenskabsgruppe</option>
                <?php
                $db->orderBy("Id","asc");
                $categories = $db->get("productattributegroups");
                //d($variations);
                    foreach ($categories as $categories) {
                        $categoryId = $categories['Id'];
                        $categoryTitle = $categories['Title'];
                        echo "
                        <option value='$categoryId'>$categoryTitle</option>
                        ";
                    }
                ?>
            </select>

            <select class="productattributes form-control">
            </select>
        </div>
    </div>

</div> <!-- end combination -->

    <?php
    }



    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'newvariation'){


            ?>
            <div class="variationwrapper">
            <div class="combination">
            <select class="categories">
            <option selected> Vælg en Attribut kategori </option>
            <?php
            $db->orderBy("Id","asc");
            $categories = $db->get("productattributegroups");
            //d($variations);
                foreach ($categories as $categories) {
                    $categoryId = $categories['Id'];
                    $categoryTitle = $categories['Title'];
                    echo "
                    <option value='$categoryId'>$categoryTitle</option>
                    ";
                }
            ?>


            </select>


            <select class="productattributes">
            </select>
            </div> <!-- end combination -->



            <input type="button" class="addnewvariation" value="Tilkobl endnu en attribut til variationen">



            <input type="text" class="qty" value="" placeholder="Antal QTY ">

            <input type="text" class="extraprice" placeholder="Pris">




            <input type="button" class="savevariation" value="Gem">



            <br> <br> <br>

            </div> <!-- end variations -->

    <?php
    }





////////////////////
// Save variation //
////////////////////



    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'savevariation'){

var_dump($_POST);


/////////////////////////////////////
//Figure out correct order of UID  //
/////////////////////////////////////

// product id
// sort rest by category

$data = $_POST['data']; // all variations chosen
$productid = $_POST['productid'];
$leftinstock = $_POST['qty'];
$productid = $_POST['productid'];

//calculate UID
                $uidcalculate = array();
                $data_orig = $data;
                foreach ($data as $key => $row)
                {
                    $uidcalculate[$key] = $row['category'];
                }

                array_multisort($uidcalculate, SORT_ASC, $data);
                $ordered_uid = $data;
                echo "unordered uid";
                var_dump($data_orig);
                echo "ordered uid";
                var_dump($ordered_uid);


                $attributetitles = "";
                $uniqueidentifier = "";
                $uniqueidentifier .= $productid;

                if(is_array($ordered_uid)){

                    foreach ($ordered_uid as $ordered_uid_unfolded) {

                        if(!empty($ordered_uid_unfolded['attribute'])){
                            $uniqueidentifier .= "-";
                            $uniqueidentifier .= $ordered_uid_unfolded['attribute'];
                        } // 4-6-8
                    }
                }elseif($ordered_uid >= 0){
                    if($ordered_uid !== 0){
                        $uniqueidentifier .= "-";
                        $uniqueidentifier .= $ordered_uid;
                    }

                }



$uid = $uniqueidentifier;
var_dump($uid);
$leftinstock = $_POST['qty'];
$productid = $_POST['productid'];
$price = $_POST['price'];

//insert with uid into
/*
stock

    Id
    uid
    leftinstock
    shopID
    image
    CreateDate

 */


        $query1 = Array (
                        "uid" => $uid,
                        "ProductID" => $productid,
                        "leftinstock" => $leftinstock,
                        "Price" => $price
        );
            $stockquery1 = $db->insert ('stock', $query1);
            if($stockquery1){
                // some confirmation on success? or not yet because no time xD
            echo "Query 1 er fuldført";
            }else{
        $errormsg = 'insert failed: ' . $db->getLastError();
        echo $errormsg;
    }



//insert with uid into
//
//


$OldValue = 0;
$NewValue = $_POST['qty'];


        $query2 = Array (
                        "uid" => $uid,
                        "NewValue" => $NewValue
        );
            $stockquery2 = $db->insert ('stock_history', $query2);
            if($stockquery2){
                // some confirmation on success? or not yet because no time xD
            echo "Query 2 er fuldført";
            }else{
        $errormsg = 'insert failed: ' . $db->getLastError();
        echo $errormsg;
    }


/*

stock_history

Id
OrderID
CustomerID
uid
CreateDate
OldValue
NewValue

 */



//for each object
//insert into productvariations
//
//




$sku = $_POST['sku'];
$price = $_POST['price'];


$DisplayPriceInTitle = $_POST['DisplayPriceInTitle'];


                    if(is_array($ordered_uid)){

                    foreach ($ordered_uid as $ordered_uid_unfolded) {

                        if(!empty($ordered_uid_unfolded['attribute'])){
                      //      $uniqueidentifier .= "-";
                      //      $uniqueidentifier .= $ordered_uid_unfolded['attribute'];
                            $ProductAttributeId = $ordered_uid_unfolded['attribute'];
                            $ProductAttributeGroupId = $ordered_uid_unfolded['category'];

                        }

                    $query3 = Array (
                                                           "ProductId" => $productid,
                                                           "ProductAttributeId" => $ProductAttributeId,
                                                           "ProductAttributeGroupId" => $ProductAttributeGroupId,
                                                           "DisplayPriceInTitle" => $DisplayPriceInTitle,
                                                           "Price" => $price,
                                                           "sku" => $sku,
                                                           "Qty" => $leftinstock
                    );

/*
Id
ProductId
ProductAttributeId
ProductAttributeGroupId
DisplayPriceInTitle
Price
sku
Qty
CreateDate
img1
img2
img3
img4
img5
img6
thumbnailimage


 */



                    $db->where("ProductAttributeId", $ProductAttributeId);
                    $db->where("ProductAttributeGroupId", $ProductAttributeGroupId);
                    if($db->has("productvariations")) {
                       // return "You are logged";
                    //already exist.. dont do anyhting to avoid duplicates.
                    } else {

                        $stockquery3 = $db->insert ('productvariations', $query3);

                        if($stockquery3){
                            // some confirmation on success? or not yet because no time xD
                            echo "Query 3 er fuldført";
                            }else{
                        $errormsg = 'insert failed: ' . $db->getLastError();
                        echo $errormsg;
                    }

                    }


                    } // end for each

                    }








            ?>


    <?php
    }


/////////////////////
//Delete variation //
/////////////////////









/////////////////////
//Show variations for product //
/////////////////////

    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'getplistforproduct'){
        $productid = $_REQUEST['productid'];

            $db->where("ProductId", $productid);
            $variations = $db->get("productvariations");
            //d($variations);
                foreach ($variations as $variations) {
                    $CombiId = $variations['Id'];
                    $AttrId = $variations['ProductAttributeId'];
                    $AttrIdName = attributetitlefromid($AttrId);
                    $AttrGroupId = $variations['ProductAttributeGroupId'];
                    $AttrGroupIdName =  attributegrouptitlefromid($AttrGroupId);
                    $DisplayPriceInTitle = $variations['DisplayPriceInTitle'];
                    $PriceForShow =  $variations['Price'];
                    if($DisplayPriceInTitle == 1){
                    $vises = "$PriceForShow kr <b>vises</b> i titlen ";
                    }else{
                    $vises = "$PriceForShow kr <b>vises IKKE</b> i titlen ";
                    }

                    echo "
                       <tr>
                            <td>$AttrIdName #$AttrId</td>
                            <td>$AttrGroupIdName #$AttrGroupId</td>
                            <td>$vises ,-</td>
                            <td>
                                <a href='#' class='md-trigger btn btn-delete' id='deleter' data-modal='delete' data-attributecombinationid='$CombiId'><span class='fa fa-trash'></span>Slet</a>

                            <a href='#'
                            class='md-trigger btn btn-edit data-modal-edit-attribute'
                            data-modal='modal'

                             data-attributecombinationid='$CombiId'  data-attributeprice='$PriceForShow' data-attributeid='$AttrId' data-attributegroupid='$AttrGroupId'
                             data-attributegroupidname='$AttrGroupIdName' data-attributeidname='$AttrIdName' data-attributedisplayprice='$DisplayPriceInTitle'><span class='fa fa-pencil'></span>Ret</a>
                            </td>
                        </tr>
                    ";
                }
      }



/////////////////////
//Show UID Combinations for product //
/////////////////////

    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'getplistforuid'){
        $productid = $_REQUEST['productid'];

            $db->where("ProductID", $productid);
            $uids = $db->get("stock");
            //d($uids);
                foreach ($uids as $uids) {
                    $CombiId = $uids['Id'];
                    $uid = $uids['uid'];
                    $leftinstock = $uids['leftinstock'];
                    $Price =  $uids['Price'];
                    $image = $uids['image'];

                    if($Price > 0){
                    $vises = "$Price kr <b>anvendes ved denne kombi</b>";
                    }else{
                    $vises = "Standard pris anvendes";
                    }

                    echo "
                       <tr>
                            <td>UID: $uid</td>
                            <td>$leftinstock</td>
                            <td>$vises ,-</td>
                            <td>
                                <a href='#' class='md-trigger btn btn-delete' id='deleteruid' data-modal='deleteuid' data-attributecombinationid='$CombiId'><span class='fa fa-trash'></span>Slet</a>

                            <a href='#'
                            class='md-trigger btn btn-edit data-modal-edit-attributeuid'
                            data-modal='modaluid'

                             data-attributecombinationiduid='$CombiId'   data-uid='$uid' data-leftinstock='$leftinstock' data-Price='$Price' data-image='$image'><span class='fa fa-pencil'></span>Ret</a>
                            </td>
                        </tr>
                    ";
                }
      }



?>
