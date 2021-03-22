<?php

namespace App\StopFactor;

use Symfony\Component\Validator\Constraints as Assert;

class GetContact
{
    /**
     * @Assert\Email
     */
    public $email;

    /**
     * @Assert\Length(4)
     */
    public $confirmation;
}