<script type="text/javascript">
    $(function(){
        var hash = window.location.hash;
        hash && $('#myTab a[href="' + hash + '"]').tab('show');

        $('#myTab a').click(function (e) {
            $('#myTab a').removeClass('active');
            $(this).tab('show');
            $(this).addClass('active');
            var scrollmem = $('body').scrollTop() || $('html').scrollTop();
            window.location.hash = this.hash;
            $('html,body').scrollTop(scrollmem);
        });

        // Javascript to enable link to tab
        var url = document.location.toString();
        if (url.match('#')) {
            $('#myTab a[href="#' + url.split('#')[1] + '"]').tab('show');
            $('#myTab a').removeClass('active');
            $('#myTab a[href="' + hash + '"]').addClass('active');
        }
    });

    function resetSearch()
    {
        $("#search_input").val('');
        window.location = BASE_URL + 'backmin/scmlaporan/indexStok';
    }
</script>
