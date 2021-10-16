<?php
    session_start();
    include_once("db.php");

// login
if ( isset( $_POST["login"] ) ) {
    $email = $_POST["email"];
    $password = $_POST["password"];
//    $password = md5($_POST["password"]);
    // echo $password, $email;
    $query = ( "SELECT * FROM system_users WHERE email = :email AND password = :password" );
    $stmt = $conn->prepare( $query );
    $stmt->execute( [":email"=>$email, ":password"=>$password] );
    $count = $stmt->rowCount();

    if ( $count > 0 ) {

        $rows = $stmt->fetch( PDO::FETCH_OBJ );
        $_SESSION["firstname"] = $rows->firstname;
        $_SESSION["lastname"] = $rows->lastname;
        $_SESSION["email"] = $rows->email;
        $_SESSION["user_id"] = $rows->user_id;
        $_SESSION["sys_id"] = $rows->sys_id;
        $_SESSION["user_role"] = $rows->user_role;

        echo "Success";
    } else {

        echo "Null";
    }
}
//register new admin user

if(isset($_POST["add_admin_user"])){
   
    $firstname = ucfirst($_POST["firstname"]);
    $lastname = ucfirst($_POST["lastname"]);
    $email = strtolower($_POST["email"]);
    $password = $_POST["password"];
//        $password = md5($_POST["password"]);

    $sys_id = generateSysId();
    
//    echo $password;
    $stmt = $conn->prepare("SELECT * FROM system_users WHERE email = :email");
    $stmt->execute([":email"=>$email]);
    $count = $stmt->rowCount();
    if($count > 0){
        echo "Exists";
    }else{
        $stmt = $conn->prepare("INSERT INTO system_users (sys_id, firstname, lastname, email, password) VALUES(:sys_id, :firstname, :lastname, :email, :password)");
        $stmt->execute([":sys_id"=>$sys_id, ":firstname"=>$firstname, ":lastname"=>$lastname, ":email"=>$email, ":password"=>$password]);
        
        echo "Success";
    }
    
}
//function generates random numbers to use as sys_ids
function generateSysId(){
    $sys_id = rand(1000, 1000000);
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM system_users WHERE sys_id = :sys_id");
    $stmt->execute([":sys_id"=>$sys_id]);
    $count = $stmt->rowCount();
    if($count > 0){
        generateSysId();
    }else{return $sys_id;}
}
// add user under admin account
if(isset($_POST["add_user"])){
    $sys_id = $_SESSION["sys_id"];
    $firstname = ucfirst($_POST["firstname"]);
    $lastname = ucfirst($_POST["lastname"]);
    $email = ($_POST["email"]);
    $password = $_POST["password"];
    $user_role = 2;
    $query = ("SELECT * FROM system_users WHERE email = :email");
    $stmt = $conn->prepare($query);
    $stmt->execute([":email"=>$email]);
    $count = $stmt->rowCount();

    if($count > 0){
        echo "Exists";
    }else{
        $query = ("INSERT INTO system_users (sys_id, firstname, lastname, email, password, user_role) VALUES(:sys_id, :firstname, :lastname, :email, :password, :user_role)");
        $stmt = $conn->prepare($query);
        $stmt->execute([":sys_id"=>$sys_id, ":firstname"=>$firstname, ":lastname"=>$lastname, ":email"=>$email, ":password"=>$password, ":user_role"=>$user_role]);
        echo "Success";
    }
}


//password change
if(isset($_POST["change_password"])){
    $password = $_POST["new_password"];
    $user_id = $_POST["user_id"];
    $stmt = $conn->prepare("UPDATE system_users SET password = :password WHERE user_id = :user_id");
    if($stmt->execute([":password"=>$password, ":user_id"=>$user_id]) == true){
        echo "Success";
    }
    else{
        echo "Failed";
    }
    
}



// define project

if ( isset( $_POST["set_project_name"] ) ) {
    $project_name = $_POST["project_name"];
    $project_description = $_POST["project_description"];
    $user_id = $_SESSION["user_id"];
    $sys_id = $_SESSION["sys_id"];

    $query = ( "SELECT * FROM projects WHERE user_id = :user_id AND project_name = :project_name" );
    $stmt = $conn->prepare( $query );
    $stmt->execute( [":user_id"=>$user_id, "project_name"=>$project_name] );
    $count = $stmt->rowCount();
    // echo $count;
    if ( $count > 0 ) {
        $rows = $stmt->fetch(PDO::FETCH_OBJ);
        
        if($rows->delete_status == 2){
            // var_dump($rows->delete_status);
            echo "Deleted";
        }else{
            // var_dump($rows->delete_status);
            echo "Exists";
        }
        // echo "Failed";

    } else {

        $query = ( "INSERT INTO projects (`user_id`,`sys_id`,`project_name`, `project_description`) VALUES(:user_id, :sys_id, :project_name, :project_description)" );
        $stmt = $conn->prepare( $query );
        $stmt->execute( [":user_id"=>$user_id,":sys_id"=>$sys_id, ":project_name"=>$project_name, ":project_description"=>$project_description] );

        echo "Success";
    }

}


