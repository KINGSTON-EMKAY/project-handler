<?php
    session_start();   
    if(!isset($_SESSION["user_id"])){
        header("location:index.php");
    };
    include_once("include/header.php");
    include_once("include/sidebar.php");
    include_once("php/db.php");

    $query = ("SELECT * FROM system_users WHERE sys_id = :sys_id");
    $stmt = $conn->prepare($query);
    $stmt->execute([":sys_id"=>$_SESSION["sys_id"]]);
   
?>

<div class="content">
    <div class="content-header">
        <div class="page-heading">
            <h2>System Users</h2>
        </div>
        <div class="menu-button-wrapper">
            <div class="menu-button"></div>
        </div>


    </div>
    <div class="content-body">






        <div class="table-wrapper">
            <table class="table">
                <div class="table-heading">
                    <div class="add-project-btn-wrapper">
                        <button class="button" id="add-user-button">Add User</button>
                    </div>
                    <div class="form-wrapper">
                        <form action="">
                            <input type="text" name="" id="search_batch" class="search" placeholder="search">
                        </form>
                    </div>
                </div>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Roles</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                        
                        $del_status = 1;
                        $user_id = $_SESSION["user_id"];
                        
                        $count = $stmt->rowCount();

                        if($count > 0){
                            $i = 1;
                            $rows = $stmt->fetchAll(PDO::FETCH_OBJ);
                            foreach($rows as $row){ ?>

                    <tr>
                        <td scope="row"><?php echo $i; ?></td>
                        <td><?php echo $row->firstname." ".$row->lastname; ?></td>
                        <td><?php echo $row->email; ?></td>
                        <td><?php if($row->user_role == 1){echo "Creator" ;}else{echo "Viewer";} ?></td>


                        <td>
                            <!-- <a  class="action-button "  id = "view-project">Manage</a> -->

                            <?php
                            if($_SESSION["user_role"] == 1){ ?>
                            <a href="#" data-id="<?php echo $row->user_id; ?>" class="action-button delete-user">Delete</a>
                            <a href="#" data-id="<?php echo $row->user_id; ?>" class="action-button reset-password-call">Password</a>

                            <?php  }

                        ?>

                        </td>
                    </tr>




                    <?php
                        $i++;
                            }
                            

                        }else{
                            echo "<tr>No Users Yet. </tr>";
                        }
                        // var_dump($count);

                    ?>



                </tbody>
            </table>
        </div>
    </div>










</div>
</div>
<div class="modal-wrapper add-users" id="add-users">

    <div class="form-wrapper">
        <form action="">
            <div class="form">
                <div class="form-header">
                    <h2>Add User</h2>
                    <div class="modal-close">
                        <div class="close-button" id="close-button"></div>
                    </div>
                </div>
                <hr>

                <div class="form-components form-body">
                    <div class="form-element">
                        <span class="error"></span>
                        <!-- <label for="batch-name">System ID:</label> <span class="error"></span> -->
                        <input type="hidden" name="batch-name" id="sys-id" value="<?php echo $_SESSION["sys_id"] ;?>" readonly>
                    </div>
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

                </div>
                <div class="form-footer">
                    <button type="submit" class="button btn-save" id="add-user">Save</button>
                </div>
            </div>
        </form>
    </div>


</div>


<div class="modal-wrapper password-reset-modal" id="reset-user-password">

    <div class="form-wrapper">
        <form action="">
            <div class="form">
                <div class="form-header">
                    <h2>Reset/Change Pasword</h2>
                    <div class="modal-close">
                        <div class="close-button" id="close-button"></div>
                    </div>
                </div>
                <hr>

                <div class="form-components form-body">
                    <div class="form-element">
                        <span class="error"></span>
                        <!-- <label for="batch-name">System ID:</label> <span class="error"></span> -->
                        <input type="hidden" name="batch-name" id="user-id" value="" readonly>
                    </div>
                    <div class="form-element">
                        <label for="batch-name">New Password:</label>
                        <input type="password" name="batch-name" id="new-password">
                    </div>
                    <div class="form-element">
                        <label for="batch-name">Repeat New Password:</label>
                        <input type="password" name="batch-name" id="repeat-new-password">
                    </div>


                </div>
                <div class="form-footer">
                    <button type="submit" class="button btn-save" id="change-password">Update Password</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {

        $("#add-user-button").click(function(e) {
            e.preventDefault();
            $(".add-users").css("display", "block");
        });
        $("#close-button").click(function(e) {
            e.preventDefault();
            $(".add-users").css("display", "none");
            $(".error").html("");
        });
        $("#add-user").click(function(e) {
            e.preventDefault();
            var sys_id = $("#sys-id").val();
            var firstname = $("#firstname").val();
            var lastname = $("#lastname").val();
            var email = $("#email").val();
            var password = $("#password").val();
            var add_user = "add_user";

            if (firstname == "" || lastname == "" || email == "" || password == "") {
                $(".error").html("Fill in all fields below");
            } else {
                $.ajax({
                    type: "POST",
                    url: "php/server.php",
                    data: {
                        add_user: add_user,
                        sys_id: sys_id,
                        firstname: firstname,
                        lastname: lastname,
                        email: email,
                        password: password,
                    },
                    success: function(response) {
                        if (response == "Success") {
                            $(".add-users").css("display", "none");
                            location.reload();
                        } else {
                            $(".error").html("User already in system. Ask admin for password reset");
                        }
                    }
                });
            }

        });

        $("a.reset-password-call").click(function(e) {
            e.preventDefault();
            $(".password-reset-modal").css("display", "block");

            var user_id = ($(this).data("id"));
            $("#user-id").val(user_id);

        });
        $("#change-password").click(function(e) {
            e.preventDefault();
            var new_password = $("#new-password").val();
            var new_password_repeat = $("#repeat-new-password").val();
            var user_id = $("#user-id").val();
            if (new_password == "" || new_password_repeat == "") {
                $(".error").html("All fields must be filled");
            } else {
                if (new_password_repeat != new_password) {
                    $(".error").html("Passwords do not match");
                } else {
                    var change_password = "change_password";
                    $.ajax({
                        type: "POST",
                        url: "php/server.php",
                        data: {
                            change_password: change_password,
                            new_password: new_password,
                            user_id:user_id,
                        },
                        success: function(response) {
                            if (response == "Success") {
                               
                                $(".password-reset-modal").css("display", "none");
                                
                            } else {
                                $(".error").html("Password update failed");

                            }
                        }
                    });
                }
            }
        });
        // all code above
    });

</script>
<?php
    include_once("include/footer.php");
?>
