<?php
session_start();

    include("connection.php");
    include("functions.php");
    require_once 'vendor/autoload.php';

    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
      //something was posted
      $user_name = $_POST['user_name'];
      $password = $_POST['password'];

      if(!empty($user_name) && !empty($password) && !is_numeric($user_name)){
        //read from database
        $query = "select * from users where user_name = '$user_name' limit 1";

        $result = mysqli_query($con, $query);

        if($result){
          if($result && mysqli_num_rows($result) > 0)
          {
              $user_data = mysqli_fetch_assoc($result);

              if($user_data['password'] === $password){
                $_SESSION['user_id'] = $user_data['user_id'];
                header("Location: CS411_project_webpage.html");
                die;
              }
          }
        }
        echo "Wrong username or password!";
      }else{
        echo "Wrong username or password!";
      }
    }

    

    if(isset($_GET['code'])){
      // Get Token
      $token = $gclient->fetchAccessTokenWithAuthCode($_GET['code']);
  
      // Check if fetching token did not return any errors
      if(!isset($token['error'])){
          // Setting Access token
          $gclient->setAccessToken($token['access_token']);
  
          // store access token
          $_SESSION['access_token'] = $token['access_token'];
  
          // Get Account Profile using Google Service
          $gservice = new Google_Service_Oauth2($gclient);
  
          // Get User Data
          $udata = $gservice->userinfo->get();
          foreach($udata as $k => $v){
              $_SESSION['login_'.$k] = $v;
          }
          $_SESSION['ucode'] = $_GET['code'];
  
          header('location: ./');
          exit;
        }
      }

?> 




<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

    <style type="text/css">
    
    #text{

        height: 25px;
        border-radius: 5px;
        padding: 4px;
        border: solid thin #aaa;
        width: 100%;
    }

    #botton{
        padding: 10px;
        width: 100px;
        color: white;
        background-color: lightblue;
        border: none
    }

    #box{
        background-color: grey;
        margin: auto;
        width: 300px;
        padding: 20px;
    }

    </style>

    <div id="box">

        <form method="post">
            <div style="font-size: 20px; margin: 10px; color: white;">Login</div>

            <input id = "text" type = "text" name = "user_name"><br><br>
            <input id = "text" type = "password" name = "password"><br><br>

            <input id = "button" type = "submit" value = "Login"><br><br>

            <a href = "signup.php">Click to Signup</a><br><br>
            <a href="<?= $gclient->createAuthUrl() ?>" class="btn btn btn-primary btn-flat rounded-0">Login with Google</a>

            
        </form>
    </div>

</body>
</html>

