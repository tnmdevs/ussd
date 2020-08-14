<?php

namespace TNM\USSD\Observers;


use TNM\USSD\Models\HistoricalSession;
use TNM\USSD\Models\Session;

class SessionObserver
{
    /**
     * Handle the session "created" event.
     *
     * @param Session $session
     * @return void
     */
    public function created(Session $session)
    {
        $this->createHistoricalRecord($session);
    }

    /**
     * Handle the session "updated" event.
     *
     * @param Session $session
     * @return void
     */
    public function updated(Session $session)
    {
        $this->createHistoricalRecord($session);
    }

    private function createHistoricalRecord(Session $session): void
    {
        HistoricalSession::updateOrCreate(['id' => $session->getKey()], $session->toArray());
    }

}
