<?php

use Illuminate\Hashing\HasherInterface;

class BotangleHasher implements HasherInterface {

	/**
	 * Default crypt cost factor.
	 *
	 * @var int
	 */
	protected $rounds = 10;

	/**
	 * Hash the given value.
	 *
	 * @param  string  $value
	 * @param  array   $options
	 * @return string
	 *
	 * @throws \RuntimeException
	 */
	public function make($value, array $options = array())
	{
		$cost = isset($options['rounds']) ? $options['rounds'] : $this->rounds;
        $salt = Config::get('auth.cake.salt');
        $hash = sha1($salt . $value);

		if ($hash === false)
		{
			throw new \RuntimeException("Botangle hashing not supported.");
		}

		return $hash;
	}

	/**
	 * Check the given plain value against a hash.
	 *
	 * @param  string  $value
	 * @param  string  $hashedValue
	 * @param  array   $options
	 * @return bool
	 */
	public function check($value, $hashedValue, array $options = array())
	{
        return (self::make($value) == $hashedValue);
	}

	/**
	 * Check if the given hash has been hashed using the given options.
	 *
	 * @param  string  $hashedValue
	 * @param  array   $options
	 * @return bool
	 */
	public function needsRehash($hashedValue, array $options = array())
	{
		return false;
	}

}
