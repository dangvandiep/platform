<?php
/**
 * Created by PhpStorm.
 * User: DiepPK
 * Date: 13/09/2018
 * Time: 10:51
 */

namespace DangVanDiep\Platform\Drivers;

class ChoiGamesDriver extends GameKunlunDriver
{
    /**
     * {@inheritdoc}
     */
    protected $config = [
        'base_url' => 'https://admin.choigames.mobi/api',

        'login'           => '/users/login.json',
        'register'        => '/users/register.json',
        'forgot_password' => '/users/forget_password.json',
        'user_by_token'   => '/Oauth/verifyUser.json',
        'update_profile'  => '/users/update_info.json',
        'change_password' => '/users/change_password.json',
        'reduce_money'    => '/payments/recharge.json',
    ];
}
