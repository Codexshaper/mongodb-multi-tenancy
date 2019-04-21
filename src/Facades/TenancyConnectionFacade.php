<?php 
namespace Codexshaper\Tenancy\Facades;

use Illuminate\Support\Facades\Facade;

class TenancyConnectionFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Codexshaper\Tenancy\TenancyConnection';
    }
}