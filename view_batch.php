<?php
    session_start();   
    if(!isset($_SESSION["user_id"])){
        header("location:index.php");
    };

    // echo $_SESSION["sys_id"];
    include_once("include/header.php");
    include_once("include/sidebar.php");
    include_once("php/db.php");




    $batch_id = $_GET["batch_id"];
    $delete_status = 1;
    $total_revenue = 0;
    $total_cost = 0;
    $cost_table = "";
    $revenue_table = "";
    $cost_history = getBatchCosts($batch_id, $delete_status);
    // $costs = selectCosts($batch_id);
    if($cost_history == 0)
    {
        $cost_value = $cost_history;
        $cost_msg = "No costs recorded yet.";
    }
    else{
        $i = 1;
        
        foreach($cost_history as $row){
            $cost = $row->cost_value;

           $cost_table .= '<tr>
                        <td scope="row"> '.$i.' </td>
                        <td> '. $row->date_incurred.'</td>
                        <td>'.  $row->cost_name.'</td>
                        <td> '. $row->cost_value.'</td>';

                if($_SESSION["user_role"] == 1)
                {
                     $cost_table .=   '<td>
                            <i class="fas fa-edit"></i>
                            <i class="fas fa-trash"></i>
                        </td>';
                }
                        
            $cost_table .=        '</tr>
                    ';

                    $i++;  
                    $total_cost += $cost; 
        }
        
              
    }
    $cost_table.='<tr>
    <td colspan = "3"><b>Total</b></td>
    <td ><b>'.$total_cost.'</b></td>
    <td></td>
    </tr>';

    $revenue_history = getBatchRevenue($batch_id, $delete_status);
    if($revenue_history == 0)
    {
        $revenue_value = $revenue_history;
        $revenue_msg = "No revenue recorded yet.";
    }
    else{
        $a = 1;
        foreach($revenue_history as $row){
            // print_r($row->revenue_value);
            $revenue = $row->revenue_value;

            $revenue_table .= '<tr>
                        <td scope="row"> '.$a.' </td>
                        <td> '. $row->date_received.'</td>
                        <td>'.  $row->revenue_name.'</td>
                        <td> '. $row->revenue_value.'</td>';

                        if($_SESSION["user_role"] == 1)
                        {
                             $revenue_table .=   '<td>
                                    <i class="fas fa-edit"></i>
                                    <i class="fas fa-trash"></i>
                                </td>';
                        }
                                
                    $revenue_table .= '</tr>
                            ';


            $total_revenue += $revenue;
            $a++;
        }
        
    }
    $revenue_table.='<tr>
    <td colspan = "3"><b>Total</b></td>
    <td ><b>'.$total_revenue.'</b></td>
    <td></td>
    </tr>';
    $total_return = $total_revenue - $total_cost;
?>
<div class="content">
    <div class="content-header">
        <div class="page-heading">


            <h2 id="batch-name"></h2>
        </div>
        <div class="menu-button-wrapper">
            <div class="menu-button"></div>
        </div>


    </div>
    <div class="content-body">

        <div class="batch-overview statistics">

            <div class="card">
                <div class="card-title">
                    <div class="card-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="card-title-text">Total Revenue</div>
                </div>
                <div class="card-body">
                    <span id="project-count">$<?php echo $total_revenue ;?></span>
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
                    <span id="project-count">$<?php echo $total_cost ;?></span>
                </div>

            </div>

            <div class="card">
                <div class="card-title">
                    <div class="card-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="card-title-text">Profit/Loss</div>
                </div>
                <div class="card-body">
                    <span id="project-count"><?php 
                        if($total_return < 0 ){
                            $total_return = $total_return * -1;
                            echo "-$".$total_return;
                        }else{
                            echo "$".$total_return ;
                        }
                        ?></span>
                </div>

            </div>
        </div>
        <div class="tables">
            <!-- <div class="cost-table"> -->



                <div class="table-wrapper">
                    <table class="table">
                        <div class="table-heading">
                        <h3>Batch Costs</h3>
                            <div class="add-project-btn-wrapper">
                            <?php if($_SESSION["user_role"]==1)
                            {echo '<button class="button" id="cost-batch-button">Cost</button>';} ?>
                                
                            </div>
                            
                        </div>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Cost</th>
                                <th>Value</th>
                                <?php if($_SESSION["user_role"]==1)

                                {echo '<th>Action</th>';}?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo $cost_table; ?>
                        </tbody>
                    </table>
                </div>
            <!-- </div>
            <div class="revenue-table"> -->



                <div class="table-wrapper">
                    <table class="table">
                        <div class="table-heading">
                        <h3>Batch Revenue</h3>

                            <div class="add-project-btn-wrapper">
                               
                            </div>

                            <?php if($_SESSION["user_role"]==1)
                            {echo '<button class="button" id="receipt-batch-btn">Receipt</button>';}
                            ?>
                        </div>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Revenue</th>
                                <th>Value</th>
                            <?php if($_SESSION["user_role"]==1)

                                {echo '<th>Action</th>';}?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo $revenue_table; ?>
                        </tbody>
                    </table>
                </div>
            <!-- </div> -->
        </div>



