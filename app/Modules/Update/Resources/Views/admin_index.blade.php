{!! Form::open() !!}
    @if ($running)
        <div class="alert alert-warning" role="alert">
            <b>Warning: An update process seems to be running. Starting another update process might cause serious damage!</b>
        </div>
    @endif

    <div class="alert alert-warning" role="alert">
        <b>Warning: This feature is experimental.</b>
    </div>

    @if (ini_get('max_execution_time') < $executionTimeTarget and ini_get('safe_mode'))
        <div class="alert alert-warning" role="alert">
            <p><b>Warning: Execution time is set to a maximum of {{ ini_get('max_execution_time') }} seconds.</b></p>

            <p>This can cause problems if the update exceeds the time limit.</p>
        </div>
    @endif

    @if ($nextVersion)
        @if (ini_get('allow_url_fopen'))
            <p>Please create a backup of all files and we recommend that you create a backup of the database as well.</p>

            <div class="well">
                <p><b>Update availabe: {{ $nextVersion }}</b></p> 

                <button class="btn btn-default">Start update</button>
            </div>
        @else
            <div class="alert alert-danger" role="alert">
                <p><b>Cannot download the updater, because the PHP option <em>allow_url_fopen</em> is set to false.</b></p>
            </div>
        @endif
    @else
        @if ($serverReachable)
            <div class="alert alert-info" role="alert">
                <p><b>No update available, this is the latest version!</b></p>
            </div>
        @else
            <div class="alert alert-danger" role="alert">
                <p><b>Update server not reachable. Please try again or contact the support team.</b></p>
            </div>
        @endif
    @endif
{!! Form::close() !!}