$(document).ready(function(){
    // var datas = [];
    // if($('#datatableCatalog').length>0){
    //     datas['selector'] = 'datatableCatalog';
    //     datas['url'] = BASE_URL+'catalog/list_catalog';
    //     datas['columns'] = [
    //         { 'data': 'kode_buku' },
    //         { 'data': 'isbn' },
    //         { 'data': 'name' },
    //         { 'data': 'category' },
    //         { 'data': 'type' },
    //         { 'data': 'price_1' }
    //     ];
    //     datatableLunas = myDatatables(datas);
    //     commonTools(datas['selector'], datatableLunas);
    // }
    
    
    $("#datatableCatalog").DataTable();
});