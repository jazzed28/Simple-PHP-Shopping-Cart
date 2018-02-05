<?php

error_reporting(E_STRICT); // throw those notices

session_start(); // session starts

// index.php - A basic shopping cart with add to cart, and remove links

// Products array
// ######## please do not alter the following code ########
$products = array(
        array("name" => "Richard", "price" => 119.75),
        array("name" => "Peter", "price" => 160.50),
        array("name" => "Bandsaw", "price" => 361.13),
        array("name" => "James", "price" => 19.9),
        array("name" => "Mike", "price" => 15.45)
        );
// ##################################################

// Reset values
if (!isset($_SESSION["total"])) {
    $_SESSION["total"] = 0;
    for ($i = 0; $i < count($products); $i++) {
        $_SESSION["qty"][$i] = 0;
        $_SESSION["prices"][$i] = 0;
    }
}

// Empty
if (isset($_GET['empty'])) {
    if ($_GET["empty"] == 'true') {
        unset($_SESSION["qty"]); //The quantity for each product
        unset($_SESSION["prices"]); //The amount from each product
        unset($_SESSION["total"]); //The total cost
        unset($_SESSION["cart"]); //Which item has been chosen
    }
}

// Add
if (isset($_GET["add"])) {
    $i = $_GET["add"];
    $qty = $_SESSION["qty"][$i] + 1;
    $_SESSION["prices"][$i] = $products[$i]["price"] * $qty;
    $_SESSION["cart"][$i] = $i;
    $_SESSION["qty"][$i] = $qty;

    header('Location: '.$_SERVER['PHP_SELF']);
    die;
}

// Remove
if (isset($_GET["remove"])) {
    $i = $_GET["remove"];
    $qty = $_SESSION["qty"][$i];
    $qty--;
    $_SESSION["qty"][$i] = $qty;

    // remove item if quantity is zero

    if ($qty == 0) {
        $_SESSION["prices"][$i] = 0;
        unset($_SESSION["cart"][$i]);
    }
    else {
        $_SESSION["prices"][$i] = $products[$i]["price"] * $qty;
        header('Location: '.$_SERVER['PHP_SELF']);
        die;
    }
}


?>
 <h2>Product List</h2>
 <table>
   <tr>
   <th>Name</th>
   <th width="10px">&nbsp;</th>
   <th>Price</th>
   <th width="10px">&nbsp;</th>
   <th>Link</th>
   </tr>
 <?php

for ($i = 0; $i < count($products); $i++) {
?>
   <tr>
   <td><?php
    echo ($products[$i]["name"]); ?></td>
   <td width="10px">&nbsp;</td>
   <td><?php
    echo (number_format((float)$products[$i]["price"], 2, '.', '')); ?></td>
   <td width="10px">&nbsp;</td>
   <td><a href="?add=<?php
    echo ($i); ?>">Add to cart</a></td>
   </tr>
   <?php
}

?>
 <tr>
 <td colspan="5"></td>
 </tr>
 <tr>
 <td colspan="5"><a href="?empty=true">Empty Cart</a></td>
 </tr>
 </table>
 <?php

if (isset($_SESSION["cart"])) {
?>
 <br/><br/><br/>
 <h2>Cart</h2>
 <table>
 <tr>
 <th>Name</th>
 <th width="10px">&nbsp;</th>
 <th>Price</th>
 <th width="10px">&nbsp;</th>
 <th>Qty</th>
 <th width="10px">&nbsp;</th>
 <th>Total</th>
 <th width="10px">&nbsp;</th>
 <th>Link</th>
 </tr>
 <?php
    $total = 0;
    foreach($_SESSION["cart"] as $i) {
?>
 <tr>
 <td><?php
        echo ($products[$_SESSION["cart"][$i]]["name"]); ?></td>
 <td width="10px">&nbsp;</td>
 <td><?php
        echo (number_format((float)$products[$_SESSION["cart"][$i]]["price"], 2, '.', '')); ?></td>
 <td width="10px">&nbsp;</td>
 <td><?php
        echo ($_SESSION["qty"][$i]); ?></td>
 <td width="10px">&nbsp;</td>
 <td><?php
        echo (number_format((float)$_SESSION["prices"][$i], 2, '.', '')); ?></td>
 <td width="10px">&nbsp;</td>
 <td><a href="?remove=<?php
        echo ($i); ?>">Remove</a></td>
 </tr>
 <?php
        $total = $total + $_SESSION["prices"][$i];
    }

    $_SESSION["total"] = $total;
?>
 <tr>
 <td colspan="7">Totals : <?php
    echo "$".($total); ?></td>
 </tr>
 </table>
 <?php
}

?>
