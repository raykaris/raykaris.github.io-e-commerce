<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- bootstrap icons link --> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

</head>
<body>
<!--navigation bar-->
<nav class="navbar navbar-expand-md bg-light navbar-light py-3 fixed-top">
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
        <a class="nav-link active" href="#">Shop</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Contact Us</a>
      </li>
      <li class="nav-item">
        <a href="cart.php"><i class='bx bx-cart'></i>
        <span id="cart-item" class="badge badge-danger"></span></a>
        <a href="login.php"><i class='bx bx-user'></i>Account</a>
      </li>
    </ul>
  </div>
  </div>
</nav>

<div class="container">
  <div class="container text-center mt-5 py-5">
    <h3>Welcome To our shop</h3>
    <hr>
    <span><p>Get top quality Shoes for the best prices!!</p></span>
  </div>
  <div id="message"></div>
  <div class="row mt-3 pb-3">
    <?php
       include 'connect.php';
       $stmt = $cartConn->prepare("SELECT * FROM products");
       $stmt->execute();
       $result = $stmt->get_result();
       while($row = $result->fetch_assoc()):
    ?>
    <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
      <div class="card-deck">
        <div class="card p-2 border-secondary mb-2">
          <img src="<?= $row['product_image'] ?>" class="card-img-top" height="250" >
          <div class="card-body p-1">
            <h5 class="card-title text-center text-info">
              <?= $row['product_name'] ?>
              <h5 class="card-text text-center text-danger">Kes.
                <?= number_format($row['product_price']) ?>/-
              </h5>
            </h5>
          </div>
          <div class="card-footer p-1">
            <form action="" method="POST" class="form-submit">
              <input type="hidden" class="pid" value="<?= $row['Id'] ?>">
              <input type="hidden" class="pname" value="<?= $row['product_name'] ?>">
              <input type="hidden" class="pprice" value="<?= $row['product_price'] ?>">
              <input type="hidden" class="pimage" value="<?= $row['product_image'] ?>">
              <input type="hidden" class="pcode" value="<?= $row['product_code'] ?>">
              <button class="btn btn-info btn-block addItemBtn">
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

    <!--footer-->
    <footer class="footer">
        <div class="container">
          <div class="row-foot">
            <div class="footer-col">
              <h4>Company</h4>
              <ul>
                <li><a href="#">About us</a></li>
                <li><a href="#"a>Our Services</a></li>
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
                <a href="#"><i class="bi bi-facebook"></a>
                <a href="#"><i class="bi bi-instagram"></a>
                <a href="#"><i class="bi bi-twitter"></a>
              </div>
            </div>
          </div>
        </div>
      </footer>



  <!-- scripts -->
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