</div>

</div>



      

<!-- cost modal -->

<div class="modal-wrapper cost-modal" id="cost-batch">

    <div class="form-wrapper">
        <div class="form">
            <div class="form-header">
                <h2>Cost Batch</h2>
                <div><span>Total Cost:</span> <span class="total-cost" id="total-cost"><i></i></span></div>
                <div class="modal-close">
                    <div class="close-button"></div>
                </div>
            </div>
            <hr>

            <div class="form-components form-body">
                <div class="form-element">
                    <label for="project-name">Cost Name:</label>
                    <?php
                        $batch_id = $_GET["batch_id"];
                        echo selectCosts($batch_id);
                    ?>
                </div>
                <div class="form-element">
                    <label for="project-date">Date Incurred:</label>
                    <input type="date" name="date-incurred" id="date-incurred">
                </div>
                <div class="form-element">
                    <label for="project-start-date">Invoice Number:</label>
                    <input type="text" name="invoice-number" id="invoice-number">
                </div>

                <div class="form-element">
                    <label for="project-date">Unit Cost:</label>
                    <input type="number" name="unit-cost" id="unit-cost">
                </div>
                <div class="form-element">
                    <label for="unit-count">Unit Count:</label>
                    <input type="number" name="unit-count" id="unit-count">
                </div>
                <div class="form-element">
                    <label for="description">Description:</label>
                    <input type="text" name="description" id="description">
                </div>
                <div class="form-element">
                    <label for="supplier-name">Supplier Details:</label>
                    <input type="text" name="supplier-name" id="supplier-details">
                </div>
                
            </div>
            <div class="form-footer">
                <button type="submit" class="button btn-save" id="cost-batch">Cost Batch</button>
            </div>
        </div>
    </div>
</div>
<!-- cost batch modal ----- receipt batch modal -->


<div class="modal-wrapper receipt-batch receipt-modal" id="receipt-batch-modal">

    <div class="form-wrapper">
        <div class="form">
            <div class="form-header">
                <h2>Receipt Batch</h2>
                <div><span>Receipt Total:</span> <span class="total-cost-receipt" id="total-cost-receipt"><i></i></span></div>
                <div class="modal-close">
                    <div class="close-button"></div>
                </div>
            </div>
            <hr>

            <div class="form-components form-body">
                <div class="form-element">
                    <label for="project-name">Revenue Name:</label>
                    <?php
                        $batch_id = $_GET["batch_id"];
                        echo selectRevenue($batch_id);
                    ?>
                </div>
                <!-- <br> -->
                <div class="form-element">
                    <label for="project-start-date">Receipt Number:</label>
                    <input type="text" name="invoice-number" id="receipt-number">
                </div>
             
                <div class="form-element">
                    <label for="unit-cost-receipt">Unit Count:</label>
                    <input type="number" name="unit-cost-receipt" id="unit-count-receipt">
                </div>
                <div class="form-element">
                    <label for="unit-cost-receipt">Unit Cost:</label>
                    <input type="number" name="unit-cost-receipt" id="unit-cost-receipt" >
                </div>

                <div class="form-element">
                    <label for="receipt-description">Description:</label>
                    <input type="text" name="description" id="receipt-description">
                </div>

                <div class="form-element">
                    <label for="project-date">Date Received:</label>
                    <input type="date" name="date-received" id="date-received">
                </div>
                <div class="form-element">
                    <label for="cutomer-details">Customer Details:</label>
                    <input type="text" name="customer-details" id="customer-details">
                </div>
            </div>
            <div class="form-footer">
                <button type="submit" class="button btn-save" id="receipt-batch">Receipt Batch</button>
            </div>
        </div>
    </div>
</div>

