<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">Pilih Gudang</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="table-responsive">
            <?php if($list_warehouse) { ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th class="text-center" width="35%">Nama Gudang</th>
                        <th class="text-center" width="50%">Alamat Gudang</th>
                        <th class="text-center" width="10%">Opsi</th>
                    </tr>
                </thead>
                <tbody
                    <?php $no = 1; foreach($list_warehouse as $row) { ?>                                       
                    <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $row->nama_gudang; ?></td>
                        <td><?php echo $row->alamat_gudang; ?></td>
                        <td class="text-center">
                            <?php if($row->status == 1) { ?>
                            <a href="<?php echo base_url(BACKMIN_PATH.'/scmpesanan/detailPesananForward/'.$id_order.'/'.$row->id_gudang); ?>" class="btn btn-default btn-rounded btn-condensed btn-sm pilih_gudang"><span class="fa fa-check"></span></a>
                            <?php } else { echo "Tidak Aktif"; } ?>
                        </td>
                    </tr>
                    <?php $no++; } ?>
                </tbody>
            </table>
            <?php } ?>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
</div>