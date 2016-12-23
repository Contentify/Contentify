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

    @if ($hasGit) 
        @if ($nextVersion)
            <p>Please create a backup of all files and we recommend that you create a backup of the database as well.</p>

            <div class="well">
                <p><b>Update availabe: {{ $nextVersion }}</b></p> 

                <button class="btn btn-default">Start update</button>
            </div>
        @else
            <div class="alert alert-warning" role="alert">
                <p><b>Update server not reachable or no update found.</b></p>
            </div>
        @endif
    @else 
        <div class="alert alert-warning" role="alert">
            <p><b>Git is not <a href="https://git-scm.com/book/en/v2/Getting-Started-Installing-Git" target="_blank">installed</a> or could not be found!</b></p>

            <p>Please install Git and ensure it is executable.</p>
        </div>
    @endif
{!! Form::close() !!}