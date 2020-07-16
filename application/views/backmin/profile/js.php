<script src="<?php echo js_url('jquery-validation/jquery.validate.min.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('jquery-validation/localization/messages_id.js?v='.date('YmdHis')); ?>"></script>
<?php if ($this->adm_level==4) { ?>
<script src="<?php echo js_url('admin/plugins/select2/js/select2.min.js?v='.date('YmdHis')); ?>"></script>
<script src="<?php echo js_url('admin/app/profile_mitra.js?v='.date('YmdHis')); ?>"></script>
<?php } else { ?>
<script src="<?php echo js_url('admin/app/profile_backmin.js?v='.date('YmdHis')); ?>"></script>
<?php } ?>