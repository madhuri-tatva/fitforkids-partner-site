<?php 
include("../../includes/config.php");

putenv('LC_ALL='.$_SESSION['Language']);
setlocale(LC_ALL, $_SESSION['Language']);

bindtextdomain($_SESSION['Language'], "../../locale");
bind_textdomain_codeset($_SESSION['Language'], 'UTF-8'); 

textdomain($_SESSION['Language']);

$customerId = $_POST['customer'];
$userId     = $_POST['user'];

$db->where('Id',$customerId);
$customer = $db->getOne('customers');

$db->where('Id',$userId);
$salesrep = $db->getOne('users');


if(!empty($customer)){
    $db->where("CustomerId",$customerId);
}

$db->where("ContactId",$userId);
$db->orderBy('Id','DESC');
$orders = $db->get('orders');


?>

<div class="box">

    <table class="table">

        <thead class="list-header">
            <tr>
                <th>ID#</th>
                <th>Customer</th>
                <th>Quote name</th>
                <th>Price</th>
                <th>Sales Rep.</th>
                <th class="no-sort sorting_disabled"></th>
            </tr>
        </thead>
        <tbody>

        <?php foreach($orders as $order){ 

        $db->where('Id',$order['CustomerId']);
        $customer = $db->getOne('customers');

        ?>


          <tr>
              <td><?php echo sprintf('%08d', $order['Id']); ?></td>
              <td><?php echo $customer['Company']; ?></td>
              <td><?php echo $order['Campaign']; ?></td>
              <td><?php echo $order['TotalPrice'] . " " . $order['Currency']; ?></td>
              <td><?php echo $salesrep['Firstname'] . " " . $salesrep['Lastname']; ?></td>
              <td>
                <a href="/order/<?php echo $order['Id']; ?>" class="fa fa-file"><span>Open</span></a>
                <a class="fa fa-file md-trigger" data-modal="modal-delete-order" onclick="deleteorder(<?php echo $order['Id']; ?>)"><span>Delete</span></a>
              </td>
          </tr>

        <?php } ?>

        </tbody>

    </table>

</div>

<script src="/assets/js/classie.js"></script>
<script src="/assets/js/modalEffects.js"></script>