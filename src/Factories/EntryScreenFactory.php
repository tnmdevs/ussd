<?php


namespace TNM\USSD\Factories;


use App\Screens\Welcome;
use TNM\USSD\Http\Request;
use TNM\USSD\Screen;

class EntryScreenFactory
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function make()
    {
        if ($this->request->toHomeScreen()) return (new Welcome($this->request))->render();

        if ($this->request->toPreviousScreen()) return $this->request->getPreviousScreen()->render();

        if ($this->request->getScreen()->outOfRange()) return $this->request->getScreen()->render();

        return Screen::handle($this->request);
    }
}
