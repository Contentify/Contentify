<h1 class="page-title model-index">{{ trans_object('partners') }}</h1>

@foreach ($partners as $partner)
    <article class="partner">
        <header>
            <h2>{{ $partner->title }}</h2>
        </header>
        <div class="content">
            @if ($partner->image)
                <div class="image">
                    <img src="{!! $partner->uploadPath().$partner->image !!}" alt="{{ $partner->title }}">
                </div>
            @endif

            <p>
                {!! $partner->text !!}
            </p>

            @if ($partner->url)
                <a href="{{ url('partners/url/'.$partner->id) }}" title="{{ $partner->title }}" target="_blank">{{ $partner->url }}</a>
            @endif
        </div>
    </article>
@endforeach