<?php

namespace Cbr\Collector;

class RedisFactory
{
    public static function getRedisConnection(): \Redis
    {
        $redis = new \Redis();
        $redis->connect('redis');

        return $redis;
    }
}