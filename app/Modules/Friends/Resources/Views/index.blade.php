<h1 class="page-title">{{ trans_object('friends') }} {{ trans('app.of') }} {{ $user->username }}</h1>

@foreach ($user->friends() as $friend)
    @include('friends::friend', compact('friend'))
@endforeach
