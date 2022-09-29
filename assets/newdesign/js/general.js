jQuery('document').ready(function(){ 
    // if(jQuery('.custom-dropdown').length) {
    //     jQuery(".custom-dropdown select").each(function () {
    //         var _this = jQuery(this);
    //         _this.select2({
    //             dropdownParent: _this.closest("div"),
    //             minimumResultsForSearch: Infinity,
    //         });
    //     });
    // }
    jQuery('.hamburger-menu').click(function(){
        jQuery('html,body').toggleClass('open-menu');
    });

    jQuery('.popup-link').click(function (e) {
        
        e.preventDefault();
        var _this_id = jQuery(this).attr('data-popup');
        jQuery('body,html').addClass('overflow-hidden')
        jQuery('.custom-popup[data-modal="' + _this_id + '"]').addClass('popup-open');
    });
   
    jQuery('.custom-popup .popup-dialog-wrapper,.custom-popup .close-popup').click(function () {
        jQuery('body,html').removeClass('overflow-hidden');
        jQuery('.custom-popup').removeClass('popup-open');
    });

    jQuery('.custom-popup .popup-content').click(function (e) {
        e.stopPropagation();
    });

    jQuery('.site-header nav ul li.has-submenu>a').click(function(e){
        e.preventDefault();
        jQuery(this).closest('li').toggleClass('submenu-open');
        jQuery(this).closest('li').find('.submenu').slideToggle();
    });
  
});
jQuery(window).on("load", function() {

});