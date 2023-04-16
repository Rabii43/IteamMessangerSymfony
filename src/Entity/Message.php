<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\ManyToMany(targetEntity: Users::class, inversedBy: 'messagesSender')]
    private Collection $sender;

    #[ORM\ManyToMany(targetEntity: Users::class, mappedBy: 'ReceivedMessage')]
    private Collection $received;

    public function __construct()
    {
        $this->sender = new ArrayCollection();
        $this->received = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getSender(): Collection
    {
        return $this->sender;
    }

    public function addSender(Users $sender): self
    {
        if (!$this->sender->contains($sender)) {
            $this->sender->add($sender);
        }

        return $this;
    }

    public function removeSender(Users $sender): self
    {
        $this->sender->removeElement($sender);

        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getReceived(): Collection
    {
        return $this->received;
    }

    public function addReceived(Users $received): self
    {
        if (!$this->received->contains($received)) {
            $this->received->add($received);
            $received->addReceivedMessage($this);
        }

        return $this;
    }

    public function removeReceived(Users $received): self
    {
        if ($this->received->removeElement($received)) {
            $received->removeReceivedMessage($this);
        }

        return $this;
    }
}
