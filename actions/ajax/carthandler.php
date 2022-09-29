<?php include("../../includes/config.php");




if(isset($_POST['action'])){



    if($_POST['action'] == 'addtocart'){


    $qtychosen = $_POST['qtychosen'];

    $productid = $_POST['productid'];
    if(isset($_POST['variations'])){
    $variations = $_POST['variations'];
    $copy = $variations; // this is used to add , between all attr. titles except the last one.
    }else{
        $variations = 0;
    }


//$_SESSION['cart'][$id][] = array($id, $colour, $size, $quantity);

//each attribute has an attribute group
//attribute group : color
//attribute: red, yellow,
//productvariations  kobler attribute + product sammen

                $producttitle = producttitlefromid($productid);

                $attributetitles = "";
                $uniqueidentifier = "";
                $uniqueidentifier .= $productid;

                if(is_array($variations)){

                    foreach ($variations as $key => $value) {

                      if(!empty($value)){
                          $uniqueidentifier .= "-";
                          $uniqueidentifier .= $value;
                          $attributetitles .= attributetitlefromid($value);
                          if (next($copy )) {
                             $attributetitles .= ', ';  // Add comma for all elements instead of last
                          }
                      }

                    }
                }elseif($variations >= 0){
                    if($variations !== 0){
                    $uniqueidentifier .= "-";
                    $uniqueidentifier .= $variations;
                    $attributetitles .= attributetitlefromid($variations); // TODO faster method? depends on how many attributes exist..
                    }

                }
                if(isset($_SESSION['cart'][$uniqueidentifier])){
                $quantity = intval($_SESSION['cart'][$uniqueidentifier]['quantity']);
                $_SESSION['cart'][$uniqueidentifier]['quantity'] = $quantity + $qtychosen;
                }
                if(!isset($_SESSION['cart'][$uniqueidentifier])){
                $_SESSION['cart'][$uniqueidentifier]['quantity'] = $qtychosen;
                }

                $quantity = $_SESSION['cart'][$uniqueidentifier]['quantity'];

                $totalprice = getpricefromuid($uniqueidentifier);
                $viptotalprice = getvippricefromuid($uniqueidentifier);


                $shipmentmethodid = shippingmethodidfromproductid($productid);
                $shippingprice = shippingmethodprice($shipmentmethodid);
              //  $shippingprice = $productid;


                $_SESSION['cart'][$uniqueidentifier]['producttitle'] = $producttitle;
                $_SESSION['cart'][$uniqueidentifier]['variationtitles'] = $attributetitles;

                $_SESSION['cart'][$uniqueidentifier]['totalprice'] = $totalprice;
                $_SESSION['cart'][$uniqueidentifier]['totalpriceallquantity'] = $totalprice * $quantity;

                $_SESSION['cart'][$uniqueidentifier]['VIPtotalprice'] = $viptotalprice;
                $_SESSION['cart'][$uniqueidentifier]['VIPtotalpriceallquantity'] = intval($viptotalprice) * intval($quantity);


                $_SESSION['cart'][$uniqueidentifier]['unitshippingprice'] = $shippingprice;
                $_SESSION['cart'][$uniqueidentifier]['totalshippingpriceforuid'] = $shippingprice * $quantity;




        getcartlisting();

    }

    if($_POST['action'] == 'updatecartquantity'){

        $uid = $_POST['uid'];
        $newqty = $_POST['amount'];
        $_SESSION['cart'][$uid]['quantity'] = $newqty;

        $totalprice = getpricefromuid($uid);
        $viptotalprice = getvippricefromuid($uid);

        $_SESSION['cart'][$uid]['totalprice'] = $totalprice;
        $_SESSION['cart'][$uid]['totalpriceallquantity'] = $totalprice * $newqty;

        $_SESSION['cart'][$uid]['VIPtotalprice'] = $viptotalprice;
        $_SESSION['cart'][$uid]['VIPtotalpriceallquantity'] = $viptotalprice * $newqty;



        $unitshippingprice = $_SESSION['cart'][$uid]['unitshippingprice'];
        $_SESSION['cart'][$uid]['totalshippingpriceforuid'] = $unitshippingprice * $newqty;



         //   $data = "success :P ";

    echo json_encode($_SESSION['cart'][$uid]);//['totalpriceallquantity'];
    //json_encode variabel
     //   return $data;
    }

    if($_POST['action'] == 'deletecart'){

    unset($_SESSION['cart']);
    echo "success";
    }

    if($_POST['action'] == 'updaterightcart'){

      $array = array();

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


      $completeprice = $subtotal + $totalshipping;
      $completepricevip = $subtotalvip + $totalshipping;


      $array['subtotal'] = $subtotal;
      $array['subtotalvip'] = $subtotalvip;
      $array['totalshipping'] = $totalshipping;
      $array['completeprice'] = $completeprice;
      $array['completepricevip'] = $completepricevip;

      if(isset($_SESSION['VIP'])){
        $USERVIP = 1;
      }else{
        $USERVIP = 0;
      }

      $array['VIP'] = $USERVIP;

   //   echo $subtotal;
   //   echo $subtotalvip;
   //   echo $totalshipping;
   //   echo $completeprice;
   //   echo $completepricevip;

   //   echo "test123";
      echo json_encode($array);

  }






    if($_POST['action'] == 'deletecartitem'){

    $cartitemuid = $_POST['uid'];
    unset($_SESSION['cart'][$cartitemuid]);
    //echo "success";
    $newtotalvalue = getcarttotalvalue();
    echo $newtotalvalue;

    }

    if($_POST['action'] == 'topcarttotalprice'){

    $newtotalvalue = getcarttotalvalue();
    echo $newtotalvalue;

    }



    if($_POST['action'] == 'getcart'){
    getcart();
    }

    if($_POST['action'] == 'getcarttotalprice'){


    }



    if($_POST['action'] == 'getcartlisting'){

        getcartlisting();
    }



    if($_POST['action'] == 'checkoutform'){

            var_dump($_POST);

            if(isset($_POST['firstname'])){
            $firstname = $_POST['firstname'];

            }else{
              $firstname = "";
            }
            if(isset($_POST['lastname'])){
            $lastname = $_POST['lastname'];

            }else{
              $lastname = "";
            }
            if(isset($_POST['phonenumber'])){
            $phonenumber = $_POST['phonenumber'];

            }else{
              $phonenumber = "";
            }
            if(isset($_POST['address'])){
            $address = $_POST['address'];

            }else{
             $address = "";
            }
            if(isset($_POST['zipcode'])){
            $zipcode = $_POST['zipcode'];

            }else{
              $zipcode = "";
            }
            if(isset($_POST['city'])){
            $city = $_POST['city'];

            }else{
              $city = "";
            }
            if(isset($_POST['email'])){
            $email = $_POST['email'];

            }else{
              $email = "";
            }
            if(isset($_POST['shippingoption'])){
            $shippingoption = $_POST['shippingoption'];

            }else{
              $shippingoption = "";
            }
            if(isset( $_POST['company'])){
            $company   =  $_POST['company'];

            }else{
              $company = "";
            }
            if(isset( $_POST['vatNumber'])){
            $vatNumber   =  $_POST['vatNumber'];

            }else{
              $vatNumber = "";
            }
            if(isset( $_POST['firstname2'])){
            $firstname2   =  $_POST['firstname2'];

            }else{
              $firstname2 = "";
            }
            if(isset( $_POST['lastname2'])){
            $lastname2   =  $_POST['lastname2'];

            }else{
              $lastname2 = "";
            }
            if(isset( $_POST['email2'])){
            $email2   =  $_POST['email2'];

            }else{
              $email2 = "";
            }
            if(isset( $_POST['phonenumber2'])){
            $phonenumber2   =  $_POST['phonenumber2'];

            }else{
              $phonenumber2 = "";
            }
            if(isset( $_POST['zipcode2'])){
            $zipcode2   =  $_POST['zipcode2'];

            }else{
              $zipcode2 = "";
            }
            if(isset( $_POST['city2'])){
            $city2   =  $_POST['city2'];

            }else{
              $city2 = "";
            }
            if(isset( $_POST['address2'])){
            $address2   =  $_POST['address2'];

            }else{
             $address2 = "";
            }
            if(isset( $_POST['company2'])){
            $company2   =  $_POST['company2'];

            }else{
             $company2 = "";
            }
            if(isset( $_POST['vatNumber2'])){
            $vatNumber2   =  $_POST['vatNumber2'];

            }else{
              $vatNumber2 = "";
            }
            if(isset( $_POST['newsletter'])){
            $newsletter   =  $_POST['newsletter'];

            }else{
              $newsletter = "";
            }
            if(isset( $_POST['StoreID'])){
            $StoreID   =  $_POST['StoreID'];

            }else{
              $StoreID = "";
            }

            if(isset($_POST['totalprice'])){
            $totalprice = $_POST['totalprice'];

            }else{
              $totalprice = "";
            }




if(isset($_SESSION['CustomerId'])){
  $customerid = $_SESSION['CustomerId'];
}else{


        $data = Array (
                  //      "type" => $type,
                        "firstname" => $firstname,
                        "lastname" => $lastname,
                        "phonenumber" => $phonenumber,
                        "address" => $address,
                        "zipcode" => $zipcode,
                        "city" => $city,
                        "email" => $email,
                       "company" => $company,
                        "vat_number" => $vatNumber,
                      //  "invoice_email" => $invoice_email,
                      //"ean_number" => $ean_number
        );
            $customerid = $db->insert ('customers', $data);

}


            if($customerid){
            // insert into order chosen

            $data = Array (
               "StoreID" =>  $StoreID,
               "firstname" =>  $firstname,
               "lastname" =>  $lastname,
               "phonenumber" =>  $phonenumber,
               "address" =>  $address,
               "zipcode" =>  $zipcode,
               "city" =>  $city,
               "email" =>  $email,
               "shippingoption" =>  $shippingoption,
               "company" =>  $company,
               "vatNumber" =>  $vatNumber,
               "firstname2" =>  $firstname2,
               "lastname2" =>  $lastname2,
               "email2" =>  $email2,
               "phonenumber2" =>  $phonenumber2,
               "zipcode2" =>  $zipcode2,
               "city2" =>  $city2,
               "address2" =>  $address2,
               "company2" =>  $company2,
               "vatNumber2" =>  $vatNumber2,
                "newsletter" => $newsletter,
                "CustomerID" => $customerid,
                "totalprice" => $totalprice
            );

            $orders = $db->insert ('orders', $data);
            if($orders){
                $orderid = $orders;
                $_SESSION['orderid'] = $orderid;
            foreach ($_SESSION['cart'] as $uid => $values) {
                if(isset($values['quantity'])){
                    $quantity = $values['quantity'];
                }else{
                    $quantity = '1';
                }
                $totalprice = getpricefromuid($uid);
                $totalpricecombined = $totalprice * $quantity;
                $ProductID = productidfromuid($uid);
                $totalprice_member = getvippricefromuid($uid);
                $totalpricecombined_member = $totalprice_member * $quantity;
                //is_member
                if(is_vip()){
                  $vip = 1;
                }else{
                  $vip = 0;
                }

                $data = Array (
                   "OrderID" =>  $orderid,
                   "ProductId" =>  $ProductID,
                   "uid" =>  $uid,
                   "qty" =>  $quantity,
                   "unitprice" =>  $totalprice,
                   "combinedprice" =>  $totalpricecombined,
                   "unitprice_member" => $totalprice_member,
                   "combinedprice_member" => $totalpricecombined_member,
                   "is_member" => $vip
                );
                 $db->insert ('orders_chosen', $data);



              ///////////
              //stock  //
              ///////////
              // Select (Get Old value and new value) and update leftinstock



                $db->where("uid", $uid); //1 is sms
                // $db->where("messageid", $mailid);
                $stock                = $db->getOne('stock');

                $oldvalue                 = $stock['leftinstock'];
                $newvalue                 = $stock['leftinstock'] - $quantity;

                $data = Array (
                    'leftinstock' => $newvalue
                );
                $db->where('uid', $uid);
                $db->update('stock', $data);


                // Insert into stock_history

                $data = Array (
                                  "OldValue" => $oldvalue,
                                 "NewValue" => $newvalue,
                                 "uid" => $uid,
                                 "CustomerID" => $customerid,
                                 "OrderID" => $orderid
                  );
                  $id = $db->insert ('stock_history', $data);
                  // TODO ADD CUSTOMERID AND ORDERID




                } // end for each
            } // end if orders chosen...
        } // end if order


        $_SESSION['CustomerId'] = $customerid;
        $_SESSION['CustomerEmail'] = $email;


// Send customer SMS & Email.
//SMS
                $mailid = "11";
                $db->where("type", "1"); //1 is sms
                $db->where("messageid", $mailid);
                $mailz                = $db->getOne('message_templates');


                $smscontent                 = $mailz['message'];

                $smscontent                 = str_replace('%navn%', $firstname, $smscontent);
                $smscontent                 = str_replace('%email%', $email, $smscontent);
                $smscontent                 = str_replace('%telefon%', $phonenumber, $smscontent);

       //         var_dump($phonenumber); var_dump($smscontent);
                //send_sms($phonenumber, 'Belibo', $smscontent);
//EMAIL
                $db->where("type", '0'); //0 is email
                $db->where("messageid", $mailid);
                $mailz                = $db->getOne('message_templates');
                $mailcontent                 = $mailz['message'];


                $mailcontent                 = str_replace('%navn%', $firstname, $mailcontent);
                $mailcontent                 = str_replace('%email%', $email, $mailcontent);
                $mailcontent                 = str_replace('%telefon%', $phonenumber, $mailcontent);


                $headers = "From: info@belibo.dk\r\n";
                $headers .= "Reply-To: info@belibo.dk\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";


          //      mail($email, 'Ordre modtaget', $mailcontent, $headers);

// Send customer to payment page.

    }// end if customerid






    if($_POST['action'] == 'updateheadcart'){

    getcartheaderlisting();

    }







    if($_POST['action'] == 'getquickpayinfo'){


        $orderid = get_unique_ordernumber_generator();
        $_SESSION['orderid'] = $orderid;
        $quickpayamount = getcarttotalvalue();  //value in øre..
        $quickpayamount = intval($quickpayamount) * 100;
        $quickpaycurrency = "DKK"; //DKK ?
        $quickpayversion = "v10";
        $cancellink = "$basehref/qp_fail";
        $callbackurl = "$basehref/actions/qp_callback.php"; //temp
        $continueurl = "$basehref/ordre-modtaget"; //temp
        $params = array(
          "version"      => $quickpayversion,
          "merchant_id"  => $qpmerchantid,
          "agreement_id" => $qpagreementid,
          "order_id"     => $orderid,
          "amount"       => $quickpayamount,
          "currency"     => $quickpaycurrency,
          "continueurl" => $continueurl,
          "cancelurl"   => $cancellink,
          "callbackurl" => $callbackurl,
        //  "type" => 'subscription', // default payment
        //  "autocapture" => '1'
          //"variables"   => array(
         //   "a" => "b",
         //   "c" => "d"
         // )
        );

        $params["checksum"] = sign($params, $qpapiuser);
        $checksumdisplay = $params["checksum"];


        $returnvalue = "

          <input type='hidden' name='version' value='$quickpayversion'>
          <input type='hidden' name='merchant_id' value='$qpmerchantid'>
          <input type='hidden' name='agreement_id' value='$qpagreementid'>
          <input type='hidden' name='order_id' value='$orderid'>
          <input type='hidden' name='amount' value='$quickpayamount'>
          <input type='hidden' name='currency' value='$quickpaycurrency'>
          <input type='hidden' name='continueurl' value='$continueurl'>
          <input type='hidden' name='cancelurl' value='$cancellink'>
          <input type='hidden' name='callbackurl' value='$callbackurl'>
          <input type='hidden' name='checksum' value='$checksumdisplay'>

        ";

        echo $returnvalue;


    }







} // end isset post action






