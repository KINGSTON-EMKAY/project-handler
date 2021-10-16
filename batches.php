<?php
include_once( "include/header.php" );
include_once( "include/sidebar.php" );
?>

<div class="content">
    <div class="content-header">
        <div class="page-heading">
            <h2>Batches</h2>
        </div>
        <div class="menu-button-wrapper">
            <div class="menu-button"></div>
        </div>

    </div>
    <div class="content-body">

        <div class="table-wrapper">
            <table class="table">
                <div class="table-heading">
                    <div class="form-wrapper">
                        <form action="">
                            <input type="text" name="" id="" placeholder="search">
                        </form>
                    </div>
                </div>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Project Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Batches</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td scope="row">1</td>
                        <td>Bees</td>
                        <td>02/10</td>
                        <td>02/12</td>
                        <td><button class="button">Cost</button><button class="button">Receipt</button><button class="button">Overview</button></td>
                    </tr>
                    <tr>
                        <td scope="row">2</td>
                        <td>Beans</td>
                        <td>02/10</td>
                        <td>02/12</td>
                        <td>Undefined</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <style>

        </style>

    </div>

    <!-- modal ectiopn -->

    <!-- <div class = "modal-wrapper cost-modal">

</div> -->

    <!-- modal end -->
    <?php
include_once( "include/footer.php" );
?>
