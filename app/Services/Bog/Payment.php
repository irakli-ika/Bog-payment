<?php

namespace App\Services\Bog;

use App\Traits\PayloadBuilder;

class Payment
{
    use PayloadBuilder;

    public function __construct(public BogApiClient $bogApiClient)
    {
        $this->resetPayload();
    }
}
