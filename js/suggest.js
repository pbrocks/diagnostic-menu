(function(window, document, $, undefined){

var hpadmin = {};

  hpadmin.init = function() {

    $('#suggest_show_on_cats').
    suggest( window.ajaxurl + "?action=ajax-tag-search&tax=category", 
    	{multiple:true, multipleSep: ","});

  }

  hpadmin.init();

})(window, document, jQuery);