<div class="widget widget-latest-comments">
    <table class="table">
        <thead>
            <tr>
                <th>{{ trans('comments::latest_comments') }}</th>
                <th>{{ trans('app.date') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($comments as $comment)
            <tr>
                <td title="{{ $comment->plainText() }}">{{ $comment->plainText(100) }}</td>
                <td>{{ $comment->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>