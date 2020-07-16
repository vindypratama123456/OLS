
<script type="text/javascript">
	$(document).ready(function(){
    $("#submitDetail").on("click",function(){
      var conf = confirm('Yakin ingin melanjutkan proses pemindahan pesanan?');
      if(conf) {
      	var panel = $(".page-content-wrap");
      	var uri = $("#frmDetilPesananForward").data('uri');
        $.ajax({
          type: "POST",
          data: $("#frmDetilPesananForward").serialize(),
          dataType: "json",
          url: BASE_URL+uri,
          beforeSend: function(){
            loading_button("submitDetail");
            panel_refresh(panel,"shown");
          },
          success: function(e){
            setTimeout(function(){
                panel_refresh(panel,"hidden");
                if(e.success=="true") {
                    window.location.href = BASE_URL+e.redirect;
                }
                else 
                {
                    reset_button("submitDetail","L a n j u t k a n");
                    window.location.href = BASE_URL+e.redirect;
                }
            },500);
          }
        });
        return false;
      }
      else
      {
        return false;
      }
		});
	});
</script>