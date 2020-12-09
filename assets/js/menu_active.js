var CURRENT_URL = window.location.href.split('#')[0].split('?')[0],
    $SIDEBAR_MENU = $('#sidebar-menu');

// Sidebar
$(document).ready(function() {
    // TODO: This is some kind of easy fix, maybe we can improve this
    // check active menu
    var segments = CURRENT_URL.split( '/' );
	var iniurl = window.location.origin; 
	var potongurl= iniurl+'/'+segments[3]+'/'+segments[4]+'/'+segments[5];
	
    $SIDEBAR_MENU.find('a[href="' + potongurl + '"]').addClass('active');
    $SIDEBAR_MENU.find('a').filter(function () {
        return this.href == potongurl;
    }).parent('li').addClass('active').parents('ul').slideDown(function() {
       
    }).parent().addClass('open active');

});















