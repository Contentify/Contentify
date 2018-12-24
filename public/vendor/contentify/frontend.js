// This script is used by all vanilla themes.
$(document).ready(function()
{
    var navKey = 'navIndex';
    var navIndex = sessionStorage.getItem(navKey);
    var $nav = $('#header nav');

    if (typeof navIndex === 'undefined') {
        navIndex = 0;
    }

    if (navIndex > 0) {
        $nav.find('a').removeClass('active');
        $nav.find('a:eq(' + navIndex + ')').addClass('active');
    }

    $nav.find('a').click(function(event)
    {
        var offset = $(this).parent().parent().find('li.icon').length;
        sessionStorage.setItem(navKey, $(this).parent().index() - offset); // -offset because of icon li
    });

    $('#header nav .icon').click(function(event)
    {
        $nav.toggleClass('max');
    });

    var gdprKey = 'gdprAlertAccepted';
    var gdprAlertAccepted = localStorage.getItem(gdprKey);
    if (! gdprAlertAccepted) {
        var $gdprAlert = $('#gdpr-alert');
        $gdprAlert.removeClass('hidden');
        $gdprAlert.find('.btn').click(function(event)
        {
            localStorage.setItem(gdprKey, "1");
        });
    }
});