// get user projects   get_projects

if ( isset( $_POST["get_projects"] ) ) {
    // $user_id = $_SESSION["user_id"];
    $user_id = $_SESSION["sys_id"];
    // $user_id = $_POST["user_id"];


    $map_value = 2;
    // $map_value = $_POST["map_value"];
    $del_status = 1;

    // echo $user_id;
    $query = ( "SELECT * FROM projects WHERE sys_id = :user_id AND delete_status = :delete_status" );
    $stmt = $conn->prepare( $query );
    $stmt->execute( [":user_id"=>$user_id, ":delete_status"=>$del_status] );
    $count = $stmt->rowCount();

    echo $user_id;
    // $data = ' <table class="styled-table table batches-table">
    // <thead>
    //     <tr>
    //         <th class="count">#</th>
    //         <th>Project</th>
    //         <th>Description</th>
    //         <th>Action</th>
            
    //     </tr>
    // </thead>
    // <tbody>';
    // $i = 1;
    // if ( $count > 0 ) {
    //     $rows = $stmt->fetchAll( PDO::FETCH_OBJ );
    //     foreach ( $rows as $row ) {
    //         $project_id = $row->project_id;
    //         $project_name = $row->project_name;
    //         $project_description = $row->project_description;

    //         $data .=
    //                     '<tr'.$user_id.'>
    //                     <td class="count">'.$i.'</td>
    //                     <td>'.$project_name.'</td>
    //                     <td>'.$project_description.'</td>';
    //         if ( $map_value == 2 ) {
    //             $data .= ' <td><a href="view_project.php?p_id='.$project_id.'" class="view_project btn btn-info btn-sm" >View</a>
    //             <a class="view_project btn btn-info btn-sm"  data-id="'.$project_id.'" id="delete-selected-project" >Delete</a>
    //             ';
    //         } else {
    //             $data .= '<a class="add_batch btn btn-info btn-sm"  data-id="'.$project_id.'">Batches</a>';
    //         };

    //         $data .='</td> </tr>'
    //         ;
    //         $i++;
    //     }
    //     $data .='  </tbody>
    //     </table>';

    // } else {
    //     $data .= '<p class="lead">
    //             You have no projects setup yet.
    //         </p>';
    // }
    // echo $data;
}



//saves batches of a project
if(isset($_POST["define_batch"])){
    
    $project_id = $_POST["project_id"];
    $batch_name = $_POST["batch_name"];
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];
    $delete_status = 1;

    $query = ("SELECT * FROM `project_batch` WHERE `project_id` = :project_id AND batch_name = :batch_name AND delete_status = :delete_status");
    
    $stmt = $conn->prepare($query);
    $stmt->execute([":project_id"=>$project_id, ":batch_name"=>$batch_name, ":delete_status"=>$delete_status]);
    $count = $stmt->rowCount();
    
    
    if( $count > 0){
        
        echo "Failed";
        
    }
    else{
        $query = ("INSERT INTO `project_batch` ( `project_id`, `batch_name`, `start_date`, `end_date` ) VALUES (:project_id, :batch_name, :start_date, :end_date )");
        $stmt = $conn->prepare($query);
   
        $stmt->execute([":project_id"=>$project_id, ":batch_name"=>$batch_name, ":start_date"=>$start_date, ":end_date"=>$end_date]);
        echo "Success";
    }
   
}

// defines project revenue
if(isset($_POST["define_revenue"])){
    $project_id = $_POST["project_id"];
    $revenue_name = $_POST["revenue_name"];

    $query = ("SELECT * FROM revenue_definition WHERE project_id = :project_id AND revenue_name = :revenue_name");
    $stmt = $conn->prepare($query);
    $stmt->execute([":project_id"=>$project_id, ":revenue_name"=>$revenue_name]);
    $count = $stmt->rowCount();
    if($count > 0){
        echo "Failed";
    }else{
        $query = ("INSERT INTO revenue_definition (project_id, revenue_name) VALUES(:project_id, :revenue_name)");
        $stmt = $conn->prepare($query);
        $stmt->execute([":project_id"=>$project_id, ":revenue_name"=>$revenue_name]);

        echo "Success";
    }
}

