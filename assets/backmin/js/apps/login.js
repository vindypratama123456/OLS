$(document).ready(function () {
    var panel = $(".login-container");
    $("#frm-login").validate({
        ignore: [],
        rules: {                                          
            u_name: {
                required: true,
                minlength: 3,
                maxlength: 75
            },
            u_pass: {
                required: true,
                minlength: 4,
                maxlength: 25
            }
        },
        submitHandler: function(form) {
            $.ajax({
                data: $(form).serialize(),
                dataType: "json",
                type: "POST",
                url: BASE_URL+"loginProcess",
                beforeSend: function() {
                    $("#errorPlace").html("");
                    loading_button("login");
                    // panel_refresh(panel,"shown");
                    pageLoadingFrame("show","v2");
                },
                success: function(e){
                    setTimeout(function(){
                        // panel_refresh(panel,"hidden");
                        pageLoadingFrame("hide","v2");
                        if(e.success=="true") {
                            window.location.href = BASE_URL+e.redirect;
                            return false;
                        }
                        else {
                            $("#errorPlace").html(e.message);
                            reset_button("login","Log In");
                        }
                    },500);
                }
            });
            return false;
        }
    });
});