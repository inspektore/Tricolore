<?php
namespace Tricolore\Security\Encoder;

class BCrypt
{
    /**
     * Encode password
     * 
     * @param string $raw_string
     * @param array $options 
     * @return string
     */
    public static function hash($raw_string, array $options = [])
    {
        return password_hash($raw_string, PASSWORD_BCRYPT, $options);
    }

    /**
     * Determine if the hash needs to be rehashed according to the options provided
     *
     * @param string $hash
     * @param array $options
     * @return bool
     */
    public static function needsRehash($hash, array $options = [])
    {
        return password_needs_rehash($hash, PASSWORD_BCRYPT, $options);
    }

    /**
     * Verify hash
     * 
     * @param string $raw_string
     * @param string $hash
     * @return bool
     */
    public static function hashVerify($raw_string, $hash)
    {
        return password_verify($raw_string, $hash);
    }

    /**
     * Hash info
     * 
     * @param string $hash 
     * @return array
     */
    public static function hashInfo($hash)
    {
        return password_get_info($hash);
    }
}
