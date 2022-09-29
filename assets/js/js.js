$(document).ready(function () {

    var url = window.location;
    $('.sidebar-nav a[href="'+ url +'"]').parent().addClass('active');
    $('.sidebar-nav a').filter(function() {
         return this.href == url;
    }).parent().addClass('active');

    $( ".active a" ).after( "<div class='arrow-left animated slideInRight'></div>" );
});

$("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
});



