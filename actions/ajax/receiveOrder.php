<?php 
include("../../includes/config.php");

$db->where('Id',$_POST['order']);
$order = $db->getOne('orders');

// CART
$cart = json_decode($order['Cart']);
/*foreach($cart as $item){

    foreach($item as $row){
                            
        $data = explode('::|',$row);
        if($data[0] == 'itemPhoto'){
            $data = '<img class="image" src="'.$data[1].'" />';
        }else{
            $data = $data[1];
        }
                            
    }

}*/


// CART DETAILS
?>

<script>
  var cart = {};
  var cartDB = json_decode($order['Cart']);


  cartDB.forEach(function (item, index) {
      console.log(item, index);
  });

  console.log('test');
  


  //{"16":["itemArtMo::|1017-1","itemClientArtNo::|","itemDescription::|Box koncept 32 ltr. - Clear","itemEAN13::|5710255101708 ","itemPackaging::|30","itemPhoto::|\/uploads\/thumb\/1017_thumb.png","itemAmount::|140","itemCurrency::|900","priceTotal::|126000","itemComments::|"]}

  $('.item').each(function(){

    var productId = $(this).attr('data-id');
    
        //var productQTY = parseInt($(this).find('.qty .selected').attr('data-value'));
        var productQTY = parseInt($(this).find('.field-qty').val());

        var itemQTY = 0;
        if(cart[productId]){

            itemQTY = parseInt(cart[productId][0].split("::|").pop());
            console.log(cart[productId]);
            
        }

        var details = [];

    if($(this).find('.field-qty').val() != 0){

      if(itemQTY){
        itemQTY += parseInt(productQTY);
      }else{
        itemQTY = parseInt(productQTY);
      }

            details.push('qty::|' + itemQTY);
            details.push('price::|' + 0);
            details.push('clientartno::|' + 0);
            details.push('comments::|' + 0);

            cart[productId] = details;

    }


  });

  setCookie('cart',JSON.stringify(cart),1);

    $('#msg').html('<div class="success">Products was successfully added to the quote</div>');

    $('#cart-mini').load('/modules/menu/minicart.php');

  console.log(getCookie('cart'));
</script>