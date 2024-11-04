<?php

class PrincipalModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

}