<h1 class="page-title">{{ trans_object('messages') }}</h1>

<div role="tabpanel" style="margin-bottom: 10px">

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" <?php if ($active == 'inbox') echo 'class="active"' ?>>
            <a href="{!! url('messages/inbox') !!}" role="tab">{!! trans('app.object_inbox') !!}</a>
        </li>
        <li role="presentation" <?php if ($active == 'outbox') echo 'class="active"' ?>>
            <a href="{!! url('messages/outbox') !!}" role="tab">{!! trans('app.object_outbox') !!}</a>
        </li>
        <li role="presentation" <?php if ($active == 'create') echo 'class="active"' ?>>
            <a href="{!! url('messages/create') !!}" role="tab">{!! trans('app.send') !!}</a>
        </li>
    </ul>

</div>