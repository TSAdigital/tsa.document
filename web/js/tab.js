$('#myTab').ready(function(){
    var storage = localStorage.getItem('nav-item');
    if (storage && storage !== "#") {
        $('.nav-item a[href="' + storage + '"]').tab('show');
    }

    $('#myTab.nav li').on('click', function() {
        var id = $(this).find('a').attr('href');
        localStorage.setItem('nav-item', id);
    });
});