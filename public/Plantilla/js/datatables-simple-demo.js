
$(document).ready(function () {
    var table = new DataTable('#datatablesSimple', {
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json',
        },
        responsive: true,
        ordering: false,
        fixedHeader: true,
        iDisplayLength: 10,
        
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]]
           
    });
});