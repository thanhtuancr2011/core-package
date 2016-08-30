<?php 
namespace Rowboat\Users\Facades;
use Illuminate\Support\Facades\Facade;
class ActLog extends Facade {
    protected static function getFacadeAccessor() { return 'activitilog'; }
}