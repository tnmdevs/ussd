<?php


namespace TNM\USSD\Http;

use App\Http\Controllers\Controller as BaseController;
use App\Screens\Welcome;
use TNM\USSD\Exceptions\UssdException;
use TNM\USSD\Screen;

class Controller extends BaseController
{
    /**
     * @param Request $request
     * @return mixed|string
     * @throws UssdException
     */
    public function __invoke(Request $request)
    {
        if ($request->invalid())
            throw new UssdException($request, 'The system could not process your request. Please try again later');

        if ($request->toHomeScreen()) return (new Welcome($request))->render();

        if ($request->toPreviousScreen()) return $request->getPreviousScreen()->render();

        if ($request->getScreen()->outOfRange()) return $request->getScreen()->render();

        return Screen::handle($request);
    }

}
