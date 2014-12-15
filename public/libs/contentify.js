var contentify;

$(document).ready(function()
{
    contentify = new function() 
    {
        var framework = this;

        /*
         * Initizalize meta variables
         */
        this.baseUrl    = $('meta[name="base-url"]').attr('content') + '/';
        this.assetUrl   = $('meta[name="asset-url"]').attr('content') + '/';
        this.csrfToken  = $('meta[name="csrf-token"]').attr('content');
        this.locale     = $('meta[name="locale"]').attr('content');
        this.dateFormat = $('meta[name="date-format"]').attr('content');

        this.urlParam = function(name) 
        {
            return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)')
                .exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null
        }

        /*
         * Add CSRF token
         */
        jQuery.ajaxPrefilter(function(options, request, xhr) 
        {
            if (! xhr.crossDomain) {
                options.data += '&_token=' + framework.csrfToken;
            }
        });

        /*
         * Template Manager
         */
        this.templateManager = new function() 
        {
            this.templates = { };

            /*
             * Adds a template
             */
            this.add = function(name, template) 
            {
                this.templates[name] = template;
            };

            /*
             * Returns true if the template exists
             */
            this.has = function(name) 
            {
                return (typeof this.templates[name] !== 'undefined');
            };

            /*
             * Returns a rendered template
             */
            this.get = function(name, vars) 
            {
                if (typeof this.templates[name] === 'undefined') {
                    return;
                }

                if (typeof vars !== 'object') {
                    return this.templates[name];
                }

                // Note: It's possible to use {{ }} but it will confuse Blade.
                return this.templates[name].replace(/%%(.*?)%%/g, function() 
                {
                    return vars[arguments[1]];
                });
            };
        };

        /*
         * Numeric inputs
         */
        $('.numeric-input').keypress(function()
        {
            if (! isNaN($(this).val())) {
                $(this).attr('valid-value', $(this).val());
            }
        }).focusout(function()
        {
            if (isNaN($(this).val())) {
                var valid = $(this).attr('valid-value');

                if (valid) {
                    $(this).val(valid);
                } else {
                    $(this).val(0);
                }
            }
        });

        /**
         * Creates an HTML alert inside the alert area.
         *
         * @param string type     The alert type e. g. sucess, alert
         * @param string text     The text that should be shown
         * @param bool   clearAll If true, this will hide all other alerts
         * @return void
         */
        this.alert = function (type, title, clearAll)
        {
            if (! framework.templateManager.has('alert')) {
                framework.templateManager.add('alert', 
                    '<div class="alert alert-%%type%%">\
                    <button type="button" class="close">&times;</button>\
                    <h4>%%title%%</h2>\
                    </div>'
                );
            }

            var $template = $(framework.templateManager.get('alert', {
                type: type,
                title: title
            }));

            $template.find('button').click(function()
            {
                $(this).parent().remove();
            });

            var $alertArea = $('.alert-area');

            if (clearAll) {
                $alertArea.html('');
            }
            $alertArea.append($template);

            $(window).scrollTop($alertArea.offset().top);
        };

        this.alertSuccess = function (title, clearAll)
        {
            framework.alert('success', title, clearAll);
        }

        this.alertWarning = function (title, clearAll)
        {
            framework.alert('warning', title, clearAll);
        }

        this.alertError = function (title, clearAll)
        {
            framework.alert('error', title, clearAll);
        }

        this.alertRequestFailed = function (response)
        {
            var text = 'Error: Request failed. ';

            if (response.status) {
                text += 'Code: ' + response.status + '. ';
            }

            if (response.statusText) {
                text += 'Message: "' + response.statusText + '"';
            }

            framework.alertError(text);
        }

        /*
         * Add delete confirm dialogue
         */
        if ($.boxer) {
            $('body').append($('<div />').boxer({})); // Bugfix or Boxer won't open due to a JS error
            $('*[data-confirm-delete]').click(function(event)
            {
                event.preventDefault();
                event.stopPropagation();

                var $self = $(this);

                var $ui = $('<div class="boxer-confirm"><h2>Delete this item?</h2></div>')
                    .append(
                        $('<button>').text('Yes').click(function()
                        {
                            window.location = $self.attr('href');
                        })
                    ).append(
                        $('<button>').text('No').click(function()
                        {
                            $.boxer('close');
                        })
                    );
                $.boxer($ui);
            });
        }

        /*
         * Add confirm dialogue
         */
        if ($.boxer) {
            $('*[data-confirm]').click(function(event)
            {       
                event.preventDefault();
                event.stopPropagation();

                var $self = $(this);
                var message = $self.attr('data-confirm');

                var $ui = $('<div class="boxer-confirm"><h2>Execute this action?</h2><p>' + message + '</p></div>')
                    .append(
                        $('<button>').text('Yes').click(function()
                        {
                            window.location = $self.attr('href');
                        })
                    ).append(
                        $('<button>').text('No').click(function()
                        {
                            $.boxer('close');
                        })
                    );
                $.boxer($ui);
            });
        }

        /*
         * Spoiler
         */
        $('.spoiler').wrap('<div class="spoiler-wrapper">');
        $('.spoiler-wrapper').prepend($('<div class="spoiler-toggle">')
            .click(function()
            {
                var $wrapper = $(this).parent();
                var $spoiler = $wrapper.find('.spoiler');

                $wrapper.toggleClass('show');
            })
        );

        /*
         * Content filter UI: Set values
         */
        var filter = this.urlParam('filter');

        if (filter) {
            var filters = filter.split(',');
            var filterNames = [];
            var filterValues = [];

            $.each(filters, function(key, value)
            {
                var values = value.split('-');

                filterNames.push(values[0]);

                if (values.length == 2) {
                    filterValues.push(values[1]);
                } else {
                    filterValues.push('');
                }
            });

            $('.content-filter-ui > *').each(function()
            {
                var name = $(this).get(0).name;
                var key = $.inArray(name, filterNames);
                if (key > -1) {
                    var val = filterValues[key];
                    if ($(this).is(':checkbox')) {
                        $(this).prop('checked', val);
                    } else {
                        $(this).val(val);
                    }                    
                }
            })
        }

        /*
         * Content filter UI: "On change" event handler
         */
        $('.content-filter-ui > *').change(function() 
        {
            var filterUiUrl = $('.content-filter-ui').attr('data-url');
            var filter      = '';

            $('.content-filter-ui select').each(function() 
            {
                if ($(this).val() != '') {
                    if (filter != '') filter += ',';
                    filter += $(this).attr('name') + '-' + $(this).val();
                }
            });

            $('.content-filter-ui input[type=checkbox]').each(function() 
            {
                if ($(this).prop('checked')) {
                    if (filter != '') filter += ',';
                    filter += $(this).attr('name') + '-' + $(this).val();
                }
            });

            if (filter) {
                var pos = filterUiUrl.indexOf('?');

                if (pos != -1) {
                    filterUiUrl += '&filter=' + filter;
                } else {
                    filterUiUrl += '?filter=' + filter;
                }
            }

            window.location = filterUiUrl;
        });

        /**
         * Returns a string formatted according to the given format string.
         * According to http://www.php.net/manual/en/function.date.php
         * 
         * @param  {Date}   date   The date
         * @param  {String} format The format string
         * @return {String}
         */
        this.formatDate = function (date, format)
        {
            var newDate     = '';

            var year        = date.getFullYear();
            var yearShort   = year % 100;
            var month       = date.getMonth() + 1;
            var day         = date.getDate();
            var hour        = date.getHours();
            var hourShort   = (hour < 13) ? hour % 13 : hour % 12;
            var minute      = date.getMinutes();
            var second      = date.getSeconds();
            var timestamp   = date.getTime();

            for (var i = 0; i < format.length; i++) {
                var symbol = format.charAt(i);

                switch (symbol) {
                    case 'a':
                        newDate += (hour < 12 ) ? 'am' : 'pm';
                        break;
                    case 'A':
                        newDate += (hour < 12 ) ? 'AM' : 'PM';
                        break;
                    case 'Y':
                        newDate += year;
                        break;
                    case 'y':
                        if (yearShort < 10) newDate += '0';
                        newDate += yearShort;
                        break;     
                    case 'm':
                        if (month < 10) newDate += '0';
                        newDate += month;
                        break;
                    case 'n':
                        newDate += month;
                        break;
                    case 'd':
                        if (day < 10) newDate += '0';
                        newDate += day;
                        break;
                    case 'h':
                        if (hour > 0 && hourShort < 10) newDate += '0';
                        newDate += (hour == 0) ? 12 : hourShort;
                        break;
                    case 'H':
                        if (hour < 10) newDate += '0';
                        newDate += hour;
                        break;
                    case 'g':
                        newDate += (hour == 0) ? 12 : hourShort;
                        break;
                    case 'G':
                        newDate += hour;
                        break;
                    case 'i':
                        if (minute < 10) newDate += '0';
                        newDate += minute;
                        break;
                    case 'j':
                        newDate += minute;
                        break;      
                    case 's':
                        if (second < 10) newDate += '0';
                        newDate += second;
                        break;
                    case 'U':
                        newDate += timestamp;
                        break;
                    default:
                        newDate += symbol;
                }
            }

            return newDate;
        }
    };    
});