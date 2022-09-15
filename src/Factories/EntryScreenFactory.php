<?php


namespace TNM\USSD\Factories;


use TNM\USSD\Http\Request;
use TNM\USSD\Screen;

class EntryScreenFactory
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function make()
    {
        if ($this->request->navigatingHome() || $this->request->toHomeScreen()) {
            $screen = config('ussd.routing.landing_screen');
            /** @var Screen $screen */
            $instance = new $screen($this->request);
            return $instance->render();
        }

        if ($this->request->toPreviousScreen()) return $this->request->getPreviousScreen()->render();

        if ($this->request->getScreen()->outOfRange()) return $this->request->getScreen()->render();

        return Screen::handle($this->request);
    }
}
