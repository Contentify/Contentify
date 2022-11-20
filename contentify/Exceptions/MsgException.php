<?php

namespace Contentify\Exceptions;

use Illuminate\Http\Request;
use View;
use Response;
use RuntimeException;

/**
 * Usually you do not want your website displaying exceptions or error messages in general
 * when it is in production mode. However, what if you actually want it to behave this way?
 * Then just throw a message exception in any controller method. It will be displayed
 * on a special error page.
 */
class MsgException extends RuntimeException
{

    /**
     * Report the exception.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function render(Request $request)
    {
        return Response::make(View::make('error_message', ['exception' => $this]), 500);
    }

}
