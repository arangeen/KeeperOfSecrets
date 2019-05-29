<?php
    $error = "";

     //log user out 
    if(array_key_exists("logout", $_GET)) {

        unset($_SESSION); 
        setcookie("id", "", time() - 60*60); 
        $_COOKIE["id"] = ""; 
    }else if((array_key_exists("id", $_SESSION) AND $_SESSION['id']) 
    OR (array_key_exists("id", $_COOKIE) AND $_SESSION['id'])){

        // if users are logged in, they will be redirected to loggedinPage
        header("Location: loggedinPage.php"); 

    }


    //start session 
    session_start(); 

    if(array_key_exists("submit", $_POST)) {
        
        // connect to database
        $link = mysqli_connect("localhost", "root", "" );

        if(mysqli_connect_error()) {
            // end the script
            die("Database connection Error"); 
        }



       
        if(!$_POST['email']){
            //append to the error if email hasnt been put
            $error .= "An email address is required <br>";
        }
        if(!$_POST['password']){
            //append to the error if email hasnt been put
            $error .= "A password is required <br>";
        }
        if($error != ""){
            $error = "<p> There were error(s) in your input: </p>".$error; 
        }else {

            if ($_POST['signUp']=='1') {
                $query = "SELECT id FROM 'users' WHERE 
                            email = '".mysqli_real_escape_string($link, $_POST['email']). "' LIMIT 1";
                // run the query 
                $result = mysqli_query($link, $query); 

                if(mysqli_num_rows($result) > 0) {
                    $error = "That email address is taken."; 
                } else {
                    $query = "INSERT INTO 'users' ('email' , 'password') VALUES 
                    ( '".mysqli_real_escape_string($link, $_POST['email'])."' , 
                    '".mysqli_real_escape_string($link, $_POST['password'])."')";

                    if(!mysqli_query($link , $query)) {
                        $error = "<p> Could not sign you up. Please try again later. </p> "; 
                    }else {
                        $query = "UPDATE 'users' SET password = 
                        '".md5(md5(mysqli_insert_id()). $_POST['password'])."'
                        WHERE id = ".mysqli_insert_id($link)." LIMIT 1"; 

                        mysqli_query($link, $query); 

                        // keep session if stay logged in is selected 
                        $_SESSION['id'] = mysqli_insert_id($link); 
                        if($_POST['stayLoggedIn'] == '1'){
                            // we will save it for an hour for now. can do a year by multiple it all by 365
                            setcookie("id", mysqli_insert_id($link), time()+ 60*60*24);
                        }
                        header("Location: loggedinPage.php"); 
                        echo "sign up succcessful"; 

                    }
                }
            } else {

                //working on login part now 
                // doing a query to make sure they got user name and password correct 
                $query = "SELECT id FROM 'users' WHERE 
                email = '".mysqli_real_escape_string($link, $_POST['email']). "' ";
                // run the query 
                $result = mysqli_query($link, $query); 
                $row = mysqli_fetch_array($result); 

                // check to see if row exists 
                if(isset($row)) {
                    $hashedPassword = md5(md5($row['id']).$_POST['password']); 
                    
                    if($hashedPassword == $row['password']) {
                        $_SESSION['id'] = $row['id']; 
                        if($_POST['stayLoggedIn'] == '1') {
                            setcookie("id", $row['id'], time()+ 60*60*24); 
                        }
                        header("Location: loggedinPage.php"); 
                    }else {
                        $error = "That email/password combo could not be found. Try again."; 
                    }
                }else {
                    $error = "That email/password combo could not be found. Try again."; 
                }

            }
        } 
    }

?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Keeper of Secrets!</title>

    <style type="text/css">

        .container{
            text-align: center;
            width: 400px; 
            margin-top: 150px; 
        }

        // making background picture so it is stretched to the full screen 

        html {
            background: url(background.jpg) no-repeat center center fixed; 
            -webkit-background-size: cover; 
            -moz-background-size: cover; 
            -o-background-size: cover; 
            background-size: cover; 
        }

        body{
            background: none; 
            color: white; 
        }

        

    </style>

  </head>
  <body>
    
    <div class="container">

        <h1> The Keeper of Secrets </h1> 


        <div id="error"> <?php  echo $error; ?> </div>

        <form method="post">
            <fieldset class="form-group">
            <input class="form-control" type="email" name="email" placeholder="Enter your email">
            </fieldset>

            <fieldset class="form-group">
            <input class="form-control" type="password" name="password" placeholder="Enter your password">
            </fieldset>


            <div class= "checkbox">
            <label>
            <input type="checkbox" name="stayLoggedIn" value=1 >
            Stay Logged in 
            </label>
            </fieldset>
            </div>

            <fieldset class="form-group">
            <input class="form-control" type="hidden" name="signUp" value="1">
            </fieldset>

            <fieldset class="form-group">
            <input class="btn btn-success" type="submit" name="submit" value="Sign up!" >
            </fieldset>

        </form>

        <form method="post">
        <fieldset class="form-group">
            <input  class="form-control" type="email" name="email" placeholder="Enter your email">
            </fieldset>

            <fieldset class="form-group">
            <input class="form-control" type="password" name="password" placeholder="Enter your password">
            </fieldset>

            <div class= "checkbox">
            <label>
            <input type="checkbox" name="stayLoggedIn" value=1 >
            Stay Logged in 
            </label>
            </fieldset>
            </div>

            <fieldset class="form-group">
            <input class="form-control" type="hidden" name="signUp" value="1">
            </fieldset>

            <fieldset class="form-group">
            <input class="btn btn-success" type="submit" name="submit" value="Log In!" >
            </fieldset>

        </form>
    </div>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>





    