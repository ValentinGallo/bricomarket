<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *  fields = {"email"},
 *  message = "L'email est déjà utilisé")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=8, minMessage="Votre mot de passe doit contenir au moins {{ limit }} caractères.")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Ce champ doit être identique à votre mot de passe.")
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $telephone;

    /**
     * @ORM\Column(type="date")
     */
    private $create_time;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="sender", orphanRemoval=true)
     */
    private $messages_send;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="receiver", orphanRemoval=true)
     */
    private $messages_received;

    public function __construct()
    {
        $this->messages_send = new ArrayCollection();
        $this->messages_received = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getCreateTime(): ?\DateTimeInterface
    {
        return $this->create_time;
    }

    public function setCreateTime(\DateTimeInterface $create_time): self
    {
        $this->create_time = $create_time;

        return $this;
    }

    public function eraseCredentials(){

    }

    public function getSalt() {

    }

    public function getRoles() {
        return ['ROLE_USER'];
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessagesSend(): Collection
    {
        return $this->messages_send;
    }

    public function addMessageSend(Message $messages_send): self
    {
        if (!$this->messages_send->contains($messages_send)) {
            $this->messages_send[] = $messages_send;
            $messages_send->setSender($this);
        }

        return $this;
    }

    public function removeMessageSend(Message $messages_send): self
    {
        if ($this->messages_send->contains($messages_send)) {
            $this->messages_send->removeElement($messages_send);
            // set the owning side to null (unless already changed)
            if ($messages_send->getSender() === $this) {
                $messages_send->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessagesReceived(): Collection
    {
        return $this->getMessagesReceived;
    }
}
