</div>
<!-- modals -->
<!-- add project modal -->
<div class="modal-wrapper add-project" id="add-project">

    <div class="form-wrapper">
        <form action="">
            <div class="form">
                <div class="form-header">
                    <h2>Add Project</h2>
                    <div class="modal-close">
                        <div class="close-button" id="close-button"></div>
                    </div>
                </div>
                <hr>

                <div class="form-components form-body">
                    <div class="form-element">
                        <label for="project-name">Project Name:</label> <span class="error"></span>
                        <input type="text" name="project-name" id="project-name">
                    </div>
                    <!-- <br> -->
                    <div class="form-element">
                        <label for="project-description">Short Description:</label>
                        <input type="text" name="project-description" id="project-description">
                    </div>

                </div>
                <div class="form-footer">
                    <button type="submit" class="button btn-save" id="define-project">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="modal-wrapper add-batch" id="add-project">

    <div class="form-wrapper">
        <form action="">
            <div class="form">
                <div class="form-header">
                    <h2>Add Batch</h2>
                    <div class="modal-close">
                        <div class="close-button" id="close-button"></div>
                    </div>
                </div>
                <hr>

                <div class="form-components form-body">
                    <div class="form-element">
                        <label for="batch-name">Batch Name:</label> <span class="error"></span>
                        <input type="text" name="batch-name" id="batch-name">
                    </div>

                    <!-- <br> -->
                    <div class="form-element">
                        <label for="start-date">Start Date:</label>
                        <input type="date" name="start-date" id="start-date">
                    </div>
                    <div class="form-element">
                        <label for="end-date">Expected End Date:</label>
                        <input type="date" name="end-date" id="end-date">
                    </div>

                </div>
                <div class="form-footer">
                    <button type="submit" class="button btn-save" id="define-batch">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>


<style>
    
</style>
<script>
    $(document).ready(function () {
        // $("#menu-button").click(function (e) { 
        //     e.preventDefault();
        //     $(".menu").css("display", "block");
        //     console.log("Clicked");
        // });
        $(".content-header").on("click","#menu-button", function (e) {
            e.preventDefault();
            $(this).addClass("close-menu");
            $(".sidebar").css({"display": "block", "position":"absolute","z-index":"10","transition":"all 2s ease"});

            // console.log("Clicked");
        });
        // $(document).off("click").on("click",".close-menu", function (e){
        $(".content-header").on("click",".close-menu", function (e){
            $(this).removeClass("close-menu");
            $(".sidebar").css({"display": "none","position":"fixed","z-index":""});
        });
        $(window).resize(function(){
            if($(window).width() < 480){
                $(".sidebar").css({"display":"none","z-index":""});
                $("#menu-button").removeClass("close-menu");
            }else{
                $(".sidebar").css({"display":"block","position":"relative"});
            }
            
        });
    });
</script>
<?php
    include_once("js_includes.php");
?>
</body>

</html>
