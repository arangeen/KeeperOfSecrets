// connect to database
<?php
        $link = mysqli_connect("localhost", "root", "" );

        if(mysqli_connect_error()) {
            // end the script
            die("Database connection Error"); 
        }
?>