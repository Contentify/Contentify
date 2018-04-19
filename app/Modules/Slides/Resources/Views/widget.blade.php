@if (sizeof($slides) > 0)
    <div id="slider{!! $categoryId !!}" class="slider">
        <div class="slides">
            <ul class="list-inline">
                @foreach ($slides as $slide)
                    <li data-title="{{ $slide->title }}">
                        <a href="{{ $slide->url }}" title="{{ $slide->title }}" target="_blank" style="background-image: url('{!! $slide->uploadPath().$slide->image !!}')">
                            {{ $slide->title }}
                        </a>
                        @if ($slide->text)
                            <div class="text">{{ $slide->text }}</div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="container">
            <ul class="buttons list-inline" title="Switch to">
                @foreach ($slides as $index => $slide)
                    <li>
                        {!! $index + 1 !!}
                    </li>
                @endforeach
            </ul>
        </div>

        <a class="to-left" href="#">{!! HTML::fontIcon('angle-left fa-5x') !!}</a>
        <a class="to-right" href="#">{!! HTML::fontIcon('angle-right fa-5x') !!}</a>
    </div>

    {!! HTML::script('vendor/contentify/slider.js') !!}
    <script>
        $(document).ready(function()
        {
            $('#slider{!! $categoryId !!}').contentifySlider();
        });
    </script>
@endif