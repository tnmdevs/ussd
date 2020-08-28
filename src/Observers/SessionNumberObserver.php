<?php

namespace TNM\USSD\Observers;

use TNM\USSD\Models\HistoricalSessionNumber;
use TNM\USSD\Models\SessionNumber;

class SessionNumberObserver
{
    public function created(SessionNumber $sessionNumber)
    {
        HistoricalSessionNumber::updateOrCreate(['id' => $sessionNumber->getKey()],
            $sessionNumber->only(['msisdn', 'ussd_session', 'last_screen', 'session_id'])
        );
    }

    public function updated(SessionNumber $sessionNumber)
    {
        HistoricalSessionNumber::updateOrCreate(['id' => $sessionNumber->getKey()],
            $sessionNumber->only(['msisdn', 'ussd_session', 'last_screen', 'session_id'])
        );
    }
}
