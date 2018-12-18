<div class="widget widget-latest-comments">
    <table class="table">
        <thead>
            <tr>
                <th>{{ trans('comments::latest_comments') }} <a href="{{ url('admin/comments') }}" title="{{ trans('app.read_more') }}">{!! HTML::fontIcon('external-link-alt') !!}</a></th>
                <th>{{ trans('app.date') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($comments as $comment)
                <tr>
                    <td title="{{ trans('app.object_user') }} {{ $comment->creator->username  }}: {{ e($comment->plainText()) }}">{!! $comment->plainText(100) !!}</td>
                    <td>{!! $comment->created_at !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
