<html>
<head>
<title>HTTP Referer example</title>
<script language="javascript" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
</head>
<body>
<script type="text/javascript">
    $(document).ready(function () {            
        var url = 'http://bukusekolah.gramedia.test/pesananblanja/testing2';
        // var url = 'https://siplah.id/API/V2/getListOrder.php?seller_id=89&api_key=f21a297cadf045d8a36e950ac7585e81&start_date=2019-08-22&end_date=2019-09-02';
        $.ajax({
            url: url,
            dataType: "json",
            error: function (request, error) {
                console.log(arguments);
                alert(" Can't do because: " + error);
            },
            success: function (data) { 
                alert('success');
                console.log(data)
            }
        });              
    });
</script>

</body>