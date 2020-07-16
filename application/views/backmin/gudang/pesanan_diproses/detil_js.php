
<script type='text/javascript' src='<?php echo assets_url('js'); ?>/jquery-validation/additional/accept.js'></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/jquery-filer/js/jquery.filer.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/bootstrap/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/blueimp/jquery.blueimp-gallery.min.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/dropzone//dropzone.min.js"></script>
<script type="text/javascript" src="<?php echo assets_url_backmin('js'); ?>/plugins/icheck/icheck.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#filer_input2").filer({
	        limit: 1,
	        maxSize: null,
	        extensions: ['jpg', 'jpeg', 'png', 'gif'],
	        changeInput: '<div class="jFiler-input-dragDrop"><div class="jFiler-input-inner"><div class="jFiler-input-icon"></div><div class="jFiler-input-text"></div><a class="jFiler-input-choose-btn"><i class="fa fa-camera fa-3x" aria-hidden="true"></i></a></div></div>',
	        showThumbs: true,
	        theme: "dragdropbox",
	        templates: {
	            box: '<ul class="jFiler-items-list jFiler-items-grid"></ul>',
	            item: '<li class="jFiler-item">\
	                        <div class="jFiler-item-container">\
	                            <div class="jFiler-item-inner">\
	                                <div class="jFiler-item-thumb">\
	                                    <div class="jFiler-item-status"></div>\
	                                    <div class="jFiler-item-thumb-overlay">\
	                                        <div class="jFiler-item-info">\
	                                            <div style="display:table-cell;vertical-align: middle;">\
	                                                <span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name}}</b></span>\
	                                                <span class="jFiler-item-others">{{fi-size2}}</span>\
	                                            </div>\
	                                        </div>\
	                                    </div>\
	                                    {{fi-image}}\
	                                </div>\
	                                <div class="jFiler-item-assets jFiler-row">\
	                                    <ul class="list-inline pull-left">\
	                                        <li>{{fi-progressBar}}</li>\
	                                    </ul>\
	                                    <ul class="list-inline pull-right">\
	                                        <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
	                                    </ul>\
	                                </div>\
	                            </div>\
	                        </div>\
	                    </li>',
	            itemAppend: '<li class="jFiler-item">\
	                            <div class="jFiler-item-container">\
	                                <div class="jFiler-item-inner">\
	                                    <div class="jFiler-item-thumb">\
	                                        <div class="jFiler-item-status"></div>\
	                                        <div class="jFiler-item-thumb-overlay">\
	                                            <div class="jFiler-item-info">\
	                                                <div style="display:table-cell;vertical-align: middle;">\
	                                                    <span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name}}</b></span>\
	                                                    <span class="jFiler-item-others">{{fi-size2}}</span>\
	                                                </div>\
	                                            </div>\
	                                        </div>\
	                                        {{fi-image}}\
	                                    </div>\
	                                    <div class="jFiler-item-assets jFiler-row">\
	                                        <ul class="list-inline pull-left">\
	                                            <li><span class="jFiler-item-others">{{fi-icon}}</span></li>\
	                                        </ul>\
	                                        <ul class="list-inline pull-right">\
	                                            <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
	                                        </ul>\
	                                    </div>\
	                                </div>\
	                            </div>\
	                        </li>',
	            progressBar: '<div class="bar"></div>',
	            itemAppendToEnd: true,
	            canvasImage: true,
	            removeConfirmation: true,
	            _selectors: {
	                list: '.jFiler-items-list',
	                item: '.jFiler-item',
	                progressBar: '.bar',
	                remove: '.jFiler-item-trash-action'
	            }
	        },
	        files: null,
	        addMore: true,
	        allowDuplicates: true,
	        clipBoardPaste: true,
	        excludeName: null,
	        beforeRender: null,
	        afterRender: null,
	        beforeShow: null,
	        beforeSelect: null,
	        onSelect: null,
	        afterShow: null,
	        onEmpty: null,
	        options: null,
	        dialogs: {
	            alert: function(text) {
	                return alert(text);
	            },
	            confirm: function (text, callback) {
	                confirm(text) ? callback() : null;
	            }
	        },
	        captions: {
	            button: "Pilih Foto",
	            feedback: "Pilih Foto untuk Diunggah",
	            feedback2: "Foto terpilih",
	            drop: "Drop file here to Upload",
	            removeConfirmation: "Yakin ingin menghapus?",
	            errors: {
	                filesLimit: "Only {{fi-limit}} files are allowed to be uploaded.",
	                filesType: "Hanya diperbolehkan file foto/gambar.",
	                filesSize: "{{fi-name}} terlalu besar! Maksimal ukuran file {{fi-maxSize}} MB.",
	                filesSizeAll: "File yang dipilih terlalu besar! Maksimal total ukuran file {{fi-maxSize}} MB."
	            }
	        }
	    });

		$("#filer_input3").filer({
	        limit: 1,
	        maxSize: null,
	        extensions: ['jpg', 'jpeg', 'png', 'gif'],
	        changeInput: '<div class="jFiler-input-dragDrop"><div class="jFiler-input-inner"><div class="jFiler-input-icon"></div><div class="jFiler-input-text"></div><a class="jFiler-input-choose-btn"><i class="fa fa-camera fa-3x" aria-hidden="true"></i></a></div></div>',
	        showThumbs: true,
	        theme: "dragdropbox",
	        templates: {
	            box: '<ul class="jFiler-items-list jFiler-items-grid"></ul>',
	            item: '<li class="jFiler-item">\
	                        <div class="jFiler-item-container">\
	                            <div class="jFiler-item-inner">\
	                                <div class="jFiler-item-thumb">\
	                                    <div class="jFiler-item-status"></div>\
	                                    <div class="jFiler-item-thumb-overlay">\
	                                        <div class="jFiler-item-info">\
	                                            <div style="display:table-cell;vertical-align: middle;">\
	                                                <span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name}}</b></span>\
	                                                <span class="jFiler-item-others">{{fi-size2}}</span>\
	                                            </div>\
	                                        </div>\
	                                    </div>\
	                                    {{fi-image}}\
	                                </div>\
	                                <div class="jFiler-item-assets jFiler-row">\
	                                    <ul class="list-inline pull-left">\
	                                        <li>{{fi-progressBar}}</li>\
	                                    </ul>\
	                                    <ul class="list-inline pull-right">\
	                                        <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
	                                    </ul>\
	                                </div>\
	                            </div>\
	                        </div>\
	                    </li>',
	            itemAppend: '<li class="jFiler-item">\
	                            <div class="jFiler-item-container">\
	                                <div class="jFiler-item-inner">\
	                                    <div class="jFiler-item-thumb">\
	                                        <div class="jFiler-item-status"></div>\
	                                        <div class="jFiler-item-thumb-overlay">\
	                                            <div class="jFiler-item-info">\
	                                                <div style="display:table-cell;vertical-align: middle;">\
	                                                    <span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name}}</b></span>\
	                                                    <span class="jFiler-item-others">{{fi-size2}}</span>\
	                                                </div>\
	                                            </div>\
	                                        </div>\
	                                        {{fi-image}}\
	                                    </div>\
	                                    <div class="jFiler-item-assets jFiler-row">\
	                                        <ul class="list-inline pull-left">\
	                                            <li><span class="jFiler-item-others">{{fi-icon}}</span></li>\
	                                        </ul>\
	                                        <ul class="list-inline pull-right">\
	                                            <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
	                                        </ul>\
	                                    </div>\
	                                </div>\
	                            </div>\
	                        </li>',
	            progressBar: '<div class="bar"></div>',
	            itemAppendToEnd: true,
	            canvasImage: true,
	            removeConfirmation: true,
	            _selectors: {
	                list: '.jFiler-items-list',
	                item: '.jFiler-item',
	                progressBar: '.bar',
	                remove: '.jFiler-item-trash-action'
	            }
	        },
	        files: null,
	        addMore: true,
	        allowDuplicates: true,
	        clipBoardPaste: true,
	        excludeName: null,
	        beforeRender: null,
	        afterRender: null,
	        beforeShow: null,
	        beforeSelect: null,
	        onSelect: null,
	        afterShow: null,
	        onEmpty: null,
	        options: null,
	        dialogs: {
	            alert: function(text) {
	                return alert(text);
	            },
	            confirm: function (text, callback) {
	                confirm(text) ? callback() : null;
	            }
	        },
	        captions: {
	            button: "Pilih Foto",
	            feedback: "Pilih Foto untuk Diunggah",
	            feedback2: "Foto terpilih",
	            drop: "Drop file here to Upload",
	            removeConfirmation: "Yakin ingin menghapus?",
	            errors: {
	                filesLimit: "Only {{fi-limit}} files are allowed to be uploaded.",
	                filesType: "Hanya diperbolehkan file foto/gambar.",
	                filesSize: "{{fi-name}} terlalu besar! Maksimal ukuran file {{fi-maxSize}} MB.",
	                filesSizeAll: "File yang dipilih terlalu besar! Maksimal total ukuran file {{fi-maxSize}} MB."
	            }
	        }
	    });

		$("#datepicker_terimabarang").datepicker({endDate: '+0d'});

		$("#frmProsesTerimaBarang").validate({
			ignore: [],
			rules: {
				nama_penerima: {
					required: true,
				},
				tanggal_terima: {
					required: true,
				},
				file_bast: {
					required: true,
	                accept: 'image/*'
	            }
			}
		});

		$("#submitDetail").on("click",function()
		{			
			if ($("#frmProsesTerimaBarang").valid() == true)
			{
				var conf = confirm('Yakin ingin melanjutkan proses terima barang?');
				if(conf) {
					var panel = $(".page-content-wrap");
					var uri = $("#frmProsesTerimaBarang").data('uri');
			        // Get form
			        var formBarang = $('#frmProsesTerimaBarang')[0];
			        var formPesanan = $('#frmDetilPesananMasuk').serializeArray();
					// Create an FormData object
			        var datas = new FormData(formBarang);
			        // Append datas
			        $.each(formPesanan, function(key, input) {
			        	datas.append(input.name, input.value);
			        });
					$.ajax({
						async: true,
		                contentType: false,
		                processData: false,
						type: "POST",
						data: datas,
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
									reset_button("submitDetail","P r o s e s");
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
			}
			else
			{
				return false;
			}
		});
	});
</script>
