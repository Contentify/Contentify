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
     * Open sidebar category
     */
    var sessionKey = 'backend.sidebar.category';
    var category = sessionStorage.getItem(sessionKey);
    if (category) {
        $('#sidebar .category:eq(' + category + ') .items').css('height', 'auto');
    }

    var naviCategoryLocked = false;
    function activateNaviCategory(index)
    {
        $('#sidebar .category').each(function()
        {
            var $items = $(this).find('.items');
            var height = $items.height();

            if ($(this).index() == index) {
                if (height == 0) {
                    $items.css('height', 'auto');
                    height = $items.height();
                    $items.css('height', 0);

                    $items.animate({height: height}, {duration: 500, queue: false});
                }
            } else {
                if (height > 0) {
                    $items.animate({height: 0}, {duration: 500, queue: false});
                }
            }

            $('#sidebar .category').removeClass('active');
        });
    }

    $('#sidebar .category .head').click(function(event)
    {
        event.preventDefault();

        var index = $(this).parent().index();
        sessionStorage.setItem(sessionKey, index);
        activateNaviCategory(index);
    });

    /*
     * Adjust sidebar height to window height
     */
    $(window).resize(function()
    {
        var $sidebar = $('#sidebar');
        var headerHeight = $('#header').height();


        $sidebar.css('margin-top', headerHeight);
        $sidebar.css('height', $(window).height() - headerHeight);
    });

    $(window).resize();

});