<h1 class="page-title model-index">{{ trans_object('partners') }}</h1>

@forelse ($partners as $partner)
    <article class="partner">
        <header>
            <h2>{{ $partner->title }}</h2>
        </header>
        <div class="content">
            @if ($partner->image)
                <div class="image">
                    <img class="img-responsive" src="{!! $partner->uploadPath().$partner->image !!}" alt="{{ $partner->title }}">
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
@empty
    <p>{{ trans('app.nothing_here') }}</p>
@endforelse
