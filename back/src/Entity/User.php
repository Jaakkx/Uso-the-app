<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $tokenSpotify;

    /**
     * @ORM\Column(type="text")
     */
    private $token_user;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTokenSpotify(): ?string
    {
        return $this->tokenSpotify;
    }

    public function setTokenSpotify(string $tokenSpotify): self
    {
        $this->tokenSpotify = $tokenSpotify;

        return $this;
    }

    public function getTokenUser(): ?string
    {
        return $this->token_user;
    }

    public function setTokenUser(string $token_user): self
    {
        $this->token_user = $token_user;

        return $this;
    }
}
