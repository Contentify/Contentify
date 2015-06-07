@if (sizeof($slides) > 0)
    <div id="slider{{ $categoryId }}" class="slider">
        <div class="slides">
            <ul class="list-inline">
                @foreach ($slides as $slide)
                    <li data-title="{{{ $slide->title }}}">
                        <a href="{{ $slide->url }}" title="{{{ $slide->title }}}" target="_blank">
                            <img src="{{ $slide->uploadPath().$slide->image }}" alt="{{{ $slide->title }}}">
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <a class="to-left" href="#">&lt;</a>
        <a class="to-right" href="#">&gt;</a>
        
        <ul class="buttons list-inline" title="Switch to">
            @foreach ($slides as $index => $slide)
                <li>
                    {{ $index + 1 }}
                </li>
            @endforeach
        </ul>
    </div>

    {{ HTML::script('vendor/contentify/slider.js') }}
    <script>
        $(document).ready(function()
        {
            $('#slider{{ $categoryId }}').contentifySlider();
        });
    </script>
@endif