<?php

namespace PubNub\Endpoints\Access;

use PubNub\Endpoints\Access\Grant;
use PubNub\Exceptions\PubNubNotImplementException;
use PubNub\Enums\PNOperationType;


class Revoke extends Grant
{
    protected $read = false;
    protected $write = false;
    protected $manage = false;

    protected $sortParams = true;

    public function read($flag)
    {
        throw new PubNubNotImplementException();
    }

    public function write($flag)
    {
        throw new PubNubNotImplementException();
    }

    public function manage($flag)
    {
        throw new PubNubNotImplementException();
    }

    public function getOperationType()
    {
        return PNOperationType::PNAccessManagerRevoke;
    }

    public function getName()
    {
        return "Revoke";
    }
}