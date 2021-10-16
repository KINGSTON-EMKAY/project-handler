$(document).ready(function() {
    // login

    $("#login").click(function(e) {
        e.preventDefault();
        var password = $("#password").val();
        var email = $("#email").val();
        var login = "login";

        if (email == "") {

            $(".uname-error").html("Enter your email!");

        } else if (password == "") {
            $(".pass-error").html("Enter your password!");
        } else {
            $.ajax({
                url: "php/server.php",
                method: "POST",
                data: {
                    login: login,
                    email: email,
                    password: password
                },
                success: function(response) {

                    if (response == "Success") {
                        window.location.href = "dashboard.php";
                    } else {
                        // $(".error").css("display", "block");
                        $(".detail-error").html("Incorrect email or password!");
                    }
                }
            });

        };
    });

    $("#email").keyup(function() {
        $(".uname-error").html("");
    });
    $("#password").keyup(function() {
        $(".pass-error").html("");
    });










    // display projects from ajax call
    var get_projects;
    $.ajax({

        url: "php/server.php",
        method: "POST",
        data: { get_projects: get_projects },
        success: function(response) {
            // console.log(response);
        },
    });




    // modal controls

    $("#add-project-button").click(function(e) {
        e.preventDefault();
        $(".add-project").css("display", "block");
    });



    $("#close-button").click(function(e) {
        e.preventDefault();
        $(".add-project").css("display", "none");
    });

    // all code above except functions
})