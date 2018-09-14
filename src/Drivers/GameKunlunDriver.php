<?php
/**
 * Created by PhpStorm.
 * User: DiepPK
 * Date: 13/09/2018
 * Time: 10:51
 */

namespace DangVanDiep\Platform\Drivers;

use DangVanDiep\Platform\Contracts\Driver;
use DangVanDiep\Platform\User;

class GameKunlunDriver extends AbstractDriver implements Driver
{
    /**
     * {@inheritdoc}
     */
    protected $config = [
        'base_url' => 'https://plf.gamekunlun.com/api',

        'login'           => '/users/login.json',
        'register'        => '/users/register.json',
        'forgot_password' => '/users/forget_password.json',
        'user_by_token'   => '/Oauth/verifyUser.json',
        'update_profile'  => '/users/update_info.json',
        'change_password' => '/users/change_password.json',
        'reduce_money'    => '/payments/recharge.json',
    ];

    /**
     * {@inheritdoc}
     */
    public function getDefaultQueries()
    {
        return [
            'app' => $this->appkey,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function login($username, $password)
    {
        $params = [
            'username' => $username,
            'password' => $password,
        ];

        $errors = [
            2 => __('Username / Password can not be empty.'),
            5 => __('Username / Password is incorrect.'),
        ];

        return $this->makeRequest('login', $params, $errors, function($result) {
            $this->setUserToken($result['data']['token']);
            return $this;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function register($username, $password, $email)
    {
        $params = [
            'username' => $username,
            'password' => $password,
            'email'    => $email,
            'repass'   => $password,
        ];

        $errors = [
            900 => __('Account already exists.'),
            6   => __('This email address has already been used.'),
        ];

        return $this->makeRequest('register', $params, $errors, function($result) {
            $this->setUserToken($result['data']['token']);
            return $this;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function forgotPassword($email)
    {
        $params = [
            'email' => $email,
        ];

        $errors = [
            2 => __('Email address can not be empty.'),
            4 => __('Email address is not valid.'),
            5 => __('Email address does not exist'),
        ];

        return $this->makeRequest('forgot_password', $params, $errors, function() {
            return $this;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function userByToken($token)
    {
        $this->setUserToken($token);
        return $this->user();
    }

    /**
     * {@inheritdoc}
     */
    public function updateProfile($profile, $token = null)
    {
        $token = $token ?: $this->getUserToken();

        $errors = [
            3 => __('Missing account information.'),
            1 => __('Change account information failed.'),
        ];

        $query = [
            'token' => $token,
        ];

        return $this->makeRequest('update_profile', $profile, $errors, function() {
            return $this;
        }, $query);
    }

    /**
     * {@inheritdoc}
     */
    public function changePassword($oldPassword, $newPassword, $token = null)
    {
        $token = $token ?: $this->getUserToken();

        $params = [
            'username'     => $this->userByToken($token)->getUsername(),
            'password'     => $oldPassword,
            'new_password' => $newPassword,
        ];

        $errors = [
            2 => __('Missing password.'),
            4 => __('User not found.'),
            5 => __('Current password is incorrect.'),
        ];

        return $this->makeRequest('change_password', $params, $errors, function() {
            return $this;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function reduceMoney($money, $token = null)
    {
        $token = $token ?: $this->getUserToken();

        $params = [
            'price' => $money,
            'sign'  => md5($this->appkey . $token . $money),
        ];

        $errors = [
            7 => __('Invalid price.'),
            5 => __('The sign is incorrect.'),
            4 => __('Missing data'),
            6 => __('The money in your account is not sufficient to perform this transaction.'),
        ];

        return $this->makeRequest('reduce_money', $params, $errors, function() {
            return $this;
        }, ['token' => $token]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $userUrl = $this->buildApiUrl('user_by_token', [
            'token' => $token,
        ]);

        $response = $this->getHttpClient()->get($userUrl);

        $user = json_decode($response->getBody(), true);

        if (isset($user['data'])) {
            $user['data']['token'] = $token;
            return $user['data'];
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User(static::class, $this->appkey))->setRaw($user)->map([
            'id'       => $user['account_id'],
            'username' => $user['user_name'],
            'email'    => $user['email'],
            'token'    => $user['token'],
            'phone'    => $user['phone'],
            'coin'     => $user['coin'],
        ]);
    }
}
