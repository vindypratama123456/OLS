<!-- Morris Charts CSS -->
<link href="<?php echo css_url('admin/bootstrap-datetimepicker.min.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/js/jquery-ui-1.12.0/jquery-ui.min.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/js/jquery-ui-1.12.0/jquery-ui.structure.min.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/js/jquery-ui-1.12.0/jquery-ui.theme.min.css'); ?>" rel="stylesheet">
<link href="<?php echo assets_url('js/admin/plugins/select2/css/select2.min.css'); ?>" rel="stylesheet">

<style type="text/css">
    .control-label {
        text-align: left !important;
    }

    /**
     * vindy 2019-06-12
     * Menambahkan fitur readonly untuk plugin select2
     * Awal
     */
    select[readonly].select2-hidden-accessible + .select2-container {
	  pointer-events: none;
	  touch-action: none;
	}

	select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
	  background: #eee;
	  box-shadow: none;
	}

	select[readonly].select2-hidden-accessible + .select2-container .select2-selection__arrow,
	select[readonly].select2-hidden-accessible + .select2-container .select2-selection__clear {
	  display: none;
	}
	/**
	 * Akhir
	 */
</style>