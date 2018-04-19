<div class="rating">
    <h2>Rating</h2>

    <div class="stars enabled clearfix">
        @for ($i = 0; $i < $maxRating; $i++)
            @if ( $i + 0.5 <= $rating) 
                {!! HTML::fontIcon('star') !!}
            @else
                {!! HTML::fontIcon('star', null, null, 'far') !!}
            @endif
        @endfor
    </div>

    <div class="infotext">
        {{ round($rating, 1) }} / {{ $maxRating }}

        @if ($myRating !== null)
            , you rated: {{ $myRating }} / {{ $maxRating }}
        @endif
    </div>

    @if ($myRating === null and user() and user()->hasAccess('ratings', PERM_CREATE))
        <script>
            $(document).ready(function()
            {
                var foreignType = '{{ $foreignType }}';
                var foreignId   = '{{ $foreignId }}';
                
                $('.rating .stars i').mouseenter(function(event) {
                    var index = $(this).index() + 1;
                    $('.rating .stars i:lt(' + index + ')').addClass('active');
                });
                $('.rating .stars i').mouseleave(function(event) {
                    $('.rating .stars i').removeClass('active');
                });

                $('.rating .stars i').click(function()
                {
                    var rating = $(this).index() + 1;

                    $.ajax({
                        url: contentify.baseUrl + 'ratings/store',
                        type: 'POST',
                        data: { 
                            rating: rating, 
                            foreigntype: foreignType, 
                            foreignid: foreignId
                        }
                    }).success(function(data)
                    {
                        $('.rating .stars i').removeClass('fas').addClass('far');
                        $('.rating .stars i:lt(' + rating + ')').removeClass('far')
                            .addClass('fas').addClass('active');

                        $('.rating .stars').removeClass('enabled');

                        $('.rating .stars i').off('mouseenter');
                        $('.rating .stars i').off('mouseleave');
                        $('.rating .stars i').off('click');
                    }).fail(function(response)
                    {
                        contentify.alertRequestFailed(response);
                    });
                });
            });
        </script>
    @endif
</div>