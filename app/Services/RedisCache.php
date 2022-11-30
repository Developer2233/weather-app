<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class RedisCache
{
    /**
     * @param string $key
     * @return array|null
     */
    public function get(string $key): array|null
    {
        return json_decode(Redis::get($key), true);
    }

    /**
     * @param string $key
     * @param array $value
     */
    public function set(string $key, array $value): void
    {
          json_decode(Redis::set($key, json_encode($value)));
    }
}

