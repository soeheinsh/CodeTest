<?php

namespace App\Exceptions;

use Exception;

class UserAlreadyLikedPostException extends Exception
{
    public function __construct($message = "You have already liked this post")
    {
        parent::__construct($message);
    }
}
