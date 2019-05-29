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


<div id="error"> <?php  echo $error; ?> </div>

<form method="post">
    <input type="email" name="email" placeholder="Enter your email">
    <input type="password" name="password" placeholder="Enter your password">
    <input type="checkbox" name="stayLoggedIn" value=1 >
    <input type="hidden" name="signUp" value="1">
    <input type="submit" name="submit" value="Sign up!" >

</form>

<form method="post">
    <input type="email" name="email" placeholder="Enter your email">
    <input type="password" name="password" placeholder="Enter your password">
    <input type="checkbox" name="stayLoggedIn" value=1 >
    <input type="hidden" name="logIn" value="0">
    <input type="submit" name="submit" value="Log in!!" >

</form>

    