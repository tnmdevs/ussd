<?php

namespace TNM\USSD\Observers;


use TNM\USSD\Models\HistoricalSession;
use TNM\USSD\Models\Session;
use TNM\USSD\Models\SessionNumber;

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

        $this->createSessionNumber($session);
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
        $this->createSessionNumber($session);
    }

    private function createHistoricalRecord(Session $session): void
    {
        HistoricalSession::updateOrCreate(['id' => $session->getKey()], $session->toArray());
    }

    /**
     * @param Session $session
     */
    protected function createSessionNumber(Session $session): void
    {
        SessionNumber::updateOrCreate(['msisdn' => $session->{'msisdn'}, 'ussd_session' => $session->{'session_id'}], [
            'last_screen' => $session->{'state'},
            'msisdn' => $session->{'msisdn'},
            'ussd_session' => $session->{'session_id'},
        ]);
    }

}
