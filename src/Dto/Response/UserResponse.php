<?php

namespace App\Dto\Response;

class UserResponse
{
    public int $id;
    public string $firstname;
    public string $email;
    /** @var string[] */
    public array $roles = [];

    /**
     * @param string[] $roles
     */
    public function __construct(
        int $id,
        string $firstname,
        string $email,
        array $roles
    ) {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->email = $email;
        $this->roles = $roles;
    }
}
