<?php
/**
 * Created by PhpStorm.
 * User: DiepPK
 * Date: 12/09/2018
 * Time: 17:39
 */

namespace DangVanDiep\Platform\Contracts;


interface Driver
{
    /**
     * Login to account
     *
     * @param string $username
     * @param string $password
     *
     * @return \DangVanDiep\Platform\Drivers\AbstractDriver
     * @static
     */
    public function login($username, $password);

    /**
     * Register new account
     *
     * @param string $username
     * @param string $password
     *
     * @param string $email
     *
     * @return \DangVanDiep\Platform\Drivers\AbstractDriver
     */
    public function register($username, $password, $email);

    /**
     * Forgot password
     *
     * @param string $email
     *
     * @return \DangVanDiep\Platform\Drivers\AbstractDriver
     */
    public function forgotPassword($email);

    /**
     * Get user info by token
     *
     * @param string $token
     *
     * @return \DangVanDiep\Platform\Drivers\AbstractDriver
     */
    public function userByToken($token);

    /**
     * Update user profile
     *
     * @param array  $profile
     * @param string $token
     *
     * @return \DangVanDiep\Platform\Drivers\AbstractDriver
     */
    public function updateProfile($profile, $token = null);

    /**
     * Change user password
     *
     * @param string $oldPassword
     * @param string $newPassword
     * @param string $token
     *
     * @return \DangVanDiep\Platform\Drivers\AbstractDriver
     */
    public function changePassword($oldPassword, $newPassword, $token = null);

    /**
     * Reduce user money
     *
     * @param int    $money
     * @param string $token
     *
     * @return \DangVanDiep\Platform\Drivers\AbstractDriver
     */
    public function reduceMoney($money, $token = null);
}
