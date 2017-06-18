<!-- This is the main navigation of the backend layout -->
<div class="navigation">
    <!-- Category 1 -->
    <div class="category"> 
        <a href="#" class="head">
            Daily<span> Content</span>
        </a>
        <div class="items">
            @foreach ($navCategories[1] as $navItem)
                <a class="item" href="{!! url($navItem['url']) !!}" title="{{ $navItem['title'] }}">
                    {!! HTML::fontIcon($navItem['icon']) !!} <span>{{ $navItem['title'] }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Category 2 -->
    <div class="category"> 
        <a href="#" class="head">
            Perma<span>nent Content</span>
        </a>
        <div class="items">
            @foreach ($navCategories[2] as $navItem)
                <a class="item" href="{!! url($navItem['url']) !!}" title="{{ $navItem['title'] }}">
                    {!! HTML::fontIcon($navItem['icon']) !!} <span>{{ $navItem['title'] }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Category 3 -->
    <div class="category">   
        <a href="#" class="head">
            Esports<span> Content</span>
        </a>
        <div class="items">        
            @foreach ($navCategories[3] as $navItem)
                <a class="item" href="{!! url($navItem['url']) !!}" title="{{ $navItem['title'] }}">
                    {!! HTML::fontIcon($navItem['icon']) !!} <span>{{ $navItem['title'] }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Category 4 -->
    <div class="category"> 
        <a href="#" class="head">
            Website
        </a>
        <div class="items">
            @foreach ($navCategories[4] as $navItem)
                <a class="item" href="{!! url($navItem['url']) !!}" title="{{ $navItem['title'] }}">
                    {!! HTML::fontIcon($navItem['icon']) !!} <span>{{ $navItem['title'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>
