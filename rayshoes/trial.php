<?php
session_start();

include 'connect.php';
require 'register.php';

// Check if user is logged in
function isUserLoggedIn() {
    return isset($_SESSION['signIn']);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle Add to Cart
    if (isset($_POST['add_to_cart'])) {
        if (!isUserLoggedIn()) {
            // Redirect to login if not logged in
            header("Location: login.php?redirect=shop");
            exit();
        } else {
            // Add item to cart logic
            // This should be done via AJAX, so you might not need this here
            // But handle it if you process form submission in PHP
            // Redirect to cart or show success message
            // You can put your cart handling logic here
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>trial</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
<!-- Navigation bar -->
<nav class="navbar navbar-expand-md bg-light navbar-light py-3 fixed-top">
  <div class="container">
    <a class="navbar-brand" href="#">Ray ShoeGame</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Shop</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Contact Us</a></li>
        <li class="nav-item">
          <a href="cart.php"><i class='bx bx-cart'></i><span id="cart-item" class="badge badge-danger"></span></a>
          <a href="login.php"><i class='bx bx-user'></i></a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">
  <div class="container text-center mt-5 py-5">
    <h3>Welcome To our shop</h3>
    <hr>
    <p>Get top quality Shoes for the best prices!</p>
  </div>
  <div id="message"></div>
  <div class="row mt-3 pb-3">
    <?php
       $stmt = $cartConn->prepare("SELECT * FROM products");
       $stmt->execute();
       $result = $stmt->get_result();
       while ($row = $result->fetch_assoc()):
    ?>
    <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
      <div class="card-deck">
        <div class="card p-2 border-secondary mb-2">
          <img src="<?= $row['product_image'] ?>" class="card-img-top" height="250">
          <div class="card-body p-1">
            <h5 class="card-title text-center text-info"><?= $row['product_name'] ?></h5>
            <h5 class="card-text text-center text-danger">Kes. <?= number_format($row['product_price']) ?>/-</h5>
          </div>
          <div class="card-footer p-1">
            <form action="" method="POST" class="form-submit">
              <input type="hidden" class="pid" value="<?= $row['Id'] ?>">
              <input type="hidden" class="pname" value="<?= $row['product_name'] ?>">
              <input type="hidden" class="pprice" value="<?= $row['product_price'] ?>">
              <input type="hidden" class="pimage" value="<?= $row['product_image'] ?>">
              <input type="hidden" class="pcode" value="<?= $row['product_code'] ?>">
              <button type="submit" name="add_to_cart" class="btn btn-info btn-block addItemBtn">
                Add To Cart&nbsp;&nbsp;<i class="bi bi-cart-plus"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
</div>

<!-- Footer -->
<footer class="footer">
    <div class="container">
      <div class="row-foot">
        <div class="footer-col">
          <h4>Company</h4>
          <ul>
            <li><a href="#">About us</a></li>
            <li><a href="#">Our Services</a></li>
            <li><a href="#">Privacy policy</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Get Help</h4>
          <ul>
            <li><a href="#">FAQ</a></li>
            <li><a href="#">Shipping</a></li>
            <li><a href="#">Order status</a></li>
            <li><a href="#">Payment method</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Follow Us</h4>
          <div class="social-links">
            <a href="#"><i class="bi bi-facebook"></i></a>
            <a href="#"><i class="bi bi-instagram"></i></a>
            <a href="#"><i class="bi bi-twitter"></i></a>
          </div>
        </div>
      </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
  $(".addItemBtn").click(function(e){
    e.preventDefault();
    var $form = $(this).closest(".form-submit");
    var pid = $form.find(".pid").val();
    var pname = $form.find(".pname").val();
    var pprice = $form.find(".pprice").val();
    var pimage = $form.find(".pimage").val();
    var pcode = $form.find(".pcode").val();


    $.ajax({
      url: 'action.php',
      method: 'post',
      data: {pid:pid,pname:pname,pprice:pprice,pimage:pimage,pcode:pcode},
      success: function(response){
        $("#message").html(response);
        window.scrollTo(0,0);
        load_cart_item_number();
      }
    });
  });

  load_cart_item_number();

  function load_cart_item_number(){
    $.ajax({
      url: 'action.php',
      method: 'get',
      data: {cartItem: "cart_item"},
      success:function(response){
        $("#cart-item").html(response);
      }
    });
  }
});
</script>
</body>
</html>