<script>
            // setting custom heading to batch selected
            $(document).ready(function() {
                url = window.location.href.split("?batch_id=");
                var batch_id = url[1];

                $.ajax({
                    type: "get",
                    url: "php/server.php",
                    data: {
                        batch_id: batch_id
                    },

                    success: function(response) {
                        $("#batch-name").html(response)
                    }
                });
                $("#cost-batch-button").click(function(e){
                    e.preventDefault();
                    $(".cost-modal").css("display","block");
                });
                var total_cost = 0;
                var unit_count = 0;
                var unit_cost = 0;
                $(".total-cost i").html("$"+total_cost);

                $("#unit-count").keyup(function(e){
                     unit_count = $("#unit-count").val();
                     unit_cost = $("#unit-cost").val();

                    
                    total_cost = unit_cost*unit_count;
                    $(".total-cost").html("$"+total_cost);
                });

                $("#unit-cost").keyup(function(e){
                     unit_count = $("#unit-count").val();
                     unit_cost = $("#unit-cost").val();

                    
                    total_cost = unit_cost*unit_count;
                    $(".total-cost").html("$"+total_cost);
                });

                // so ashamed of duplication this item above

                $("#unit-count-receipt").keyup(function(e){
                     unit_count = $("#unit-count-receipt").val();
                     unit_cost = $("#unit-cost-receipt").val();

                    
                    total_cost = unit_cost*unit_count;
                    $(".total-cost-receipt").html("$"+total_cost);
                });

                $("#unit-cost-receipt").keyup(function(e){
                     unit_count = $("#unit-count-receipt").val();
                     unit_cost = $("#unit-cost-receipt").val();

                    
                    total_cost = unit_cost*unit_count;
                    $(".total-cost-receipt").html("$"+total_cost);
                });


                // cost batch

                $("#cost-batch").click(function(e){
                    e.preventDefault();
                    var cost_id = $("#select-cost").val();
                    var invoice_number = $("#invoice-number").val();
                    var unit_cost = $("#unit-cost").val();
                    var unit_count = $("#unit-count").val();
                    var description = $("#description").val();
                    var supplier_details = $("#supplier-details").val();
                    var date_incurred = $("#date-incurred").val();
                    var cost_batch = "cost_batch";

                    description = description+" ($"+unit_cost+"x"+unit_count+" units)";
                    var error = "";
                    if(cost_id == "" || invoice_number == "" || unit_cost == "" || unit_count == "" || description == "" || supplier_details == "" || date_incurred == ""){
                        error = "Please fill in all the fields!";
                        $(".error").html(error);
                    }else{
                        var total_cost = unit_cost * unit_count;
                        $.ajax({
                            type: "POST",
                            url: "php/server.php",
                            data: {
                                cost_batch:cost_batch,
                                batch_id:batch_id,
                                cost_id:cost_id,
                                invoice_number:invoice_number,
                                total_cost: total_cost,
                                description:description,
                                supplier_details:supplier_details,
                                date_incurred:date_incurred,
                            },
                            success: function (response) {
                                if(response == "Success"){
                                    $(".cost-modal").css("display","none");
                                    location.reload();
                                }else{
                                    error = "Failed. Consult support!";
                                    $(".error").html(error);
                                }
                            }
                        });
                    }
                });

                // receipt batch modal call
                $("#receipt-batch-btn").click(function(e){
                    e.preventDefault();
                    $(".receipt-modal").css("display","block");
                });

              

                // receipt batch
                $(".receipt-batch").on("click","#receipt-batch",function(e){
                    e.preventDefault();
                    var revenue_id = $("#select-revenue").val();
                    var receipt_number = $("#receipt-number").val();
                    var unit_count_receipt = $("#unit-count-receipt").val();
                    var unit_cost_receipt = $("#unit-cost-receipt").val();
                    var receipt_description = $("#receipt-description").val();
                    var customer_details = $("#customer-details").val();
                    var date_received = $("#date-received").val();
                    var receipt_batch = "receipt_batch";

                    description = description+" ($"+unit_cost_receipt+"x"+unit_count_receipt+" units)";
                    var error = "";
                    if(revenue_id == "" || receipt_number == "" || unit_cost_receipt == "" || unit_count_receipt == "" || receipt_description == "" || customer_details == "" || date_received == ""){
                        error = "Please fill in all the fields!";
                        $(".error").html(error);
                    }else{
                        var total_receipt = unit_cost_receipt * unit_count_receipt;
                        $.ajax({
                            type: "POST",
                            url: "php/server.php",
                            data: {
                                receipt_batch:receipt_batch,
                                batch_id:batch_id,
                                revenue_id:revenue_id,
                                receipt_number:receipt_number,
                                total_receipt: total_receipt,
                                receipt_description:receipt_description,
                                customer_details:customer_details,
                                date_received:date_received,
                            },
                            success: function (response) {
                                if(response == "Success"){
                                    $(".receipt-modal").css("display","none");
                                    location.reload();
                                }else{
                                    error = "Failed. Consult support!";
                                    $(".error").html(error);
                                }
                            }
                        });
                    }
                });
                // all code except functions must go above
            });

        </script>
