$(document).ready(function(){
    var datas = [];
    if($('#datatableCatalog').length>0){
        datas['selector'] = 'datatableCatalog';
        datas['url'] = BASE_URL+'catalog/list_catalog';
        datas['columns'] = [
            { 'data': 'kode_buku' },
            { 'data': 'isbn' },
            { 'data': 'name' },
            { 'data': 'category' },
            { 'data': 'type' },
            { 'data': 'price_1' },
            { 'data': 'price_2' },
            { 'data': 'price_3' },
            { 'data': 'price_4' },
            { 'data': 'price_5' }
        ];
        datas['columnDefs'] = [
            { className: "text-center", targets: [0, 1, 3, 4] },
            { className: "text-right", targets: [5, 6, 7, 8], render: $.fn.dataTable.render.number( ',', '.', 0 ) }
        ];
        datas['sort'] = [3,'asc'];
        datatableLunas = myDatatables(datas);
        commonTools(datas['selector'], datatableLunas);
    }
});