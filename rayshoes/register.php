<?php

include 'connect.php';

if(isset($_POST['signUp'])){
    $firstName=$_POST['fName'];
    $lastName=$_POST['lName'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $password=md5($password);

     $checkEmail="SELECT * From users where email='$email'";
     $result=$loginConn->query($checkEmail);
     if($result->num_rows>0){
        echo "Email already exists !";
     }
     else{
        $insertQuery="INSERT INTO users(firstName,lastName,email,password) 
                        VALUES ('$firstName','$lastName','$email','$password')";
            if($loginConn->query($insertQuery)==TRUE){
                header("location: login.php");
            }else{
                echo "Error:".$conn->error;
            }           
     }
}

if(isset($_POST['signIn'])){
    $email=$_POST['email'];
    $password=$_POST['password'];
    $password=md5($password);

    $sql="SELECT * FROM users WHERE email='$email' and password='$password'";
    $result=$loginConn->query($sql);
    if($result->num_rows>0){
        session_start();
        $row=$result->fetch_assoc();
        $_SESSION['email']=$row['email'];
        header("location: index.html");
        exit();
    }else{
        echo "Not Found, Incorrect Email or Password";
    }

}

?>
