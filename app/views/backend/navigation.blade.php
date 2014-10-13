{{-- This is the main navigation of the backend layout --}}
<div class="applemenu">
    {{-- Sub nav 1 --}}
    <div class="silverheader">
        <div class="menu-head daily">
            <span class="hidden">Daily Content</span>
        </div>
    </div>
    <div class="submenu">
        @foreach ($navCategories[1] as $navItem)
            <div class="item"><img src="{{ asset('icons/'.$navItem['icon']) }}" width="16" height="16" alt="Icon">{{ HTML::link($navItem['url'], $navItem['title']) }}</div>
        @endforeach
    </div>
    <div class="menu-bottom"></div>

    {{-- Sub nav 2 --}}
    <div class="silverheader">
        <div class="menu-head perma">
            <span class="hidden">Permanent Content</span>
        </div>
    </div>
    <div class="submenu">
        @foreach ($navCategories[2] as $navItem)
            <div class="item"><img src="{{ asset('icons/'.$navItem['icon']) }}" width="16" height="16" alt="Icon">{{ HTML::link($navItem['url'], $navItem['title']) }}</div>
        @endforeach
    </div>
    <div class="menu-bottom"></div>

    {{-- Sub nav 3 --}}
    <div class="silverheader">
        <div class="menu-head esports">
            <span class="hidden">eSports Content</span>
        </div>
    </div>
    <div class="submenu">               
        @foreach ($navCategories[3] as $navItem)
            <div class="item"><img src="{{ asset('icons/'.$navItem['icon']) }}" width="16" height="16" alt="Icon">{{ HTML::link($navItem['url'], $navItem['title']) }}</div>
        @endforeach
    </div>
    <div class="menu-bottom"></div>

    {{-- Sub nav 4 --}}
    <div class="silverheader">
        <div class="menu-head website">
            <span class="hidden">Website</span>
        </div>
    </div>
        <div class="submenu">
            @foreach ($navCategories[4] as $navItem)
                <div class="item"><img src="{{ asset('icons/'.$navItem['icon']) }}" width="16" height="16" alt="Icon">{{ HTML::link($navItem['url'], $navItem['title']) }}</div>
            @endforeach
        </div>
    <div class="menu-bottom"></div>
</div>