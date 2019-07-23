<?php

namespace JinseokOh\BootPay;

use Illuminate\Support\Facades\Facade;

class BootPayFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'BootPay';
    }
}
