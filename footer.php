
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <!-- Add jquery that will allow you to switch between login and sign up -->
    <script type= "text/javascript"> 

    $("#signUpform").toggle(); 
    $("#loginform").toggle(); 
    
    $('#diary').bind('input propertychange', function() {

        $.ajax({
            method: "POST",
            url:"updateDatabase.php",
            data: {content: $("#diary").val()}
        })
        .done(function(msg){
            alert("Data Saved: "+msg); 
        }); 

    }); 

  

    </script> 

  </body>
</html>
