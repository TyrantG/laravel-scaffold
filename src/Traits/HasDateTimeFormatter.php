<?php

namespace TyrantG\LaravelScaffold\Traits;

use DateTimeInterface;

trait HasDateTimeFormatter
{
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format($this->getDateFormat());
    }
}
