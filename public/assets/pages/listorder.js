function refreshData() {
    
}
function hapus(id) {
    
}
function edit(id) {
    
}

document.addEventListener('DOMContenLoaded', (c) => {
    refreshData();
    $('body').on('click', 'a.link-hapus', (e) => {
        var c = confirm("Apakah anda yakin ingin menghapus?");
        if (c == true) {
            var id = $(e.currentTarget).data('id');
            hapus(id);
        }
    });
    $('body').on('click', 'a.link-edit', (e) => {
        var id = $(e.currentTarget).data('id');
        edit(id);
    });
});