// defines project cost
if(isset($_POST["define_cost"])){
    $project_id = $_POST["project_id"];
    $cost_name = $_POST["cost_name"];

    $query = ("SELECT * FROM cost_definition WHERE project_id = :project_id AND cost_name = :cost_name");
    $stmt = $conn->prepare($query);
    $stmt->execute([":project_id"=>$project_id, ":cost_name"=>$cost_name]);
    $count = $stmt->rowCount();
    if($count > 0){
        echo "Failed";
    }else{
        $query = ("INSERT INTO cost_definition (project_id, cost_name) VALUES(:project_id, :cost_name)");
        $stmt = $conn->prepare($query);
        $stmt->execute([":project_id"=>$project_id, ":cost_name"=>$cost_name]);

        echo "Success";
    }
}


// delete defined cost

if(isset($_POST["delete_defined_cost"])){
    $defined_cost_id = $_POST["defined_cost_id"];
    $delete_status = 0;
    $query = ("UPDATE `cost_definition` SET `delete_status` = :delete_status WHERE `cost_definition`.`defined_cost_id` = :defined_cost_id");
    $stmt = $conn->prepare($query);
    $stmt->execute([":delete_status"=>$delete_status,":defined_cost_id"=>$defined_cost_id]);

}

// update cost name
if(isset($_POST["update_defined_cost"])){
    $defined_cost_id = $_POST["defined_cost_id"];
    $defined_cost_name = $_POST["defined_cost_name"];
    $project_id = $_POST["project_id"];

    $query = ("SELECT * FROM cost_definition WHERE project_id = :project_id AND cost_name = :cost_name");
    $stmt = $conn->prepare($query);
    $stmt->execute([":project_id"=>$project_id, ":cost_name"=>$defined_cost_name]);
    $count = $stmt->rowCount();
    if($count > 0){
        echo "Failed";
    }else{

    $query = ("UPDATE `cost_definition` SET `cost_name` = :defined_cost_name WHERE `cost_definition`.`defined_cost_id` = :defined_cost_id;");
    $stmt = $conn->prepare($query);
    $stmt->execute([":defined_cost_name"=>$defined_cost_name, ":defined_cost_id"=>$defined_cost_id]);
    echo "Success";
    }
}

// cost batch
if(isset($_POST["cost_batch"])){
    $batch_id = $_POST["batch_id"];
    $cost_id = $_POST["cost_id"];
    $invoice_number = $_POST["invoice_number"];
    $total_cost = $_POST["total_cost"];
    $description = $_POST["description"];
    $supplier_details = $_POST["supplier_details"];
    $date_incurred = $_POST["date_incurred"];

    $query = ("INSERT INTO `incurred_costs`(`supplier_details`, `batch_id`, `defined_cost_id`, `cost_description`, `cost_value`, `invoice_number`, `date_incurred`) VALUES(:supplier_details,:batch_id,:cost_id,:description,:total_cost,:invoice_number,:date_incurred)");
    $stmt = $conn->prepare($query);
    $stmt->execute([":supplier_details"=>$supplier_details, ":batch_id"=>$batch_id, ":cost_id"=>$cost_id, ":description"=>$description, ":total_cost"=>$total_cost,":invoice_number"=>$invoice_number, ":date_incurred"=>$date_incurred]);

    echo "Success";
}

// receipt batch
if(isset($_POST["receipt_batch"])){


    // variable names same as that of cost batch //// copy paste thing hahaha
    $batch_id = $_POST["batch_id"];
    $cost_id = $_POST["revenue_id"];
    $invoice_number = $_POST["receipt_number"];
    $total_cost = $_POST["total_receipt"];
    $description = $_POST["receipt_description"];
    $supplier_details = $_POST["customer_details"];
    $date_incurred = $_POST["date_received"];

    $query = ("INSERT INTO `received_revenue`(`client_details`, `batch_id`, `defined_revenue_id`, `revenue_description`, `revenue_value`, `receipt_number`, `date_received`) VALUES(:supplier_details,:batch_id,:cost_id,:description,:total_cost,:invoice_number,:date_incurred)");
    $stmt = $conn->prepare($query);
    $stmt->execute([":supplier_details"=>$supplier_details, ":batch_id"=>$batch_id, ":cost_id"=>$cost_id, ":description"=>$description, ":total_cost"=>$total_cost,":invoice_number"=>$invoice_number, ":date_incurred"=>$date_incurred]);

    echo "Success";
}



