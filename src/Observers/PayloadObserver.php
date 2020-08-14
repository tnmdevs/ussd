<?php

namespace TNM\USSD\Observers;

use TNM\USSD\Models\HistoricalPayload;
use TNM\USSD\Models\Payload;

class PayloadObserver
{
    public function created(Payload $payload)
    {
        $this->createHistoricalRecord($payload);
    }

    public function updated(Payload $payload)
    {
        $this->createHistoricalRecord($payload);
    }

    private function createHistoricalRecord(Payload $payload): void
    {
        HistoricalPayload::updateOrCreate(['id' => $payload->getKey()], $payload->toArray());
    }
}
