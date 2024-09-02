<?php

namespace App\Exceptions;

use Exception;

class UserLikeOwnPostException extends Exception
{
    public function __construct($message = "You cannot like your own post")
    {
        parent::__construct($message);
    }
}
