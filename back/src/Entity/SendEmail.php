<?php

namespace App\Entity;

use App\Repository\SendEmailRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SendEmailRepository::class)
 */
class SendEmail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $destinationEmail;

    /**
     * @ORM\ManyToOne(targetEntity=NotionPage::class, inversedBy="sendEmails")
     * @ORM\JoinColumn(nullable=false)
     */
    private $notionPage;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sendEmails")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDestinationEmail(): ?string
    {
        return $this->destinationEmail;
    }

    public function setDestinationEmail(string $destinationEmail): self
    {
        $this->destinationEmail = $destinationEmail;

        return $this;
    }

    public function getNotionPage(): ?NotionPage
    {
        return $this->notionPage;
    }

    public function setNotionPage(?NotionPage $notionPage): self
    {
        $this->notionPage = $notionPage;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