// get project name for heading print
if(isset($_GET["project_id"])){
    $project_id = $_GET["project_id"];
    $query = ("SELECT project_name FROM projects WHERE project_id = :project_id");
    $stmt = $conn->prepare($query);
    $stmt->execute([":project_id"=>$project_id]);

    $result = $stmt->fetch(PDO::FETCH_OBJ);
    $project_name = $result->project_name;

    echo ucfirst($project_name);
}

// get batch name for heading print
if(isset($_GET["batch_id"])){
    $batch_id = $_GET["batch_id"];
    $query = ("SELECT batch_name FROM project_batch WHERE batch_id = :batch_id");
    $stmt = $conn->prepare($query);
    $stmt->execute([":batch_id"=>$batch_id]);

    $result = $stmt->fetch(PDO::FETCH_OBJ);
    $batch_name = $result->batch_name;

    echo ucfirst($batch_name);
}


// dynamic adding selector option for batches
if(isset($_POST["report_type"])){
    $project_id = $_POST["report_type"];

    $query = ("SELECT * FROM project_batch WHERE project_id = :project_id");
    $stmt = $conn->prepare($query);
    $stmt->execute([":project_id"=>$project_id]);
    $count = $stmt->rowCount();
    $select = '<select name="batches" id="batch">
                    <option value="null">Select Batch</option>
                    <!--<option value="all">All batches</option>-->';
    if($count > 0){
            while($row = $stmt->fetch(PDO::FETCH_OBJ)){
                $select .= '<option value="'.$row->batch_id.'">'.$row->batch_name.'</option>';
            }
    }else{
            $select .= '<option value="null">No batches yet</option>';
    }
    $select .='</select>';
    echo $select;
}








// all projects compilation
// step 1
    // select all project IDs, project names, date_created for specified user_id and delete_status 1
// step 2
    // use the project IDs to get batch IDs and batch_names
// step 3 
    // use the batch IDs to fetch total costings and revenue per batch and delete_status 1








// compile_all_projects
if(isset($_POST["compile_all_projects"])){
    $user_id = $_SESSION["sys_id"];
    // $user_id = $_SESSION["user_id"];
    $delete_status = 1;
    // $compile_revenue = compileBatchRevenue($user_id);
    $query = ("SELECT * FROM projects WHERE sys_id = :user_id AND delete_status = :delete_status");
    $stmt  = $conn->prepare($query);
    $stmt->execute([":user_id"=>$user_id, ":delete_status"=>$delete_status]);
    $count = $stmt->rowCount();
    $projects_report = '<table>';
    if($count > 0){

        while($row = $stmt->fetch(PDO::FETCH_OBJ)){
            $batch_detail = compileBatchDetail($row->project_id);
            $projects_report .='<tr><td>'.$row->project_name.'</td><td>'.$row->project_description.'</td></tr>
            <tr><td></td><td>'.$batch_detail.'</td></tr>';
            
        } 
    }else{
        $projects_report .='<tr><td>No batches yet</td></tr>';
    }
    $projects_report .= '<tr><td></td></tr></table>'; 

    echo $projects_report;
}


//compile batch report

