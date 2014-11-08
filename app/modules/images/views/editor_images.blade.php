@if (! $pure)
<div class="boxer-plain editor-images">
    <div class="actions">
        {{ Form::text('image') }}
        {{ Form::button('submit') }}
    </div>
@endif
    <div class="images">
        @foreach($images as $image)
        <div class="image" style="background-image: url('{{ $image->uploadPath().'100/'.$image->image }}')" data-src="{{ $image->uploadPath().$image->image }}"></div>
        @endforeach
    </div>
@if (! $pure)
</div>
@endif