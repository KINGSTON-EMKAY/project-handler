<?php
    session_start();   
    if(!isset($_SESSION["user_id"])){
        header("location:index.php");
    };  
    include_once("php/db.php");
    include_once("include/header.php");
    include_once("include/sidebar.php");
?>
<div class="content">
    <div class="content-header">
        <div class="page-heading">
            <h2>Dashboard</h2>
        </div>
        <div class="menu-button-wrapper" id="menu-button">
            <div class="menu-button"></div>
        </div>


    </div>
    <div class="content-body">

        <div class="statistics">



            <div class="card">
                <div class="card-title">
                    <div class="card-icon">
                        <i class="far fa-copy"></i>
                    </div>
                    <div class="card-title-text">Total Projects</div>
                </div>
                <div class="card-body">
                    <?php 
                        $delete_status = 1;
                        $total_revenue = 0;
                        $total_cost = 0;
                        $total_cost1 = 0;
                        $i = 1;
                        $b_totals = 0;
                        $user_id = $_SESSION["sys_id"];
                        $query = ("SELECT * FROM projects WHERE sys_id = :user_id AND delete_status = :delete_status ORDER BY date_created ASC");
                        $stmt = $conn->prepare($query);
                        $stmt->execute([":user_id"=>$user_id, ":delete_status"=>$delete_status]);
                        $project_count = $stmt->rowCount();
                        $project_id = 0;
                        $compilation = "<tr>";
                            while($rows = $stmt->fetch(PDO::FETCH_OBJ)){
                                $project_id = $rows->project_id;
                                $project_name = $rows->project_name;
                                $compilation .= "<td>".$i."</td><td>".$rows->date_created."</td><td>".$project_name."</td>";
                                $i++;
                            $query = ("SELECT * FROM project_batch WHERE project_id = :project_id AND delete_status = :delete_status");
                            $stmt1 = $conn->prepare($query);
                            $stmt1->execute([":project_id"=>$project_id, ":delete_status"=>$delete_status]);

                            $batch_count = $stmt1->rowCount();
                            // var_dump($batch_count);
                            $compilation .= "<td>".$batch_count."</td></tr>";
                            if($batch_count > 0){
                                $batch_rows = $stmt1->fetchAll(PDO::FETCH_OBJ);
                                foreach($batch_rows as $batch_row){
                                    $batch_id = $batch_row->batch_id;
                                    // $batches_total = getBatchesTotals($batch_id, $project_id);
                                    // $b_totals += $batches_total;
                                    // echo $b_totals."<br>"; 
                                    // compiling total revenue from batches final step
                                    $query = ("select a.revenue_description, a.revenue_value, a.date_received, b.revenue_name FROM received_revenue AS a INNER JOIN revenue_definition as b ON b.defined_revenue_id = a.defined_revenue_id AND a.batch_id = :batch_id AND a.delete_status = :delete_status ORDER BY a.date_received ASC");
                                    $stmt2 = $conn->prepare($query);
                                    $stmt2->execute([":batch_id"=>$batch_id, ":delete_status"=>$delete_status]);
                                    $rev_rows = $stmt2->fetchAll(PDO::FETCH_OBJ);

                                    foreach($rev_rows as $rev_row){
                                        $revenue =  $rev_row->revenue_value;
                                        $total_revenue += $revenue;
                                    }

                                    // compiling total cost from batches final step
                                    $query = ("select a.cost_description, a.cost_value, a.date_incurred, b.cost_name FROM incurred_costs AS a INNER JOIN cost_definition as b ON b.defined_cost_id = a.defined_cost_id AND a.batch_id = :batch_id AND b.delete_status = :delete_status ORDER BY a.date_incurred ASC");
                                    $stmt3 = $conn->prepare($query);
                                    $stmt3->execute([":batch_id"=>$batch_id, ":delete_status"=>$delete_status]);
                                    $cost_rows = $stmt3->fetchAll(PDO::FETCH_OBJ);

                                    foreach($cost_rows as $cost_row){
                                        $cost =  $cost_row->cost_value;

                                        $total_cost += $cost;
                                    }
                                }      
                                                          
                            }    
                        }    
                                           
                        $gains = $total_revenue - $total_cost;
                    ?>
                    <span id="project-count"><?php echo $project_count; ?></span>
                </div>
                <hr>
                <div class="card-footer">
                    <button id="add-project" class="button">Add Project</button>
                </div>
            </div>

            <div class="card">
                <div class="card-title">
                    <div class="card-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="card-title-text">Total Revenue</div>
                </div>
                <div class="card-body">
                    <span id="project-count"><?php echo '$'.$total_revenue; ?></span>
                </div>
                <hr>
                <div class="card-footer">
                    <button id="add-project" class="button">Reports</button>
                </div>
            </div>




            <div class="card">
                <div class="card-title">
                    <div class="card-icon">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div class="card-title-text">Total Cost</div>
                </div>
                <div class="card-body">
                    <span id="project-count"><?php echo '$'.$total_cost; ?></span>
                </div>
                <hr>
                <div class="card-footer">
                    <button id="add-project" class="button">Reports</button>
                </div>
            </div>

            <div class="card">
                <div class="card-title">
                    <div class="card-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="card-title-text">Gains</div>
                </div>
                <div class="card-body">
                    <span id="project-count"><?php echo '$'.$gains; ?></span>
                </div>
                <hr>
                <div class="card-footer">
                    <button id="add-project" class="button">Reports</button>
                </div>
            </div>


        </div>
<br>

        <div class="detailed_stats">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <th></th>
                        <th>Date</th>
                        <th>Project Name</th>
                        <th>Batch Count</th>
                        <!-- <th>Revenue</th>
                        <th>Cost</th>
                        <th>Return</th> -->
                    </thead>
                    <tbody>
                        
                        <?php echo $compilation; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>





    <?php
    function getBatchesTotals($batch_id, $project_id){
        global $conn;
        // var_dump ($batch_id, $project_id);
        $delete_status = 1; $project_revenue_total = 0;
        $query = ("SELECT a.revenue_value FROM received_revenue AS a INNER JOIN project_batch AS b ON a.batch_id = b.batch_id AND b.batch_id = :batch_id AND  b.project_id = :project_id  AND b.delete_status = :delete_status");
        $stmt = $conn->prepare($query);
        $stmt->execute([":batch_id"=>$batch_id, ":project_id"=>$project_id, ":delete_status"=>$delete_status]);

        $count = $stmt->rowCount();
        if($count > 0){
            // $rows = $stmt->fetchAll(PDO::FETCH_OBJ);
           
            while($rows = $stmt->fetch(PDO::FETCH_OBJ)){
                $revenue = $rows->revenue_value;
                $project_revenue_total += $revenue; 
            }
            return $project_revenue_total;
        }else{
            return $project_revenue_total;
        }
    }
    include_once("include/footer.php");
?>
