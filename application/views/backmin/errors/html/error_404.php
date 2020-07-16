<!DOCTYPE html>
<html lang="en">
    <head>        
        <!-- META SECTION -->
        <title>Halaman Tidak Ditemukan - <?php echo config_item('app_name'); ?></title> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" href="<?php echo base_url(); ?>favicon.ico" type="image/x-icon" />
        <!-- END META SECTION -->
        <!-- CSS INCLUDE -->        
        <link rel="stylesheet" type="text/css" id="theme" href="<?php echo assets('css'); ?>/theme-default.css"/>
        <!-- EOF CSS INCLUDE -->                                  
    </head>
    <body>
        <div class="error-container">
            <div class="error-code">404</div>
            <div class="error-text">Halaman Tidak Ditemukan</div>
            <div class="error-subtext">Mohon maaf, halaman yang anda maksud tidak tersedia.<br />Silahkan pilih salah satu tombol dibawah ini.</div>
            <div class="error-actions">                                
                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-info btn-block btn-lg" onClick="document.location.href= '<?php echo base_url(); ?>';">Ke Dasbor</button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-block btn-lg" onClick="history.back();">Kembali</button>
                    </div>
                </div>                                
            </div>
        </div>                 
    </body>
</html>