<?php
    session_start();   
    if(!isset($_SESSION["user_id"])){
        header("location:index.php");
    };
    include_once("include/header.php");
    include_once("include/sidebar.php");
    include_once("php/db.php");
?>

<div class="content">
    <div class="content-header">
        <div class="page-heading">
            <h2>Reports</h2>
        </div>
        <div class="menu-button-wrapper">
            <div class="menu-button"></div>
        </div>


    </div>
    <div class="content-body">
        <div class="selectors">
            <div class="report-form-wrapper">
                <form action="" method="post">
                    <!-- <label for="report-type">Select Project</label> -->
                    <div class="report-form-fields">
                        <div id="project" class="report-select">
                            <select name="report-type" id="report-type">
                                <!-- <option value="batch">Batch</option> -->
                                <option value="null">Select Project</option>
                                <!-- <option value="all">All Projects</option> -->
                                <?php
                                    $query = ("SELECT * FROM projects WHERE sys_id = :user_id");
                                    $stmt=$conn->prepare($query);
                                    $stmt->execute([":user_id"=>$_SESSION["sys_id"]]);
                                    $count = $stmt->rowCount();

                                    if($count > 0){
                                        
                                        while($row = $stmt->fetch(PDO::FETCH_OBJ)){ ?>

                                <option value="<?php echo $row->project_id; ?>"><?php echo $row->project_name; ?></option>

                                <?php
                                        }
                                        
                                    }else{
                                        ?>
                                <option value="project"><?php echo "No projects yet"; ?></option>
                                <?php
                                    }
                                ?>

                            </select>
                        </div>
                        <div class="batch report-select batch-selector" id="batches">

                        </div>
                        <div class="form-footer">
                            <button class="button" id="compile-report">Compile</button>
                            <span class="print" id="print-report"><i class="fas fa-print" aria-hidden="true"></i></span>
                            <span class="cancel-selector" id="cancel-report"><i class="fa fa-times" aria-hidden="true"></i></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <style>
            .report-form-fields,
            .form-footer {
                display: flex;
                align-items: center;
                justify-content: flex-start;
            }

            .report-form-fields div {
                padding: 0px 2px;
                width: 200px;
            }

            .batch-selector,
            .cancel-selector,
            .print {
                display: none;
            }

            .report-form-fields button {
                padding: 10px;
                color: #ffffff;
                background-color: rgb(63, 12, 48);
            }

            .cancel-selector,
            .print {
                /* width: 30px !important; */
                color: rgb(63, 12, 48);
                cursor: pointer;
                font-size: 25px;
                margin-left: 10px;
                font-weight: 100;
            }
            .report-heading{
                display: flex;
                justify-content: space-between;
                padding: 5px;
                align-items: center;
                margin-top: 10px;
            }
            .project-name, .batch-name{
                font-size: 25px;
            }
            .compiled-date{
                font-style: italic;
            }
        </style>
        <div class="report-container">
            <div class="report-header">
                <div class="report-heading">
                    <div class="project-name" id="project-name"></div>
                    <div class="batch-name" id="report-name"></div>
                    <div class="compiled-date" id="compiled-date">
                        <span class="date-title">Date Compiled: </span>
                        <span class="compile-date">2021-10-06</span>
                    </div>
                    
                </div><br>

            </div>
            <div class="report-section">

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            // changing between project selections
            $("#report-type").change(function(e) {
                e.preventDefault();
                var selected_project = $("#report-type option:selected").text();
                // $("#project-name").html(selected_project);
                $(".project-name").html(selected_project);
                $(".batch-name").html("");
                var report_type = $("#report-type").val();
                // console.log(report_type);
                $(".batch-selector").css("display", "block")
                $(".cancel-selector").css("display", "block")



                if (report_type == "null") {
                    $(".batch-selector").css("display", "none")

                    // $(".cancel-selector").css("display","none")
                    //                } else if (report_type == "all") {
                    //                    $(".batch-selector").css("display", "none")
                    // $(".cancel-selector").css("display","none")
                } else {
                    $.ajax({
                        type: "POST",
                        url: "php/server.php",
                        data: {
                            report_type: report_type
                        },
                        success: function(response) {
                            // console.log(response);
                            $("#batches").html(response);
                        }
                    });
                }
            });
            $("#batches").on("change","#batch",function (e) { 
                e.preventDefault();
                var selected_batch = $("#batch option:selected").text();
                $(".batch-name").html(selected_batch);
            });
            $(".cancel-selector").click(function(e) {
                location.reload();
            });

            //            compile for all projects
            $("#compile-report").click(function(e) {
                e.preventDefault();
                var compile_all_projects = "compile_all_projects";
                var project_id = $("#report-type").val();
                var batch_id = $("#batch").val();
                // console.log(batch_id);
                $(".print").css("display", "block");
                var compile_batch_report = "compile_batch_report";
                if (project_id == "all") {
                    $.ajax({
                        type: "POST",
                        url: "php/server.php",
                        data: {
                            compile_all_projects: compile_all_projects,
                            project_id: project_id
                        },
                        success: function(response) {
                            // console.log(response);
                            $(".report-section").html(response);
                        }
                    });
                } else if (project_id == "null") {
                    $(".report-section").html("Select project to start");
                } else {

                    if (batch_id == "null") {
                        $(".report-section").html("Select a batch to start");
                        //                    } else if (batch_id == "all") {

                    } else {
                        $.ajax({
                            type: "POST",
                            url: "php/server.php",
                            data: {
                                compile_batch_report: compile_batch_report,
                                batch_id: batch_id,
                                project_id: project_id
                            },
                            success: function(response) {
                                // console.log(response);
                                $(".report-section").html(response);

                            }
                        });
                    }

                }
            });
        });

    </script>
    <?php
    include_once("include/footer.php");
?>
