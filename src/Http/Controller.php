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
        if ($request->toHomeScreen()) return (new Welcome($request))->render();

        if ($request->toPreviousScreen()) return $request->getPreviousScreen()->render();

        if ($request->getScreen()->outOfRange()) return $request->getScreen()->render();

        return Screen::handle($request);
    }

}
