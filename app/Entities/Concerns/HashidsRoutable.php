<?php

namespace App\Entities\Concerns;

use Vinkla\Hashids\HashidsManager;

/**
 * Bind a model to a route based on the hash of
 * its id (or other specified key).
 *
 * @package App
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HashidsRoutable
{
    /**
     * Instantiate appropriate Hashids connection
     *
     * @return \Hashids\Hashids
     */
    protected function getHashidsInstance()
    {
        return app(HashidsManager::class)->connection($this->getHashidsConnection());
    }

    /**
     * Determine Hashids connection to use
     *
     * @return null|string
     */
    protected function getHashidsConnection()
    {
        return null;
    }

    /**
     * Encode a parameter
     *
     * @param int $parameter
     * @return string
     */
    protected function encodeParameter($parameter)
    {
        return $this->getHashidsInstance()->encode($parameter);
    }

    /**
     * Decode parameter
     *
     * @param string $parameter
     * @return null|int Decoded value or null on failure
     */
    protected function decodeParameter($parameter)
    {
        if (count($decoded = $this->getHashidsInstance()->decode($parameter)) != 1) {
            // We are expecting a single value from the decode parameter,
            // if none or multiple are returned we just fail
            return null;
        }

        return $decoded[0];
    }

    /**
     * Instruct implicit route binding to use
     * our custom hashed parameter.
     *
     * This is long and crazy to avoid parameters
     * collisions.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'hashidsRoutableHashParam';
    }

    /**
     * Determine which attribute to encode
     *
     * @return string
     */
    public function getRouteHashKeyName()
    {
        return $this->getKeyName();
    }

    /**
     * Get beginning value
     *
     * @return string
     */
    public function getRouteHashKey()
    {
        return $this->getAttribute($this->getRouteHashKeyName());
    }

    /**
     * Encode real parameter to url value for bindings
     *
     * @return string
     */
    public function getHashidsRoutableHashParamAttribute()
    {
        return $this->encodeParameter($this->getRouteHashKey());
    }

    /**
     * Transform a checking by hashed key to real query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function where()
    {
        $params = func_get_args();
        if ($params[0] == $this->getRouteKeyName()) {
            if (is_null($decoded = $this->decodeParameter($params[1]))) {
                // Decoding failed so we return a query with no results
                return parent::whereRaw('0 = 1');
            }

            return parent::where($this->getRouteHashKeyName(), $decoded);
        }
        return parent::where(...$params);
    }
}
