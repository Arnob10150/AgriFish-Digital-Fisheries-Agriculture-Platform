<?php
require_once("../models/Product.php");

if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $product_id = $_POST["pro_id"];
    $product = new Product();
    $flag = $product->delete($product_id);

    if($flag)
    {
        header("Location:../views/admin_views/GetAllProducts.php?msg=deletion Successful");
    }
    else
    {
        header("Location:../views/admin_views/GetAllProducts.php?msg=deletion failed");
    }
}

?>
