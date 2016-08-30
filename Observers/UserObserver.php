<?php 
namespace Comus\Core\Observers;

use Comus\Core\Services\CacheService;

class UserObserver{

    public function saving($model)
    {
        //
    }
    public function saved($model)
    {
        if (CacheService::has('users'))
        {
            CacheService::forget('users'); 
        }

        if (CacheService::has('usersManager'))
        {
            CacheService::forget('usersManager'); 
        }
    }
    public function deleted($model)
    {
        if (CacheService::has('users'))
        {
            CacheService::forget('users');
        }

        if (CacheService::has('usersManager'))
        {
            CacheService::forget('usersManager'); 
        }
    }
    public function updated($model)
    {
        if (CacheService::has('users'))
        {
            CacheService::forget('users');
        }

        if (CacheService::has('usersManager'))
        {
            CacheService::forget('usersManager'); 
        }
    }
}