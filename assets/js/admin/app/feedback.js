$(document).ready(function(){
    var datas = [];
    if($('#datatableFeedback').length>0){
        datas['selector'] = 'datatableFeedback';
        datas['url'] = BASE_URL+'feedback/list_feedback';
        datas['columns'] = [
            { 'data': 'id_order' },
            { 'data': 'kode_pesanan' },
            { 'data': 'nama_sekolah' },
            { 'data': 'testimoni' },
            { 'data': 'tgl_tulis' },
            { 'data': 'status' }
        ];
        datas['columnDefs'] = [
            { className: 'text-center', targets: [0, 1, 4, 5] }
        ];
        datas['sort'] = [4, 'desc'];
        datatableFeedback = myDatatables(datas);
        commonTools(datas['selector'], datatableFeedback);
    }
    $('#feedback_form').submit(function(e){
        e.preventDefault();
        var conf = confirm('Yakin ingin memperbarui STATUS testimoni ini?');
        if(conf) {
            $('button').attr('disabled', true);
            $.ajax({
                type: 'POST',
                data: $('#feedback_form').serialize(),
                dataType: 'json',
                url: BASE_URL+'feedback/editPost',
                success:function(datas){
                    if(datas.success==='true') {
                        window.location.href = BASE_URL+'feedback';
                    }
                    else {
                        bootAlert(datas.message);
                        $('button').attr('disabled', false);
                    }
                }
            });
            return false;
        }
    });
});