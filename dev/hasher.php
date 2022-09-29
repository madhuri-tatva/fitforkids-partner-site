<?php
require "../includes/config.php";
if(isset($_GET['pass'])){
  $hashkey = doubleSalt($_GET['pass']);
ddd($hashkey);
}else{
    echo "set pass";
}
