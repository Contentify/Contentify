var contentify;

$(document).ready(function()
{
    function Contentify() {
        var frameWork = this;

        /*
         * Initizalize meta variables
         */
        this.baseUrl    = $('meta[name="base-url"]').attr('content') + '/';
        this.csrfToken  = $('meta[name="csrf-token"]').attr('content');
        this.locale     = $('meta[name="locale"]').attr('content');
        this.dateFormat = $('meta[name="date-format"]').attr('content');

        /*
         * Add CSRF token
         */
        jQuery.ajaxPrefilter(function(options, request, xhr) 
        {
            if (! xhr.crossDomain) {
                options.data += '&_token=' + frameWork.csrfToken;
            }
        });

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

        /*
         * Add delete confirm dialogue
         */
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

        /*
         * Add confirm dialogue
         */
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

        var now = new Date();
        console.log(this.formatDate(now, 'Y-m-d'));

    };

    contentify = new Contentify();

    console.log(contentify.baseUrl);
});