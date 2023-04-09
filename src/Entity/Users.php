<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[ORM\Table(name: '`users`')]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @return string|null
     */
    public function getUserName(): ?string
    {
        return $this->userName;
    }

    /**
     * @param string|null $userName
     */
    public function setUserName(?string $userName): void
    {
        $this->userName = $userName;
    }

    #[ORM\Column]
    private ?string $userName = null;

    #[ORM\ManyToMany(targetEntity: Message::class, mappedBy: 'sender')]
    private Collection $messagesSender;

    #[ORM\ManyToMany(targetEntity: Message::class, inversedBy: 'received')]
    private Collection $receivedMessage;

    public function __construct()
    {
        $this->messagesSender = new ArrayCollection();
        $this->receivedMessage = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessagesSender(): Collection
    {
        return $this->messagesSender;
    }

    public function addMessagesSender(Message $messagesSender): self
    {
        if (!$this->messagesSender->contains($messagesSender)) {
            $this->messagesSender->add($messagesSender);
            $messagesSender->addSender($this);
        }

        return $this;
    }

    public function removeMessagesSender(Message $messagesSender): self
    {
        if ($this->messagesSender->removeElement($messagesSender)) {
            $messagesSender->removeSender($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getReceivedMessage(): Collection
    {
        return $this->receivedMessage;
    }

    public function addReceivedMessage(Message $receivedMessage): self
    {
        if (!$this->receivedMessage->contains($receivedMessage)) {
            $this->receivedMessage->add($receivedMessage);
        }

        return $this;
    }

    public function removeReceivedMessage(Message $receivedMessage): self
    {
        $this->receivedMessage->removeElement($receivedMessage);

        return $this;
    }
}
