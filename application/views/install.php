<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script src="<?php echo base_url('assets/backmin/js/plugins/jquery/jquery.min.js'); ?> " type="text/javascript"></script>
	<script>
		$(document).ready(function(){
				var uri1 = '<?php echo base_url("home/generateJson2013/1-6/1"); ?>';
				var uri2 = '<?php echo base_url("home/generateJsonAllTeks/1-6"); ?>';
				var uri3 = '<?php echo base_url("home/generateJsonAllTeksKonfirmasi/1-6"); ?>';
				var uri4 = '<?php echo base_url("home/generateJson2013/7-9/1"); ?>';
				var uri5 = '<?php echo base_url("home/generateJson2006/7-9"); ?>';
				var uri6 = '<?php echo base_url("home/generateJsonAllTeks/7-9"); ?>';
				var uri7 = '<?php echo base_url("home/generateJsonAllTeksKonfirmasi/7-9"); ?>';
				var uri8 = '<?php echo base_url("home/generateJson2013/10-12/1"); ?>';
				var uri9 = '<?php echo base_url("home/generateJson2006/10-12"); ?>';
				var uri10 = '<?php echo base_url("home/generateJsonPeminatan/10-12"); ?>';
				var uri11 = '<?php echo base_url("home/generateJsonAllTeks/10-12"); ?>';
				var uri12 = '<?php echo base_url("home/generateJsonAllTeksKonfirmasi/10-12"); ?>';
				var uri13 = '<?php echo base_url("home/generateJsonLiterasi/1-6"); ?>';
				var uri14 = '<?php echo base_url("home/generateJsonLiterasi/7-9"); ?>';
				var uri15 = '<?php echo base_url("home/generateJsonLiterasi/10-12"); ?>';
				var uri16 = '<?php echo base_url("home/generateJsonPengayaan/1-6"); ?>';
				var uri17 = '<?php echo base_url("home/generateJsonPengayaan/7-9"); ?>';
				var uri18 = '<?php echo base_url("home/generateJsonPengayaan/10-12"); ?>';
				var uri19 = '<?php echo base_url("home/generateJsonReferensi/1-6"); ?>';
				var uri20 = '<?php echo base_url("home/generateJsonReferensi/7-9"); ?>';
				var uri21 = '<?php echo base_url("home/generateJsonReferensi/10-12"); ?>';
				var uri22 = '<?php echo base_url("home/generateJsonPandik/1-6"); ?>';
				var uri23 = '<?php echo base_url("home/generateJsonPandik/7-9"); ?>';
				var uri24 = '<?php echo base_url("home/generateJsonPandik/10-12"); ?>';
				var uri25 = '<?php echo base_url("home/generateJsonPendampingk13/1-6"); ?>';
				var uri26 = '<?php echo base_url("home/generateJsonPendampingk13/7-9"); ?>';
				var uri27 = '<?php echo base_url("home/generateJsonPendampingk13/10-12"); ?>';
				var uri28 = '<?php echo base_url("home/generateJsonPeminatanSmaMa/1-6"); ?>';
				var uri29 = '<?php echo base_url("home/generateJsonPeminatanSmaMa/7-9"); ?>';
				var uri30 = '<?php echo base_url("home/generateJsonPeminatanSmaMa/10-12"); ?>';
				var uri31 = '<?php echo base_url("home/generateJsonHetk13/1-6"); ?>';
				var uri32 = '<?php echo base_url("home/generateJsonHetk13/7-9"); ?>';
				var uri33 = '<?php echo base_url("home/generateJsonHetk13/10-12"); ?>';
				var uri34 = '<?php echo base_url("home/generateJsonProductIt/1-6"); ?>';
				var uri35 = '<?php echo base_url("home/generateJsonProductIt/7-9"); ?>';
				var uri36 = '<?php echo base_url("home/generateJsonProductIt/10-12"); ?>';
				var uri37 = '<?php echo base_url("home/generateJsonProductCovid/1-6"); ?>';
				var uri38 = '<?php echo base_url("home/generateJsonProductCovid/7-9"); ?>';
				var uri39 = '<?php echo base_url("home/generateJsonProductCovid/10-12"); ?>';
				var uri40 = '<?php echo base_url("home/generateJsonAlatTulis/1-6"); ?>';
				var uri41 = '<?php echo base_url("home/generateJsonAlatTulis/7-9"); ?>';
				var uri42 = '<?php echo base_url("home/generateJsonAlatTulis/10-12"); ?>';
				var uri43 = '<?php echo base_url("home/generateJsonSmartLibrary/1-6"); ?>';
				var uri44 = '<?php echo base_url("home/generateJsonSmartLibrary/7-9"); ?>';
				var uri45 = '<?php echo base_url("home/generateJsonSmartLibrary/10-12"); ?>';

				$.ajax({
                    url: uri1,
                    dataType: "json",
                    success: function(e1){
                        console.log(e1.message);
	                        $("#alert").append("1. " + e1.message + " <b>OK</b> <br/>");
                        req2();
                    }
                });

				function req2(){
					$.ajax({
	                    url: uri2,
	                    dataType: "json",
	                    success: function(e2){
	                        console.log(e2.message);
	                        $("#alert").append("2. " + e2.message + " <b>OK</b> <br/>");
	                        req3();
	                    }
	                });
				}

				function req3(){
					$.ajax({
	                    url: uri3,
	                    dataType: "json",
	                    success: function(e3){
	                        console.log(e3.message);
	                        $("#alert").append("3. " + e3.message + " <b>OK</b> <br/>");
	                        req4();
	                    }
	                });
				}

				function req4(){
					$.ajax({
	                    url: uri4,
	                    dataType: "json",
	                    success: function(e4){
	                        console.log(e4.message);
	                        $("#alert").append("4. " + e4.message + " <b>OK</b> <br/>");
	                        req5();
	                    }
	                });
				}

				function req5(){
					$.ajax({
	                    url: uri5,
	                    dataType: "json",
	                    success: function(e5){
	                        console.log(e5.message);
	                        $("#alert").append("5. " + e5.message + " <b>OK</b> <br/>");
	                        req6();
	                    }
	                });
				}

				function req6(){
					$.ajax({
	                    url: uri6,
	                    dataType: "json",
	                    success: function(e6){
	                        console.log(e6.message);
	                        $("#alert").append("6. " + e6.message + " <b>OK</b> <br/>");
	                        req7();
	                    }
	                });
				}

				function req7(){
					$.ajax({
	                    url: uri7,
	                    dataType: "json",
	                    success: function(e7){
	                        console.log(e7.message);
	                        $("#alert").append("7. " + e7.message + " <b>OK</b> <br/>");
	                        req8();
	                    }
	                });
				}

				function req8(){
					$.ajax({
	                    url: uri8,
	                    dataType: "json",
	                    success: function(e8){
	                        console.log(e8.message);
	                        $("#alert").append("8. " + e8.message + " <b>OK</b> <br/>");
	                        req9();
	                    }
	                });
				}

				function req9(){
					$.ajax({
	                    url: uri9,
	                    dataType: "json",
	                    success: function(e9){
	                        console.log(e9.message);
	                        $("#alert").append("9. " + e9.message + " <b>OK</b> <br/>");
	                        req10();
	                    }
	                });
				}

				function req10(){
					$.ajax({
	                    url: uri10,
	                    dataType: "json",
	                    success: function(e10){
	                        console.log(e10.message);
	                        $("#alert").append("10. " + e10.message + " <b>OK</b> <br/>");
	                        req11();
	                    }
	                });
				}

				function req11(){
					$.ajax({
	                    url: uri11,
	                    dataType: "json",
	                    success: function(e11){
	                        console.log(e11.message);
	                        $("#alert").append("11. " + e11.message + " <b>OK</b> <br/>");
	                        req12();
	                    }
	                });
				}

				function req12(){
					$.ajax({
	                    url: uri12,
	                    dataType: "json",
	                    success: function(e12){
	                        console.log(e12.message);
	                        $("#alert").append("12. " + e12.message + " <b>OK</b> <br/>");
	                        req13();
	                    }
	                });
				}

				function req13(){
					$.ajax({
	                    url: uri13,
	                    dataType: "json",
	                    success: function(e13){
	                        console.log(e13.message);
	                        $("#alert").append("13. " + e13.message + " <b>OK</b> <br/>");
	                        req14();
	                    }
	                });
				}

				function req14(){
					$.ajax({
	                    url: uri14,
	                    dataType: "json",
	                    success: function(e14){
	                        console.log(e14.message);
	                        $("#alert").append("14. " + e14.message + " <b>OK</b> <br/>");
	                        req15();
	                    }
	                });
				}

				function req15(){
					$.ajax({
	                    url: uri15,
	                    dataType: "json",
	                    success: function(e15){
	                        console.log(e15.message);
	                        $("#alert").append("15. " + e15.message + " <b>OK</b> <br/>");
	                        req16();
	                    }
	                });
				}

				function req16(){
					$.ajax({
	                    url: uri16,
	                    dataType: "json",
	                    success: function(e16){
	                        console.log(e16.message);
	                        $("#alert").append("16. " + e16.message + " <b>OK</b> <br/>");
	                        req17();
	                    }
	                });
				}

				function req17(){
					$.ajax({
	                    url: uri17,
	                    dataType: "json",
	                    success: function(e17){
	                        console.log(e17.message);
	                        $("#alert").append("17. " + e17.message + " <b>OK</b> <br/>");
	                        req18();
	                    }
	                });
				}

				function req18(){
					$.ajax({
	                    url: uri18,
	                    dataType: "json",
	                    success: function(e18){
	                        console.log(e18.message);
	                        $("#alert").append("18. " + e18.message + " <b>OK</b> <br/>");
	                        req19();
	                    }
	                });
				}

				function req19(){
					$.ajax({
	                    url: uri19,
	                    dataType: "json",
	                    success: function(e19){
	                        console.log(e19.message);
	                        $("#alert").append("19. " + e19.message + " <b>OK</b> <br/>");
	                        req20();
	                    }
	                });
				}

				function req20(){
					$.ajax({
	                    url: uri20,
	                    dataType: "json",
	                    success: function(e20){
	                        console.log(e20.message);
	                        $("#alert").append("20. " + e20.message + " <b>OK</b> <br/>");
	                        req21();
	                    }
	                });
				}

				function req21(){
					$.ajax({
	                    url: uri21,
	                    dataType: "json",
	                    success: function(e21){
	                        console.log(e21.message);
	                        $("#alert").append("21. " + e21.message + " <b>OK</b> <br/>");
	                        req22();
	                    }
	                });
				}

				function req22(){
					$.ajax({
	                    url: uri22,
	                    dataType: "json",
	                    success: function(e22){
	                        console.log(e22.message);
	                        $("#alert").append("22. " + e22.message + " <b>OK</b> <br/>");
	                        req23();
	                    }
	                });
				}

				function req23(){
					$.ajax({
	                    url: uri23,
	                    dataType: "json",
	                    success: function(e23){
	                        console.log(e23.message);
	                        $("#alert").append("23. " + e23.message + " <b>OK</b> <br/>");
	                        req24();
	                    }
	                });
				}

				function req24(){
					$.ajax({
	                    url: uri24,
	                    dataType: "json",
	                    success: function(e24){
	                        console.log(e24.message);
	                        $("#alert").append("24. " + e24.message + " <b>OK</b> <br/>");
	                        req25();
	                    }
	                });
				}

				function req25(){
					$.ajax({
	                    url: uri25,
	                    dataType: "json",
	                    success: function(e25){
	                        console.log(e25.message);
	                        $("#alert").append("25. " + e25.message + " <b>OK</b> <br/>");
	                        req26();
	                    }
	                });
				}

				function req26(){
					$.ajax({
	                    url: uri26,
	                    dataType: "json",
	                    success: function(e26){
	                        console.log(e26.message);
	                        $("#alert").append("26. " + e26.message + " <b>OK</b> <br/>");
	                        req27();
	                    }
	                });
				}

				function req27(){
					$.ajax({
	                    url: uri27,
	                    dataType: "json",
	                    success: function(e27){
	                        console.log(e27.message);
	                        $("#alert").append("27. " + e27.message + " <b>OK</b> <br/>");
	                        req28();
	                    }
	                });
				}

				function req28(){
					$.ajax({
	                    url: uri28,
	                    dataType: "json",
	                    success: function(e28){
	                        console.log(e28.message);
	                        $("#alert").append("28. " + e28.message + " <b>OK</b> <br/>");
	                        req29();
	                    }
	                });
				}

				function req29(){
					$.ajax({
	                    url: uri29,
	                    dataType: "json",
	                    success: function(e29){
	                        console.log(e29.message);
	                        $("#alert").append("29. " + e29.message + " <b>OK</b> <br/>");
	                        req30();
	                    }
	                });
				}

				function req30(){
					$.ajax({
	                    url: uri30,
	                    dataType: "json",
	                    success: function(e30){
	                        console.log(e30.message);
	                        $("#alert").append("30. " + e30.message + " <b>OK</b> <br/>");
	                        req31();
	                    }
	                });
				}

				function req31(){
					$.ajax({
	                    url: uri31,
	                    dataType: "json",
	                    success: function(e31){
	                        console.log(e31.message);
	                        $("#alert").append("31. " + e31.message + " <b>OK</b> <br/>");
	                        req32();
	                    }
	                });
				}

				function req32(){
					$.ajax({
	                    url: uri32,
	                    dataType: "json",
	                    success: function(e32){
	                        console.log(e32.message);
	                        $("#alert").append("32. " + e32.message + " <b>OK</b> <br/>");
	                        req33();
	                    }
	                });
				}

				function req33(){
					$.ajax({
	                    url: uri33,
	                    dataType: "json",
	                    success: function(e33){
	                        console.log(e33.message);
	                        $("#alert").append("33. " + e33.message + " <b>OK</b> <br/>");
	                        req34();
	                    }
	                });
				}

				function req34(){
					$.ajax({
	                    url: uri34,
	                    dataType: "json",
	                    success: function(e34){
	                        console.log(e34.message);
	                        $("#alert").append("34. " + e34.message + " <b>OK</b> <br/>");
	                        req35();
	                    }
	                });
				}

				function req35(){
					$.ajax({
	                    url: uri35,
	                    dataType: "json",
	                    success: function(e35){
	                        console.log(e35.message);
	                        $("#alert").append("35. " + e35.message + " <b>OK</b> <br/>");
	                        req36();
	                    }
	                });
				}

				function req36(){
					$.ajax({
	                    url: uri36,
	                    dataType: "json",
	                    success: function(e36){
	                        console.log(e36.message);
	                        $("#alert").append("36. " + e36.message + " <b>OK</b> <br/>");
	                        req37();
	                    }
	                });
				}

				function req37(){
					$.ajax({
	                    url: uri37,
	                    dataType: "json",
	                    success: function(e37){
	                        console.log(e37.message);
	                        $("#alert").append("37. " + e37.message + " <b>OK</b> <br/>");
	                        req38();
	                    }
	                });
				}

				function req38(){
					$.ajax({
	                    url: uri38,
	                    dataType: "json",
	                    success: function(e38){
	                        console.log(e38.message);
	                        $("#alert").append("38. " + e38.message + " <b>OK</b> <br/>");
	                        req39();
	                    }
	                });
				}

				function req39(){
					$.ajax({
	                    url: uri39,
	                    dataType: "json",
	                    success: function(e39){
	                        console.log(e39.message);
	                        $("#alert").append("39. " + e39.message + " <b>OK</b> <br/>");
	                        req40();
	                    }
	                });
				}

				function req40(){
					$.ajax({
	                    url: uri40,
	                    dataType: "json",
	                    success: function(e40){
	                        console.log(e40.message);
	                        $("#alert").append("40. " + e40.message + " <b>OK</b> <br/>");
	                        req41();
	                    }
	                });
				}

				function req41(){
					$.ajax({
	                    url: uri41,
	                    dataType: "json",
	                    success: function(e41){
	                        console.log(e41.message);
	                        $("#alert").append("41. " + e41.message + " <b>OK</b> <br/>");
	                        req42();
	                    }
	                });
				}

				function req42(){
					$.ajax({
	                    url: uri42,
	                    dataType: "json",
	                    success: function(e42){
	                        console.log(e42.message);
	                        $("#alert").append("42. " + e42.message + " <b>OK</b> <br/>");
	                        req43();
	                    }
	                });
				}

				function req43(){
					$.ajax({
	                    url: uri43,
	                    dataType: "json",
	                    success: function(e43){
	                        console.log(e43.message);
	                        $("#alert").append("43. " + e43.message + " <b>OK</b> <br/>");
	                        req44();
	                    }
	                });
				}

				function req44(){
					$.ajax({
	                    url: uri44,
	                    dataType: "json",
	                    success: function(e44){
	                        console.log(e44.message);
	                        $("#alert").append("44. " + e44.message + " <b>OK</b> <br/>");
	                        req45();
	                    }
	                });
				}

				function req45(){
					$.ajax({
	                    url: uri45,
	                    dataType: "json",
	                    success: function(e45){
	                        console.log(e45.message);
	                        $("#alert").append("45. " + e45.message + " <b>OK</b> <br/>");
	                        $("#alert").append("<h3>Generate JSON file success.</h3>");
	                    }
	                });
				}
		});
	</script>
</head>
<body>
	<div id="alert">
	</div>
</body>
</html>