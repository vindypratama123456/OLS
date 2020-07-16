$(document).ready(function () {

    $(function(){        
        //Datepicker
        $('#dp').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            endDate: '0d',
            todayHighlight: true,
            toggleActive: true,
            onSelect: function () {
                $("#dp label.error").hide();
                $("#dp .error").removeClass("error");
            }
        });             
        //End Datepicker
    });

    function alertTimeout(wait){
        setTimeout(function(){
            $('.alert').remove();
        }, wait);
    }

    $(".gallery-item-remove").on("click",function(){
        var conf = confirm('Are you sure want to delete this image?');
        if(conf) {
            var key = $(this).parents(".gallery-item").data('key');
            var filename = $(this).parents(".gallery-item").data('name');
            var article = $(this).parents(".gallery-item").data('article');
            $.post(BASE_URL+'backoffice/article/removeGalleryPost', {id:key,name:filename,article:article}, function(data, textStatus, xhr) {
                console.log('Delete success');
            });
            $(this).parents(".gallery-item").fadeOut(400,function(){
                $(this).remove();
                $('#msg-gal').html('<div role="alert" class="alert alert-danger"><button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>Image gallery successfully deleted.</div>');
                alertTimeout(5000);
            });
        }
        return false;
    });

    $(".list-product-remove").on("click",function(){
        var conf = confirm('Are you sure want to delete this product?');
        if(conf) {
            var id = $(this).parents(".list-product").data('key');
            var filename = $(this).parents(".list-product").data('name');
            var article = $(this).parents(".list-product").data('article');
            $.post(BASE_URL+'backoffice/article/removeProductPost', {id:id,name:filename,article:article}, function(data, textStatus, xhr) {
                console.log('Delete success');
            });
            $(this).parents(".list-product").fadeOut(400,function(){
                $(this).remove();
                $('#msg-place').html('<div role="alert" class="alert alert-danger"><button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>Product successfully deleted.</div>');
                alertTimeout(3000);
            });
        }
        return false;
    });

    var panel = $(".panel-default");
    $("#frm-article").validate({
        ignore: [],
        rules: {                                          
            title: {
                required: true,
                minlength: 3
            },
            main_image: {
                required: function (el) {
                    return $(el).closest('form').find('#tmp_img').val()=='';
                },
                accept: 'image/*'
            },
            content: {
                required: function() {
                    CKEDITOR.instances.content.updateElement();
                },
                minlength:10
            },
            author: {
                required: true
            },
            category_id: {
                required: true
            },
            section_id: {
                required: true
            },
            date_publish: {
                required: true
            },
            status: {
                required: true
            }
        },
        submitHandler: function(form) {
            var uri = $(form).data('uri');
            $.ajax({
                async: true,
                url: BASE_URL+uri,
                type: 'POST',
                contentType: false,
                data: new FormData(form),
                processData: false,
                dataType: 'json',                
                beforeSend: function() {
                    $("#errorPlace").html("");
                    loading_button("submit");
                    panel_refresh(panel,"shown");
                },
                success: function(e){
                    setTimeout(function(){
                        panel_refresh(panel,"hidden");
                        if(e.success=="true") {
                            window.location.href = BASE_URL+e.redirect;
                        }
                        else {
                            reset_button("submit","Submit");
                            $("#errorPlace").append(e.message);
                        }
                    },500);
                }
            });
            return false;
        }
    });
});