if(isset($_POST["compile_batch_report"])){
    $batch_id = $_POST["batch_id"];
    $delete_status = 1;
    $costs = getBatchCosts($batch_id, $delete_status);
    $revenue = getBatchRevenue($batch_id, $delete_status);
    // var_dump($costs);
    $i = 1;
    $total_cost = 0;
    $total_revenue = 0;
    $compiled_table='<hr><table><thead>
    <th></th>
    <th>Date</th>
    <th>Details</th>
    <th>Description</th>
    <th>Amount</th>
    <th>Totals</th>
    </thead>';

    if($revenue == 0){
        $compiled_table.= '<tr><td colspan="5">No revenue data</td><td>0</td></tr>';
    }else{
        $compiled_table .= '<tr><td colspan = "6"><b>Revenue</b> <i class="fas fa-arrow-down"></i></td></tr>';
    foreach($revenue as $row){
        $revenue_value = $row->revenue_value;
        $compiled_table .='<tr>
            <td>'.$i.'</td>
            <td>'.$row->date_received.'</td>
            <td>'.$row->revenue_name.'</td>
            <td>'.$row->revenue_description.'</td>
            <td>'.$row->revenue_value.'</td><td></td>
        </tr>';
    $i++;
    $total_revenue +=$revenue_value;
}
$compiled_table .= '<tr>
    <td colspan = "5"></td>
        <td class="totals"><b>'.$total_revenue.'</b></td>
    </tr><br>';}

//var_dump($costs);
    if($costs == 0){
        $compiled_table.= '<tr><td colspan="5">No cost data</td><td>0</td></tr>';
    }else{
        $compiled_table .= '<tr><td colspan = "6"><b>Costs</b> <i class="fas fa-arrow-down"></i></td></tr>';
        foreach($costs as $row){
            $cost_value = $row->cost_value;
            $compiled_table .='<tr>
                <td>'.$i.'</td>
                <td>'.$row->date_incurred.'</td>
                <td>'.$row->cost_name.'</td>
                <td>'.$row->cost_description.'</td>
                <td>'.$row->cost_value.'</td><td></td>
            </tr>';
        $i++;
        $total_cost +=$cost_value;
    }
   
    

    if($total_cost > 0){
        $compiled_table .= '<tr>
        <td colspan = "5"></td>
            <td class="totals red-total"><b>('.$total_cost.')</b></td>
        </tr>';
    }else{
        $compiled_table .= '<tr>
        <td colspan = "5"><b>Total</b></td>
            <td class="totals"><b>'.$total_cost.'</b></td>
        </tr>';
    }
}

    $difference = $total_revenue - $total_cost;
    $compiled_table .= '<tr>

        <td colspan="5"><b>Profit/Loss</b></td>';

        if($difference < 0){
            $difference = $difference * -1;
            $compiled_table .= '<td class="totals red-total"><b>('.$difference.')</b></td>';
        }else{
            $compiled_table .= '<td class="totals "><b>'.$difference.'</b></td>';
        }
    $compiled_table .='</tr>';
//    }

    echo $compiled_table;
}






function compileBatchDetail($project_id){
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM project_batch WHERE project_id = :project_id");
            $stmt->execute([":project_id"=>$project_id]);
           
                $rows = $stmt->fetchAll(PDO::FETCH_OBJ);
                $rev_total = 0;
                $batch_name = "";$rev = 0;
                foreach($rows as $row){
                    $batch_id = $row->batch_id;
                    $batch_name = $row->batch_name;
                    $rev = compileBatchRevenue($batch_id);

                    // return $batch_name." ".$rev;
                } 
                return $batch_name." ".$rev;
                
}

function compileBatchRevenue($batch_id){
        global $conn;

        $delete_status = 1;
        $query = ("select a.revenue_description, a.revenue_value, a.date_received, b.revenue_name FROM received_revenue AS a INNER JOIN revenue_definition as b ON b.defined_revenue_id = a.defined_revenue_id AND a.batch_id = :batch_id AND a.delete_status = :delete_status");
        $stmt = $conn->prepare($query);
        $stmt->execute([":batch_id"=>$batch_id, ":delete_status"=>$delete_status]);
        $count = $stmt->rowCount();

        if($count > 0){
            $rev_value = 0;
                $rows = $stmt->fetchAll(PDO::FETCH_OBJ);
                foreach($rows as $row){
                    $rev_value += $row->revenue_value;
                   
                } return $rev_value;
           
        }
        else{
            $empty = 0;
            return $empty;
        }
}











// from view batch to compile reports

function getBatchCosts($batch_id, $delete_status){
    global $conn;

    // $query = ("SELECT * FROM incurred_costs WHERE batch_id = :batch_id AND delete_status = :delete_status");
    $query = ("select a.cost_description, a.cost_value, a.date_incurred, b.cost_name FROM incurred_costs AS a INNER JOIN cost_definition as b ON b.defined_cost_id = a.defined_cost_id AND a.batch_id = :batch_id AND b.delete_status = :delete_status ORDER BY a.date_incurred ASC");
    $stmt = $conn->prepare($query);
    $stmt->execute([":batch_id"=>$batch_id, ":delete_status"=>$delete_status]);
    $count = $stmt->rowCount();

    if($count > 0){
        $rows = $stmt->fetchAll(PDO::FETCH_OBJ);
//        var_dump($rows);
        return $rows;
    }
    else{
        $empty = 0;
        return $empty;
    }
}

function getBatchRevenue($batch_id, $delete_status){
    global $conn;

    $query = ("select a.revenue_description, a.revenue_value, a.date_received, b.revenue_name FROM received_revenue AS a INNER JOIN revenue_definition as b ON b.defined_revenue_id = a.defined_revenue_id AND a.batch_id = :batch_id AND a.delete_status = :delete_status ORDER BY a.date_received ASC");
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



if ( isset( $_POST["logout"] ) ) {
    session_destroy();
}
?>