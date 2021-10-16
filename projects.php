<?php
session_start();

if ( !isset( $_SESSION["user_id"] ) ) {
    header( "location:index.php" );
}
;

include_once( "include/header.php" );
include_once( "include/sidebar.php" );

include_once( "php/db.php" );
?>

<div class="content">
    <div class="content-header">
        <div class="page-heading">
            <h2>Projects</h2>
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
                        {echo '<button class="button" id="add-project-button">Add Project</button>';} ?>
                    </div>
                    <div class="form-wrapper">
                        <form action="">
                            <input type="text" name="" id="search_project" class="search" placeholder="search">
                        </form>
                    </div>
                </div>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Project Name</th>
                        <th>About</th>
                        <!-- <th>Date Created</th> -->
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    <?php

$del_status = 1;
$user_id = $_SESSION["sys_id"];
// $user_id = $_SESSION["user_id"];
$query = ( "SELECT  * FROM projects WHERE sys_id = :user_id AND delete_status = :delete_status" );
$stmt = $conn->prepare( $query );
$stmt->execute( [":user_id"=>$user_id, ":delete_status"=>$del_status] );
$count = $stmt->rowCount();

if ( $count > 0 ) {
    $i = 1;
    $rows = $stmt->fetchAll( PDO::FETCH_OBJ );
    foreach ( $rows as $row ) {
        ?>

                    <tr>
                        <td scope="row"><?php echo $i;
        ?></td>
                        <td><?php echo $row->project_name;
        ?></td>
                        <td><?php echo $row->project_description;
        ?></td>
                        <!-- <td>All about bees</td> -->
                        <td>
                            <a href="view_project.php?proj_id=<?php echo $row->project_id ?>" class="action-button " id="view-project">Batches</a>

                            <?php
        if ( $_SESSION["user_role"] == 1 ) {
            echo '<a href="manage_project.php?proj_id='.$row->project_id.'" class="action-button " id = "manage-project">Manage</a>';

        }

        ?>

                        </td>
                    </tr>

                    <?php
        $i++;
    }

} else {

}

?>

                </tbody>
            </table>
        </div>

        <style>

        </style>

    </div>
    <style>

    </style>

    <script>
        $(document).ready(function(){
            $("#add-project-button").click(function(e){
            e.preventDefault();
            $(".add-project").css("display","block");
        });



        $(".form-header").on("click","#close-button",function(e){
            e.preventDefault();
            $(".add-project").css("display","none");
        });


        
    // define project
    $("#add-project").on("click","#define-project" ,function (e) {
    e.preventDefault();
    // console.log("clicked");
    var project_name = $("#project-name").val();
    var project_description = $("#project-description").val();
    var set_project_name = "set_project_name";

        if(project_name == "" || project_description == ""){
            $(".error").html("Fill in all fields");
        }
        else{ 
            $.ajax({
            url: "php/server.php",
            method: "POST",
            data: {
                set_project_name: set_project_name,
                project_name: project_name,
                project_description: project_description,

            },
            success: function (response) {
                // console.log(response);

                var error = "";
                if (response == "Success") {
                
                    
                    $(".add-project").css("display", "none");
                    location.reload();
            
                } else if(response == "Exists") {
                    error = '<p class="bg-danger text-dark text-center">Project already exist</p>';
                    $(".error").css("display", "block");
                    $(".error").html(error);
                }else{
                    error = '<p class="bg-danger text-dark text-center">Project was deleted. Restore to use it.</p>';
                    

                        $(".error").css("display", "block");
                        $(".error").html(error);
                
                }
            },
        }); // ajax end
        }
    
    });
    });
        

    </script>
    <?php
include_once( "include/footer.php" );
?>
