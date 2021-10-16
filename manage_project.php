<?php
    session_start();   
    if(!isset($_SESSION["user_id"])){
        header("location:index.php");
    };
    include_once("include/header.php");
    include_once("include/sidebar.php");
    include_once("php/db.php");

    $project_id = $_GET["proj_id"];
 
?>
<div class="content">
    <div class="content-header">
        <div class="page-heading">


            <h2 id="project-name"></h2>
        </div>
        <div class="menu-button-wrapper">
            <div class="menu-button"></div>
        </div>


    </div>
    <div class="content-body">

        <div class="tables">
            <div class="table-wrapper">
                <table class="table">
                    <div class="table-heading">
                        <div class="add-project-btn-wrapper">
                            <button class="button" id="define-revenue-buttom">Define Revenue</button>
                        </div>
                        <div class="form-wrapper">
                            <form action="">
                                <!-- <input type="text" name="" id="search_batch" class="search" placeholder="search"> -->
                            </form>
                            <div class="revenue-count"></div>
                        </div>
                    </div>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Revenue Name</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                            
                            $del_status = 1;
                            $user_id = $_SESSION["user_id"];
                            $query = ("SELECT * FROM revenue_definition WHERE project_id = :project_id ");
                            $stmt = $conn->prepare($query);
                            $stmt->execute([":project_id"=>$project_id]);
                            $count = $stmt->rowCount();

                            if($count > 0){
                                $i = 1;
                                $rows = $stmt->fetchAll(PDO::FETCH_OBJ);
                                foreach($rows as $row){ ?>

                        <tr>
                            <td scope="row"><?php echo $i; ?></td>
                            <td><?php echo $row->revenue_name; ?></td>

                            <td>
                                <!-- <a class="action-button " id="view-project">Edit</a> -->

                                <?php
                                        if($_SESSION["user_role"] == 1){
                                    // echo '<a href="view_batch.php?batch_id='. $row->defined_revenue_id.'" class="action-button " id = "manage-batch">Delete</a>';

                                        }

                                    ?>

                            </td>
                        </tr>




                        <?php
                            $i++;
                                }
                                

                            }else{
                                echo "<tr>No revenue defined yet. </tr>";
                            }
                            // var_dump($count);

                        ?>



                    </tbody>
                </table>
            </div>

            <div class="table-wrapper">
                <table class="table">
                    <div class="table-heading">
                        <div class="add-project-btn-wrapper">
                            <button class="button" id="define-cost-btn">Define Cost</button>
                        </div>
                        <div class="form-wrapper">
                            <form action="">
                                <!-- <input type="text" name="" id="search_batch" class="search" placeholder="search"> -->
                            </form>
                        </div>
                    </div>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cost Name</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                            
                            $del_status = 1;
                            $user_id = $_SESSION["user_id"];
                            $query = ("SELECT * FROM cost_definition WHERE project_id = :project_id ");
                            $stmt = $conn->prepare($query);
                            $stmt->execute([":project_id"=>$project_id]);
                            $count = $stmt->rowCount();

                            if($count > 0){
                                $i = 1;
                                $rows = $stmt->fetchAll(PDO::FETCH_OBJ);
                                foreach($rows as $row){ ?>

                        <tr>
                            <td scope="row"><?php echo $i; ?></td>
                            <td class="displayed-cost-name"><?php echo $row->cost_name; ?></td>

                            <td>
                            <?php
                                // echo '<a class="action-button" id="edit-defined-cost" data-id="'.$row->defined_cost_id.'">Edit</a> ';

                               
                                        if($_SESSION["user_role"] == 1){
                                    // echo '<a class="action-button" id="delete-defined-cost" data-id="'.$row->defined_cost_id.'">Delete</a>';

                                        }

                                    ?>

                            </td>
                        </tr>




                        <?php
                            $i++;
                                }
                                

                            }else{
                                echo "<tr>No cost defined yet. </tr>";
                            }
                            // var_dump($count);

                        ?>



                    </tbody>
                </table>
            </div>
        </div>


                        </div>

    </div>



    <script>
        $(document).ready(function() {
            url = window.location.href.split("?proj_id=");
            var project_id = url[1];

            $.ajax({
                type: "get",
                url: "php/server.php",
                data: {
                    project_id: project_id
                },

                success: function(response) {
                    $("#project-name").html(response)
                }
            });

            $("#define-batch").click(function(e) {
                e.preventDefault();
                var batch_name = $("#batch-name").val();
                var start_date = $("#start-date").val();
                var end_date = $("#end-date").val();
                var define_batch = "define_batch";

                // console.log(batch_name)
                if (batch_name == "" || start_date == "" || end_date == "") {
                    $(".error").html("Fill in all fields");
                } else {
                    $.ajax({
                        type: "POST",
                        url: "php/server.php",
                        data: {
                            define_batch: define_batch,
                            project_id: project_id,
                            batch_name: batch_name,
                            start_date: start_date,
                            end_date: end_date,
                        },

                        success: function(response) {
                            if (response == "Success") {
                                $(".add-batch").css("display", "none");
                            } else {
                                $(".error").html("Batch name already exists");
                            }
                        }
                    });
                }
            });


            $("#define-revenue-buttom").click(function(e) {
                $(".revenue-define-modal").css("display", "block");
            }); 
            $(".revenue-define-modal").on("click","#close-button",function(e){
                e.preventDefault();
                $(".revenue-define-modal").css("display", "none");
                $(".error").html("");
            });

            $("#define-revenue-button").click(function(e){
                e.preventDefault();
                var project_id = $("#project-id-for-revenue").val();
                var revenue_name = $("#revenue-name").val();
                var define_revenue = "define_revenue";

                if(revenue_name == ""){
                    $(".error").html("Please fill in the field below");
                }
                else{
                    $.ajax({
                    type: "POST",
                    url: "php/server.php",
                    data: {
                        define_revenue : define_revenue,
                        project_id : project_id,
                        revenue_name : revenue_name,
                    },
                    success: function (response) {
                        if(response == "Success"){
                            window.location.reload();
                        }else{
                            $(".error").html("Revenue already recorded");
                        }
                    }
                });}
            });
            //end of define revenue

            $("#define-cost-btn").click(function(e){
                e.preventDefault();
                $(".cost-define-modal").css("display", "block");
            });
           
            $(".cost-define-modal").on("click","#close-button",function(e){
                e.preventDefault();
                $(".cost-define-modal").css("display", "none");
                $(".error").html("");
            });


            $("#define-cost-button").click(function(e){
                e.preventDefault();
                var project_id = $("#project-id-for-cost").val();
                var cost_name = $("#cost-name").val();
                var define_cost = "define_cost";
                var error = "";
                if(cost_name == ""){
                    error = "Please fill in the field below";
                    $(".error").html(error);
                }
                else{
                    $.ajax({
                    type: "POST",
                    url: "php/server.php",
                    data: {
                        define_cost : define_cost,
                        project_id : project_id,
                        cost_name : cost_name,
                    },
                    success: function (response) {
                        if(response == "Success"){
                            window.location.reload();
                        }else{
                            $(".error").html("Cost already recorded");
                        }
                    }
                });
            }
            });
            //end of define cost

            $(".table-wrapper").on("click","#delete-defined-cost",function(e){
                e.preventDefault();
                var delete_defined_cost = "delete_defined_cost";
                var defined_cost_id = $(this).data("id");
                // console.log(defined_cost_id);
                $.ajax({
                    type: "POST",
                    url: "php/server.php",
                    data: {
                        delete_defined_cost:delete_defined_cost,
                        defined_cost_id:defined_cost_id,
                    },
                    success: function (response) {
                        
                    }
                });
            });

            
            $(".table-wrapper").on("click","#edit-defined-cost",function(e){
                e.preventDefault();
                $(".cost-define-modal").css("display", "block");
                $(".form-header h2").html("Edit Cost");
                var defined_cost_name = $(this).data(".displayed-cost-name");
                var defined_cost_id = $(this).data("id");
                $("#cost-name").val(defined_cost_name);
                $(".define-cost-submit").css("display","none");
                $(".define-cost-edit").css("display","block");
                // $(".form-body").append('<input type = "hidden" id="defined-cost-id" data-id="'+defined_cost_id+'"/>');
                $(".id-value").html(defined_cost_id) ; //find out how to set a dat-id on button using jquery
            });
            
            $(".table-wrapper").on("click","#define-cost-edit",function(e){
                e.preventDefault();
                var defined_cost_name = $("#cost-name").val();
                var defined_cost_id = $("#id-value").text();
                var update_defined_cost = "update_defined_cost";
                var project_id = $("#project-id-for-cost").val();
                console.log(defined_cost_id);
                $.ajax({
                    type: "POST",
                    url: "php/server.php",
                    data: {
                        update_defined_cost:update_defined_cost,
                        defined_cost_id: defined_cost_id,
                        defined_cost_name:defined_cost_name,
                        project_id:project_id,
                    },
                    success: function (response) {
                        console.log(response)
                        if(response == "Success"){
                            $.alert(
                            "Updated reccorded!"
                        );
                            $(".cost-define-modal").css("display", "none");
                            location.reload();
                        }else{
                            $(".error").html("Cost update failed. Cost already saved");
                        }
                        // 
                    }
                });
            });

        });

    </script>

