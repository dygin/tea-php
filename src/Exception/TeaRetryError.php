<?php

namespace HttpX\Tea\Exception;

use RuntimeException;

/**
 * Class TeaRetryError
 *
 * @package HttpX\Tea\Exception
 */
class TeaRetryError extends RuntimeException
{
    /**
     * TeaRetryError constructor.
     *
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
