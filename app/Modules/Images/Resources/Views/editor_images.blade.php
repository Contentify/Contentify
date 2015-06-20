@if (! $pure)
    <div class="editor-images">
        <div class="actions">
            {!! Form::text('image') !!}
            {!! Form::button(trans('app.search')) !!}
        </div>
        <div class="images">
@endif
        @foreach($images as $image)
            <div class="image" style="background-image: url('{!! $image->uploadPath().'100/'.$image->image !!}')" data-src="{!! $image->uploadPath().$image->image !!}"></div>
        @endforeach
@if (! $pure)
        </div>
    </div>
@endif