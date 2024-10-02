<?php
require 'connect.php';

$grand_total = 0;
$allItems = '';
$items = array();

$sql = "SELECT CONCAT(product_name, '(',qty,')') AS ItemQty, total_price FROM cart";
$stmt = $cartConn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
while($row = $result->fetch_assoc()){
    $grand_total +=$row['total_price'];
    $items[] = $row['ItemQty'];
}
$allItems = implode(",", $items);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- bootstrap icons link --> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

</head>
<body>
<!--navigation bar-->
<nav class="navbar navbar-expand-md bg-light navbar-light py-2 fixed-top ">
  <div class="container">
  <a class="navbar-brand" href="#">Ray ShoeGame</a>

  <!-- Toggler/collapsibe Button -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Navbar links -->
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link " href="index.html">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="shop.php">Shop</a>
      </li>
      <li class="nav-item">
        <a href="cart.php"><i class='bx bx-cart'></i>
        <span id="cart-item" class="badge badge-danger"></span></a>
        <a href="login.php"><i class='bx bx-user'></i></a>
      </li>
    </ul>
  </div>
  </div>
</nav>

<div class="container">
  <div class="row justify-content-center mt-5">
    <div class="col-lg-6 px-4 pb-4" id="order">
        <h4 class="text-center text-info p-2">Complete Your Order</h4>
        <div class="jumbotron p-3 mb-2 text-center">
            <h5 class="lead"><b>Product(s) : </b><?= $allItems; ?></h5>
            <h5 class="lead"><b>Delivery Charge :</b>Kes.100</h5>
            <h5><b>Total Amount Payable : </b>Kes.<?= number_format($grand_total) ?>/-</h5>
        </div>
        <form action="" method="post" id="placeOrder">
            <input type="hidden" name="products" value="<?= $allItems; ?>">
            <input type="hidden" name="grand_total" value="<?= $grand_total; ?>">
            <div class="form-group">
                <input type="text" name="name" class="form-control" placeholder="Enter Your Name" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Enter  Email" required>
            </div>
            <div class="form-group">
                <input type="tel" name="phone" class="form-control" placeholder="Enter Your Phone Number" required>
            </div>
            <div class="form-group">
                <textarea name="address" class="form-control" rows="3" cols="10" placeholder="Enter Delivery Address..."></textarea>
            </div>
            <h5 class="text-center lead">Select Payment Mode</h5>
            <div class="form-group">
              <select name="pmode" class="form-control">
                <option value="" selected disabled>->select payment mode-<</option>
                <option value="cards">Debit/Credit card</option>
                <option value="mpesa">Mpesa</option>
                <option value="pod">Payment On Delivery</option>
              </select>
            </div>
            <div class="form-group">
              <input type="submit" name="submit" value="Place Order" class="btn btn-danger btn-block">
            </div>
        </form>
    </div>
  </div>
</div>

   



  <!-- jQuery library -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function(){
      
      $("#placeOrder").submit(function(e){
        e.preventDefault();
        $.ajax({
          url: 'action.php',
          method: 'post',
          data: $('form').serialize()+"&action=order",
          success: function(response){
            $("#order").html(response);
          }
        });
      });

      load_cart_item_number();

      function load_cart_item_number(){
        $.ajax({
          url: 'action.php',
          method: 'get',
          data: {cartItem:"cart_item"},
          success:function(response){
            $("#cart-item").html(response);
          }
        });
      }
    });
  </script>
</body>
</html>