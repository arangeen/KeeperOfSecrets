<?php

    if(array_key_exists("submit", $_POST)) {
        
        // connect to database
        $link = mysqli_connect("localhost", "root", "" );

        if(mysqli_connect_error()) {
            // end the script
            die("Database connection Error"); 
        }



        $error = "";
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
                    echo "sign up succcessful"; 

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
    <input type="submit" name="submit" value="Sign up!" >

</form>

    