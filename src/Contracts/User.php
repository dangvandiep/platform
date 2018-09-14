<?php
/**
 * Created by PhpStorm.
 * User: DiepPK
 * Date: 13/09/2018
 * Time: 16:46
 */

namespace DangVanDiep\Platform\Contracts;

interface User
{
    /**
     * Get the unique identifier for the user.
     *
     * @return string
     */
    public function getId();

    /**
     * Get the username for the user.
     *
     * @return string
     */
    public function getUsername();

    /**
     * Get the e-mail address of the user.
     *
     * @return string
     */
    public function getEmail();

    /**
     * Get the token of the user.
     *
     * @return string
     */
    public function getToken();

    /**
     * Get the phone number of the user.
     *
     * @return string
     */
    public function getPhone();

    /**
     * Get the coin of the user.
     *
     * @return int
     */
    public function getCoin();
}
