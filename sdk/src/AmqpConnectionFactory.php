<?php

namespace Cbr\Sdk;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class AmqpConnectionFactory
{
    public static function getStream(): AMQPStreamConnection
    {
        return new AMQPStreamConnection('rabbit', 5672, 'guest', 'guest');
    }
}