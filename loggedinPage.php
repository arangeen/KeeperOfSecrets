<?php 

    session_start(); 

    // check to see if there is a cookie 
    if(array_key_exists("id", $_COOKIE)) {
        $_SESSION['id'] = $_COOKIE['id']; 
    }


    //check to see if there is a session 
    if(array_key_exists("id", $_SESSION)){
        echo "Logged in! <a href = 'index.php?logout = 1'> Log out </a><"; 
    } else {
        header("Location: index.php"); 
    }

?>