<!--- define revenue modal  -->

<div class="modal-wrapper revenue-define-modal" id="define-revenue-modal">

    <div class="form-wrapper">
        <form action="" method="post">
            <div class="form">
                <div class="form-header">
                    <h2>Define Revenue</h2>
                    <div class="modal-close">
                        <div class="close-button" id="close-button"></div>
                    </div>
                </div>
                <hr>
                <div class="form-components form-body">
                    <div class="error"></div>
                    <div class="form-body">
                        <input type="hidden" name="project_id" id="project-id-for-revenue" value="<?php echo $_GET["proj_id"];?>">
                        <label for="revenue-name">Revenue Name</label>
                        <input type="text" name="revenue-name" id="revenue-name" class="form-input">

                    </div>
                    <div class="form-footer">
                        <button type="submit" id="define-revenue-button" class="define-revenue-submit button">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- define costs modal -->


<div class="modal-wrapper cost-define-modal" id="define-cost-modal">

    <div class="form-wrapper">
        <form action="" method="post">
            <div class="form">
                <div class="form-header">
                    <h2>Define Cost</h2>
                    <div class="modal-close">
                        <div class="close-button" id="close-button"></div>
                    </div>
                </div>
                <hr>
                <div class="form-components form-body">
                    <div class="error"></div>
                    <div class="id-value" id="id-value"></div>
                    <div class="form-body">
                        <input type="hidden" name="project_id" id="project-id-for-cost" value="<?php echo $_GET["proj_id"];?>">
                        <label for="cost-name">Cost Name</label>
                        <input type="text" name="" id="cost-name" class="form-input">

                    </div>
                    <div class="form-footer">
                        <button type="submit" id="define-cost-button" class="define-cost-submit button">Save</button>
                        <button type="submit" id="define-cost-edit" class="define-cost-edit button">Update</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

    <?php
    include_once("include/footer.php");
?>
