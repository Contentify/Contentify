$(document).ready(function()
{  
    /*
     * Open sidebar category
     */
    var categorySessionKey = 'backend.sidebar.category';
    var moduleSessionKey = 'backend.sidebar.module';

    var category = sessionStorage.getItem(categorySessionKey);
    if (! category) {
        category = 0;
    }
    $('#sidebar .category:eq(' + category + ')').addClass('initial-active');
    $('#sidebar .category:eq(' + category + ') .items').css('height', 'auto');

    var module = sessionStorage.getItem(moduleSessionKey);
    if (module) {
        $('#sidebar .category:eq(' + category + ') .item:eq(' + module + ')').addClass('active');
    }  

    /*
     * Make sidebar collapsible
     */
    var naviCategoryLocked = false;
    function activateNaviCategory(index)
    {
        var duration = 200;

        $('#sidebar .category').each(function()
        {
            var $items = $(this).find('.items');
            var height = $items.height();

            if ($(this).index() == index) {
                $(this).addClass('active');
                if (height == 0) {
                    $items.css('height', 'auto');
                    height = $items.height();
                    $items.css('height', 0);

                    $items.animate({height: height}, {duration: duration, queue: false});
                }
            } else {
                $(this).removeClass('active');
                if (height > 0) {
                    $items.animate({height: 0}, {duration: duration, queue: false});
                }
            }

            $('#sidebar .category').removeClass('initial-active');
        });
    }

    $('#sidebar .category .head').click(function(event)
    {
        event.preventDefault();

        var index = $(this).parent().index();
        sessionStorage.setItem(categorySessionKey, index);
        activateNaviCategory(index);
    });

    $('#sidebar .category .item').click(function()
    {
        sessionStorage.setItem(moduleSessionKey, $(this).index());
    });

    var stateSessionKey = 'backend.sidebar.state';

    var state = sessionStorage.getItem(stateSessionKey);

    if (state) {
        $('#sidebar').addClass('max');
    }

    $('#sidebar .hamburger').click(function(event)
    {
        event.preventDefault();

        state = sessionStorage.getItem(stateSessionKey);

        if (state) {
            state = '';
            $('#sidebar').removeClass('max');
        } else {
            state = 1;
            $('#sidebar').addClass('max');
        }

        sessionStorage.setItem(stateSessionKey, state);
    });

    $('#footer .top').click(function(event)
    {
        event.preventDefault();

        contentify.scrollTop();
    });

    /*
     * Adjust sidebar height to window height
     * and set footer position
     */
    $(window).resize(function()
    {
        var $sidebar        = $('#sidebar');
        var headerHeight    = $('#header').height();
        var sidebarHeight   = $(window).height() - headerHeight;

        $sidebar.css('height', sidebarHeight);

        var $content = $('#content');
        if (parseInt($content.css('marginLeft')) > 0) {
            $content.css('minHeight', sidebarHeight - $('#footer').outerHeight() + parseInt($content.css('padding-top')));    
        } else {
            $content.css('minHeight', 'auto');
        }
        
    });

    $(window).resize();

    /*
     * Display hover UI via Bootstrap tooltip
     */
    $('.hover-ui').each(function()
    {
        var $parent = $(this).parent();

        if ($parent.is('td')) {
            $parent.attr('data-toggle',     'tooltip');
            $parent.attr('data-html',       'true');
            $parent.attr('data-placement',  'top');
            $parent.attr('data-trigger',    'hover');
            $parent.attr('data-title',      $(this).html());
            $parent.attr('data-container',  'body');

            $parent.tooltip();
        }
    });
    
});