<?php
    function getBatchCosts($batch_id, $delete_status){
        global $conn;

        // $query = ("SELECT * FROM incurred_costs WHERE batch_id = :batch_id AND delete_status = :delete_status");
        $query = ("select a.cost_description, a.cost_value, a.date_incurred, b.cost_name FROM incurred_costs AS a INNER JOIN cost_definition as b ON b.defined_cost_id = a.defined_cost_id AND a.batch_id = :batch_id AND a.delete_status = :delete_status");
        $stmt = $conn->prepare($query);
        $stmt->execute([":batch_id"=>$batch_id, ":delete_status"=>$delete_status]);
        $count = $stmt->rowCount();

        if($count > 0){
            $rows = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $rows;
        }
        else{
            $empty = 0;
            return $empty;
        }
    }

    function getBatchRevenue($batch_id, $delete_status){
        global $conn;

        $query = ("select a.revenue_description, a.revenue_value, a.date_received, b.revenue_name FROM received_revenue AS a INNER JOIN revenue_definition as b ON b.defined_revenue_id = a.defined_revenue_id AND a.batch_id = :batch_id AND a.delete_status = :delete_status");
        $stmt = $conn->prepare($query);
        $stmt->execute([":batch_id"=>$batch_id, ":delete_status"=>$delete_status]);
        $count = $stmt->rowCount();

        if($count > 0){
            $rows = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $rows;
        }
        else{
            $empty = 0;
            return $empty;
        }
    }

    function selectCosts($batch_id){
        global $conn;
        $delete_status = 1;
        $stmt = $conn->prepare("SELECT project_id FROM project_batch WHERE batch_id = :batch_id");
        $stmt->execute([':batch_id'=>$batch_id]);
        $select = "<select name='select-cost' id='select-cost'>";
        while($rows = $stmt->fetch(PDO::FETCH_OBJ)){
            $project_id = $rows->project_id;
            $stmt = $conn->prepare("SELECT cost_name, defined_cost_id FROM cost_definition WHERE project_id = :project_id AND delete_status = :delete_status");
            $stmt->execute([":project_id"=>$project_id, ":delete_status"=>$delete_status]);

            $select .= "<option>Select cost</option>";
            $count = $stmt->rowCount();
            if($count > 0){
               
                $rows = $stmt->fetchAll(PDO::FETCH_OBJ);
                foreach ($rows as $row) {
                    $select .= "<option value='".$row->defined_cost_id."'>".$row->cost_name."</option>";
                };
                // $select .= "</select>";
                

            }else{
                $select .= "<option>No cost defined</option>";
            }

        }
        $select .= "</select>";
        return $select;
    }

    function selectRevenue($batch_id){
        global $conn;
        $delete_status = 1;
        $stmt = $conn->prepare("SELECT project_id FROM project_batch WHERE batch_id = :batch_id");
        $stmt->execute([':batch_id'=>$batch_id]);
        $select = "<select name='select-revenue' id='select-revenue'>";
        while($rows = $stmt->fetch(PDO::FETCH_OBJ)){
            $project_id = $rows->project_id;
            $stmt = $conn->prepare("SELECT revenue_name, defined_revenue_id FROM revenue_definition WHERE project_id = :project_id AND delete_status = :delete_status");
            $stmt->execute([":project_id"=>$project_id, ":delete_status"=>$delete_status]);

            $select .= "<option>Select revenue</option>";
            $count = $stmt->rowCount();
            if($count > 0){
               
                $rows = $stmt->fetchAll(PDO::FETCH_OBJ);
                foreach ($rows as $row) {
                    $select .= "<option value='".$row->defined_revenue_id."'>".$row->revenue_name."</option>";
                };
                $select .= "</select>";
                

            }else{
                $select .= "<option>No revenue defined</option>";
            }

        }
        $select .="</select>";
        return $select;
    }
    include_once("include/footer.php");
?>
