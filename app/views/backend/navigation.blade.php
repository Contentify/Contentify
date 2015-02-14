{{-- This is the main navigation of the backend layout --}}
<div class="navigation">
    {{-- Category 1 --}}
    <div class="category"> 
        <a href="#" class="head">
            Daily Content
        </a>
        <div class="items">
            @foreach ($navCategories[1] as $navItem)
                <a class="item" href="{{ url($navItem['url']) }}">
                    {{ HTML::fontIcon($navItem['icon']) }} {{{ $navItem['title'] }}}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Category 2 --}}
    <div class="category"> 
        <a href="#" class="head">
            Permanent Content
        </a>
        <div class="items">
            @foreach ($navCategories[2] as $navItem)
                <a class="item" href="{{ url($navItem['url']) }}">
                    {{ HTML::fontIcon($navItem['icon']) }} {{{ $navItem['title'] }}}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Category 3 --}}
    <div class="category">   
        <a href="#" class="head">
            eSports Content
        </a>
        <div class="items">        
            @foreach ($navCategories[3] as $navItem)
                <a class="item" href="{{ url($navItem['url']) }}">
                    {{ HTML::fontIcon($navItem['icon']) }} {{{ $navItem['title'] }}}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Category 4 --}}
    <div class="category"> 
        <a href="#" class="head">
            Website
        </a>
        <div class="items">
            @foreach ($navCategories[4] as $navItem)
                <a class="item" href="{{ url($navItem['url']) }}">
                    {{ HTML::fontIcon($navItem['icon']) }} {{{ $navItem['title'] }}}
                </a>
            @endforeach
        </div>
    </div>
</div>