<?php

namespace Cbr\Sdk;

class RedisFactory
{
    public static function getRedisConnection(): \Redis
    {
        $redis = new \Redis();
        $redis->connect('redis');

        return $redis;
    }
}