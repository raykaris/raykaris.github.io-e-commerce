<?php
session_start();
require 'connect.php';

//checking if a user is logged in
function isUserLoggedIn(){
    return isset($_SESSION['signIn']);
}
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //checking if add to cart button is clicked
    if(isset($_POST['add to cart'])){
        if(!isUserLoggedIn()){
            //redirect to login/signup if not logged in
            header("location: login.php?redirect=cart");
            exit();
        }else{
            $Id = $_POST['Id'];
            header("location: cart.php");
        }
    }
}

if(isset($_POST['pid'])){
    $pid = $_POST['pid'];
    $pname = $_POST['pname'];
    $pprice = $_POST['pprice'];
    $pimage = $_POST['pimage'];
    $pcode = $_POST['pcode'];
    $pqty = 1;

    $stmt = $cartConn->prepare("SELECT product_code FROM cart WHERE product_code=?");
    $stmt->bind_param("s",$pcode);
    $stmt->execute();
    $res =  $stmt->get_result();
    $r = $res->fetch_assoc();
    $code = $r['product_code'];

    if(!$code){
        $query = $cartConn->prepare("INSERT INTO cart (product_name,product_price,product_image,qty,total_price,product_code)
        VALUES (?,?,?,?,?,?)");
        $query->bind_param("sssiss",$pname,$pprice,$pimage,$pqty,$pprice,$pcode);
        $query->execute();

        echo '<div class="alert alert-success alert-dismissible">
                 <button type="button" class="close" data-dismiss="alert">&times;</button>
                 <strong>Item Added To cart!</strong> 
              </div>';
    }else{
        echo '<div class="alert alert-danger alert-dismissible">
                 <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Item Already exists!</strong> 
             </div>';
    }
}

if(isset($_GET['cartItem']) && isset($_GET['cartItem']) == 'cart_item'){
    $stmt = $cartConn->prepare("SELECT * FROM cart");
    $stmt->execute();
    $stmt->store_result();
    $rows = $stmt->num_rows;

    echo $rows;
}

if(isset($_GET['remove'])){
    $Id = $_GET['remove'];

    $stmt = $cartConn->prepare("DELETE FROM cart WHERE Id=?");
    $stmt->bind_param("i",$Id);
    $stmt->execute();

    $_SESSION['showAlert'] = 'block';
    $_SESSION['message'] = 'Item successfully removed';
    header('location:cart.php');
}

if(isset($_GET['clear'])){
    $stmt = $cartConn->prepare("DELETE FROM cart");
    $stmt->execute();
    $_SESSION['showAlert'] = 'block';
    $_SESSION['message'] = 'All Items successfully removed';
    header('location:cart.php');
}

if(isset($_POST['qty'])){
    $qty = $_POST['qty'];
    $pid = $_POST['pid'];
    $pprice = $_POST['pprice'];

    $tprice = $qty*$pprice;

    $stmt = $cartConn->prepare("UPDATE cart SET qty=?, total_price=? WHERE Id=?");
    $stmt->bind_param("isi",$qty,$tprice,$pid);
    $stmt->execute();
}

if(isset($_POST['action']) && isset($_POST['action']) == 'order'){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $products = $_POST['products'];
    $grand_total = $_POST['grand_total'];
    $address = $_POST['address'];
    $pmode = $_POST['pmode'];

    $data = '';

    $stmt = $cartConn->prepare("INSERT INTO orders (name,email,phone,address,pmode,products,amount_paid) VALUES(?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssss",$name,$email,$phone,$address,$pmode,$products,$grand_total);
    $stmt->execute();
    $data .= '<div class="text-center">
                 <h1 class="display-4 mt-2 text-danger">Thank You!</h1>
                 <h2 class="text-success">Your Order Has been Placed Successfully!</h2>
                 <h4 class="bg-danger text-light rounded p-2">Items Purchased: '.$products.'</h4>
                 <h4>Your Name: '.$name.'</h4>
                 <h4>Your E-mail: '.$email.'</h4>
                 <h4>Your phone Number: '.$phone.'</h4>
                 <h4>Method Of Payment: '.$pmode.'</h4>
                 <h4>Total Amount Paid: Kes.'.number_format($grand_total).'</h4>   
              </div>';
    echo $data;
}

?>
 