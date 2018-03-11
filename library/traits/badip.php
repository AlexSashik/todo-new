<?php

namespace traits;
/**
 * Trait BadIp contains list of ips which do not have permission to visit this web-site
 * @package traits
 */
trait BadIp
{
    /**
     * @var array
     */
    static $badip = [
      '127.0.0.2',
    ];
}