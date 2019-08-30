<?php


namespace TNM\USSD\Http;

use App\Http\Controllers\Controller as BaseController;
use App\Screens\Welcome;
use TNM\USSD\Screen;

class Controller extends BaseController
{
    /**
     * @param Request $request
     * @return mixed|string
     */
    public function __invoke(Request $request)
    {
        if ($request->isInitial() || $request->message == '0') return (new Welcome($request))->render();

        if ($request->message == '#') return $request->getPreviousScreen()->render();

        return Screen::handle($request);
    }

}
