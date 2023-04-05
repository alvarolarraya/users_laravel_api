<?php

namespace App\Domain;

class User
{
    private int $identificator;
    private string $email;

    public function __construct(int $identificator, string $email)
    {
        $this->identificator = $identificator;
        $this->email = $email;
    }

    public function getId(): int
    {
        return $this->identificator;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
