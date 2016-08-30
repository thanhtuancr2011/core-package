<?php

namespace Comus\Core\Services;

use Laravel\Socialite\Contracts\User as ProviderUser;
use Comus\Core\Models\CustomerModel;

class SocialAccountService
{
    /**
     * Create or get user when user login or create user with social account
     * @author Thanh Tuan <thanhtuancr2011@gmail.com>
     * @param  Object $providerUser Info social account
     * @return Object $customer     Customer to login or create account
     */
    public function createOrGetUser(ProviderUser $providerUser, $provider)
    {
        $account = CustomerModel::whereProvider($provider)->first();

        if ($account) {
            return $account;
        } else {

            $data = [];
            $data['id'] = $providerUser->getId();
            $data['email'] = $providerUser->getEmail();
            $data['avatar'] = $providerUser->getAvatar();
            $data['fullname'] = $providerUser->getName();
            $data['provider'] = $provider;
            $data['remember_token'] = csrf_token();
            $customer = CustomerModel::create($data);

            return $customer;
        }

    }
}