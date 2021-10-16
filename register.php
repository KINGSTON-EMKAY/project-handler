<?php
    session_start();   
    if(!isset($_SESSION["user_id"])){
        header("location:index.php");
    };
    include_once("include/header.php");
    // include_once("include/sidebar.php");
    include_once("php/db.php");

    $query = ("SELECT * FROM system_users WHERE sys_id = :sys_id");
    $stmt = $conn->prepare($query);
    $stmt->execute([":sys_id"=>$_SESSION["sys_id"]]);
   
?>

<div class="form-wrapper">
    <form action="">
        <div class="form">
            <div class="form-header">
                <h2>Register Account</h2>
                <div class="modal-close">
                    <div class="close-button" id="close-button"></div>
                </div>
            </div>
            <br>
            <hr>

            <div class="form-components form-body">
           <span class="error"></span>
                <div class="form-element">
                    <label for="batch-name">First Name:</label> 
                    <input type="text" name="batch-name" id="firstname">
                </div>
                <div class="form-element">
                    <label for="batch-name">Last Name:</label> 
                    <input type="text" name="batch-name" id="lastname">
                </div>
                <div class="form-element">
                    <label for="batch-name">Email:</label> 
                    <input type="text" name="batch-name" id="email">
                </div>
                <div class="form-element">
                    <label for="batch-name">Password:</label> 
                    <input type="password" name="batch-name" id="password">
                </div>
                <div class="form-element">
                    <label for="batch-name">Repeat Password:</label> <span class="pwd-error"></span>
                    <input type="password" name="batch-name" id="repeat-password">
                </div>

            </div>
            <div class="form-footer">
                <button type="submit" class="button btn-save" id="create-user">Register</button>
                <a href="index.php">login here</a>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $("#create-user").click(function (e) { 
            e.preventDefault();
          
            var firstname = $("#firstname").val();
            var lastname = $("#lastname").val();
            var email = $("#email").val();
            var password = $("#password").val();
            var repeat_password = $("#repeat-password").val();
            var add_admin_user = "add_admin_user";

           
           if(repeat_password != password){
            $(".pwd-error").html("Passwords do not match!!");
           }
          else {
              if (firstname == "" || lastname == "" || email == ""||password == "") {
               $(".error").html("Fill in all fields below");
           } else {
                    $.ajax({
                        type: "POST",
                        url: "php/server.php",
                        data: {
                            add_admin_user:add_admin_user,
                            firstname:firstname,
                            lastname:lastname,
                            email:email,
                            password:password,
                        },
                        success: function (response) {
                        if(response == "Success"){
                            $(".add-users").css("display", "none");
                            location.reload();
                        }else{
                            $(".error").html("User already in system. Ask admin for password reset or login");
                        }
                            
                        }

                    });
                }
           }

        });
    });
//    });
</script>
<?php
    include_once("include/footer.php");

?>