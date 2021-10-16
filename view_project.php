<?php
    session_start();   
    if(!isset($_SESSION["user_id"])){
        header("location:index.php");
    };
    include_once("include/header.php");
    include_once("include/sidebar.php");
    include_once("php/db.php");




    $project_id = $_GET["proj_id"];
    $query = ("SELECT * FROM project_batch WHERE project_id = :project_id");
   
    $stmt = $conn->prepare($query);
    $stmt->execute([":project_id"=> $project_id]);

    // $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    // var_dump($result);
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










    <div class="table-wrapper">
            <table class="table">
                <div class="table-heading">
                    <div class="add-project-btn-wrapper">
                        <?php if($_SESSION["user_role"]==1)
                        {echo '<button class="button" id="add-batch-button">Add Batch</button>';}?>
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
                        <th>Batch Name</th>
                        <th>Date</th>
                        <!-- <th>Date Created</th> -->
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
                        <td><?php echo $row->batch_name; ?></td>
                        <td><?php echo $row->start_date; ?></td>
                       
                        <td>
                                <!-- <a  class="action-button "  id = "view-project">Manage</a> -->

                                <?php
                                echo '<a href="view_batch.php?batch_id='. $row->batch_id.'" class="action-button " id = "manage-batch">Manage</a>';

                                    

                                ?>

                        </td>
                    </tr>

                                


                        <?php
                        $i++;
                            }
                            

                        }else{
                            echo "<tr>No Batches Yet. </tr>";
                        }
                        // var_dump($count);

                    ?>

                   
                
                </tbody>
            </table>
        </div>
    </div>
<script>
                        $(document).ready(function(){
                            url = window.location.href.split("?proj_id=");
                            var project_id = url[1];
                            
                            $.ajax({
                                type: "get",
                                url: "php/server.php",
                                data: {project_id:project_id},
                                
                                success: function (response) {
                                    $("#project-name").html(response)
                                }
                            });

                            $("#define-batch").click(function(e){
                                e.preventDefault();
                                var batch_name = $("#batch-name").val();
                                var start_date = $("#start-date").val();
                                var end_date = $("#end-date").val();
                                var define_batch = "define_batch";

                                // console.log(batch_name)
                                if(batch_name == ""|| start_date == "" || end_date == ""){
                                    $(".error").html("Fill in all fields");
                                }
                                else{
                                $.ajax({
                                    type: "POST",
                                    url: "php/server.php",
                                    data: {
                                        define_batch:define_batch,
                                        project_id:project_id, 
                                        batch_name:batch_name, 
                                        start_date:start_date, 
                                        end_date:end_date,
                                    },
                                    
                                    success: function (response) {
                                        if(response == "Success"){
                                            $(".add-batch").css("display","none");
                                            location.reload();
                                        }else{
                                            $(".error").html("Batch name already exists");
                                        }
                                    }
                                });
                            }
                            });

                            $(".add-project-btn-wrapper").on("click","#add-batch-button",function(e){
                                e.preventDefault();
                                $(".add-batch").css("display","block");
                            });

                            $(".add-batch").on('click','#close-button',function(e){
                                e.preventDefault();
                                $(".add-batch").css("display","none");
                            });

                            // all code above unless they are functions
                        });
</script>



<?php
    include_once("include/footer.php");
?>
