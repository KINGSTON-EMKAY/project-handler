<div class="sidebar">
      <div class="sidebar-header">
          <h3>Project Handler</h3>
      </div>
      <br>
      <div class="sidebar-links">
            <div class="sidebar-link">
                <a href="dashboard.php" class="link active"><i class="fas fa-th-list"></i> Dashboard</a>
            </div>

            <div class="sidebar-link">
                <a href="projects.php" class="link"><i class="fas fa-copy"></i> Projects</a>
            </div>
            <!-- <div class="sidebar-link">
                <a href="stock.php" class="link"><i class="fas fa-hourglass-half"></i>Stocks</a>
            </div> -->
            <div class="sidebar-link">
                <a href="reports.php" class="link"><i class="far fa-chart-bar"></i> Reports</a>
            </div>
            <br>

            <div class="sidebar-link">
                <a href="profile.php" class="link"><i class="far fa-user"></i> Profile</a>
            </div>
            <?php
            if($_SESSION["user_role"] == 1)
            { echo '<div class="sidebar-link">
                <a href="users.php" class="link"><i class="fas fa-users"></i> Users</a>
            </div>';} ?>
            <br>

            <div class="sidebar-link">
                <a  class="link" id="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
      </div>
</div>

<script>
    
$("#logout").click(function(e){
    // e.preventDefault();
    console.log("clicked")
    var logout = "logout";
    $.ajax({
        type: "POST",
        url: "php/server.php",
        data: {
            logout : logout
        },
        success: function (response) {
            window.location.reload();
        }
    });
});
</script>
