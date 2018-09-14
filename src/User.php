<?php
/**
 * Created by PhpStorm.
 * User: DiepPK
 * Date: 13/09/2018
 * Time: 16:51
 */

namespace DangVanDiep\Platform;


use ArrayAccess;

/**
 * Class User
 *
 * @package DangVanDiep\Platform
 *
 * @mixin \DangVanDiep\Platform\Contracts\Driver
 */
class User implements ArrayAccess, Contracts\User
{
    /**
     * The unique identifier for the user.
     *
     * @var mixed
     */
    public $id;

    /**
     * The user's username.
     *
     * @var string
     */
    public $username;

    /**
     * The user's e-mail address.
     *
     * @var string
     */
    public $email;

    /**
     * The user's token.
     *
     * @var string
     */
    public $token;

    /**
     * The user's phone number.
     *
     * @var string
     */
    public $phone;

    /**
     * The user's coin.
     *
     * @var int
     */
    public $coin;

    /**
     * The user's raw attributes.
     *
     * @var array
     */
    public $user;

    /**
     * Current driver
     */
    protected $driver;

    /**
     * Current appkey
     */
    protected $appkey;

    /**
     * User constructor.
     *
     * @param string $driver
     * @param string $appkey
     */
    public function __construct($driver, $appkey)
    {
        $this->driver = $driver;
        $this->appkey = $appkey;
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the username for the user.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the e-mail address of the user.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the token of the user.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Get the phone number of the user.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Get the coin of the user.
     *
     * @return int
     */
    public function getCoin()
    {
        return $this->coin;
    }

    /**
     * Get the raw user array.
     *
     * @return array
     */
    public function getRaw()
    {
        return $this->user;
    }

    /**
     * Set the raw user array from the provider.
     *
     * @param  array $user
     *
     * @return $this
     */
    public function setRaw(array $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Map the given array onto the user's properties.
     *
     * @param  array $attributes
     *
     * @return $this
     */
    public function map(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->{$key} = $value;
        }
        return $this;
    }

    /**
     * Determine if the given raw user attribute exists.
     *
     * @param  string $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->user);
    }

    /**
     * Get the given key from the raw user.
     *
     * @param  string $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->user[$offset];
    }

    /**
     * Set the given attribute on the raw user array.
     *
     * @param  string $offset
     * @param  mixed  $value
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->user[$offset] = $value;
    }

    /**
     * Unset the given value from the raw user array.
     *
     * @param  string $offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->user[$offset]);
    }

    /**
     * Dynamically pass methods to the user object.
     *
     * @param  string $method
     * @param  array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $driver = (new $this->driver($this->appkey));
        $driver->userToken = $this->getToken();
        return $driver->$method(...$parameters);
    }
}
