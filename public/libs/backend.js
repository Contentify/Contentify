/*
 * Initialize navigation script
 */
ddaccordion.init({
    headerclass: 'silverheader',    // Shared CSS class name of headers group
    contentclass: 'submenu',        // Shared CSS class name of contents group
    revealtype: 'click',            // Clicks / onmouseover on the header reveal? Values: "click", "clickgo", "mouseover"
    mouseoverdelay: 200,            // If revealtype="mouseover", set delay in milliseconds before header expands onMouseover
    collapseprev: true,             // Collapse previous content (so only one open at any time)? true/false
    defaultexpanded: [0],           // index of content(s) open by default [index1, index2, etc] [] denotes no content
    onemustopen: true,              // Specify whether at least one header should be open always (so never all headers closed)
    animatedefault: false,          // Should contents open by default be animated into view?
    persiststate: true,             // Persist state of opened contents within browser session?
    toggleclass: ['', 'selected'],  // Two CSS classes to be applied to the header when it's collapsed and expanded
    togglehtml: ['', '', ''],       // Additional HTML added to the header when it's collapsed and expanded
    animatespeed: 'fast',           // Speed of animation: integer in milliseconds (ie: 200), or keywords "fast" etc.
    oninit: function(headers, expandedindices) 
    { 
        // Custom code to run when headers have initalized
    },
    onopenclose: function(header, index, state, isuseractivated) 
    { 
        // Custom code to run whenever a header is opened or closed
    }
});

$(document).ready(function()
{  
    /*
     * Add datetime picker
     */
    $('.date-time-picker').datetimepicker();
    $('.date-time-picker .add-on').click(function(event)
    {
        event.preventDefault();

        $('.bootstrap-datetimepicker-widget').css({
            position: 'absolute',
            display: 'block',

        });
    });
    $('.bootstrap-datetimepicker-widget .picker-switch a').click(function(event)
    {
        event.preventDefault();

        $('.bootstrap-datetimepicker-widget .collapse').each(function()
        {
            if ($(this).hasClass('in')) {
                $(this).removeClass('in')
            } else {
                $(this).addClass('in')
            }
        });

        var $icon = $(this).find('i');

        if ($icon.hasClass('icon-time')) {
            $icon.removeClass('icon-time');
            $icon.addClass('icon-date');
        } else {
            $icon.addClass('icon-time');
            $icon.removeClass('icon-date');
        }
    });

    /*
     * Activate selecter plugin
     */
    $("select").selecter();

    /*
     * Add quicktip
     */
    $('*[title]').quicktip({
        speed: 400
    });
    $('.hover-ui').parent().quicktip({
        speed: 400
    });

    /*
     * Update date and time
     */
    var timeoutTime = 1000; // ms

    var updateDatetime = function () 
    {
        var now = contentify.formatDate(new Date(), contentify.dateFormat + ' - H:i')
        $('#datetime').text(now);
    }

    updateDatetime();
    var t = setInterval(updateDatetime, timeoutTime);

    /*
     * Make sidebar responsive to scrolling
     */
    $(window).scroll(function () 
    {
        var $header     = $('#header');
        var $sidebar    = $('#sidebar');
        var $footer     = $('#footer');

        // 22px = Sidebar offset
        if ($(window).scrollTop() > $header.height() - 22 && $(window).height() > $sidebar.height()) { 
            if ($(window).scrollTop() + $sidebar.height() + 22 < $footer.get(0).offsetTop) {
                $sidebar.css({ 'position': 'fixed', top: '22px'});
            } else {
                $sidebar.css({ 
                    'position': 'relative', top: $footer.get(0).offsetTop - $sidebar.height() - $header.height()
                });
            }
        } else {
            $sidebar.css({ 'position': 'relative', top: 'auto'});
        }
    });
});