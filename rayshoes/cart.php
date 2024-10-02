<?php
session_start();
 ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- bootstrap icons link --> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

</head>
<body>
<!--navigation bar-->
<nav class="navbar navbar-expand-md bg-light navbar-light  py-2 fixed-top">
  <div class="container">
  <a class="navbar-brand" href="#">Your Cart</a>

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
        <a href="login.php"><i class='bx bx-user'></i>Account</a>
      </li>
    </ul>
  </div>
  </div>
</nav>

<div class="container mt-5">
    <div class="row justify-content-center mt-5">
        <div class="col-lg-10">
            <div style="display:<?php if(isset($_SESSION['showAlert'])){echo $_SESSION['showAlert'];}else{echo 'none';} unset($_SESSION['showAlert']); ?>" class="alert alert-success alert-dismissible mt-3">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong><?php if(isset($_SESSION['message'])){echo $_SESSION['message'];} unset($_SESSION['showAlert']); ?></strong>
            </div>
            <div class="table-responsive mt-2">
                <table class="table table-bordered table-striped text-center">
                    <thead>
                    <tr>
                        <td colspan="7">
                            <h4 class="text-center text-info m-0">Products In Your Cart!</h4>
                        </td>
                    </tr>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>price</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>
                            <a href="action.php?clear=all" class="badge-danger badge p-1" onclick="return confirm('Are You Sure You Want to clear your cart?');">
                                <i class="bi bi-trash"></i>
                            &nbsp;&nbsp;Clear Cart</a>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        require 'connect.php';
                        $stmt = $cartConn->prepare("SELECT * FROM cart");
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $grand_total = 0;
                        while($row = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= $row['Id'] ?></td>
                            <input type="hidden" class="pid" value="<?= $row['Id'] ?>">
                            <td><img src="<?= $row['product_image'] ?>" width="50"></td>
                            <td><?= $row['product_name'] ?></td>
                            <td>Kes.<?=number_format($row['product_price']) ?></td>
                            <input type="hidden" class="pprice" value="<?= $row['product_price'] ?>">
                            <td><input type="number" class="form-control itemQty" value="<?= $row['qty'] ?>" style="width:75px;"></td>
                            <td>Kes.<?=number_format($row['total_price']) ?></td>
                            <td>
                                <a href="action.php?remove=<?= $row['Id'] ?>" class="text-danger lead" onclick="return confirm('Are you sure you want to remove this item?');">
                                    <i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php $grand_total +=$row['total_price']; ?>
                        <?php endwhile; ?>
                        <tr>
                            <td colspan="3">
                                <a href="shop.php" class="btn btn-success">Continue Shopping</a>
                            </td>
                            <td colspan="2"><b>Grand Total</b></td>
                            <td>Kes.<b><?= number_format($grand_total); ?></b></td>
                            <td>
                                <a href="checkout.php" class="btn btn-info <?= ($grand_total>1)?"":"disabled"; ?>">
                                    <i class="bi bi-credit-card"></i>&nbsp;&nbsp;Checkout</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
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

        $(".itemQty").on('change', function(){
            var $el = $(this).closest('tr');

            var pid = $el.find(".pid").val();
            var pprice = $el.find(".pprice").val();
            var qty = $el.find(".itemQty").val();
            location.reload(true);
            
            $.ajax({
                url: 'action.php',
                method: 'post',
                cache: false,
                data: {qty:qty,pid:pid,pprice:pprice},
                success: function(response){
                    
                    console.log(response);
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