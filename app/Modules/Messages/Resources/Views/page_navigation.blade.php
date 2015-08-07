<h1 class="page-title">Messages</h1>

<div role="tabpanel" style="margin-bottom: 10px">

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" <?php if ($active == 'inbox') echo 'class="active"' ?>>
            <a href="{!! url('messages/inbox') !!}" role="tab">{!! trans('messages::inbox') !!}</a>
        </li>
        <li role="presentation" <?php if ($active == 'outbox') echo 'class="active"' ?>>
            <a href="{!! url('messages/outbox') !!}" role="tab">{!! trans('messages::outbox') !!}</a>
        </li>
        <li role="presentation" <?php if ($active == 'create') echo 'class="active"' ?>>
            <a href="{!! url('messages/create') !!}" role="tab">{!! trans('app.create') !!}</a>
        </li>
    </ul>

</div>