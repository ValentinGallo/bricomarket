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

    /**
     * @ORM\OneToMany(targetEntity=Tool::class, mappedBy="Creator", orphanRemoval=true)
     */
    private $tools;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="User", orphanRemoval=true)
     */
    private $reservations;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="User", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=Location::class, mappedBy="User", orphanRemoval=true)
     */
    private $locations;

    public function __construct()
    {
        $this->messages_send = new ArrayCollection();
        $this->messages_received = new ArrayCollection();
        $this->tools = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->locations = new ArrayCollection();
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
        return $this->messages_received;
    }

    /**
     * @return Collection|Tool[]
     */
    public function getTools(): Collection
    {
        return $this->tools;
    }

    public function addTool(Tool $tool): self
    {
        if (!$this->tools->contains($tool)) {
            $this->tools[] = $tool;
            $tool->setCreator($this);
        }

        return $this;
    }

    public function removeTool(Tool $tool): self
    {
        if ($this->tools->contains($tool)) {
            $this->tools->removeElement($tool);
            // set the owning side to null (unless already changed)
            if ($tool->getCreator() === $this) {
                $tool->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setUser($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getUser() === $this) {
                $reservation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getListUserMessages(): Collection
    {
        $users = new ArrayCollection();
        foreach ($this->messages_send as $message){
            if ( !$users->contains($message->getReceiver()) ) {
                $users[] = $message->getReceiver();
            }
        }
        foreach ($this->messages_received as $message){
            if ( !$users->contains($message->getSender()) ) {
                $users[] = $message->getSender();
            }
        }
        return $users;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessageByUser(?User $user): Collection
    {
        $messages = new ArrayCollection();
        foreach ($this->messages_send as $message){
            if ($user == $message->getReceiver()) {
                $messages[] = $message;
            }
        }
        foreach ($this->messages_received as $message){
            if ($user == $message->getSender()) {
                $messages[] = $message;
            }
        }
        return $messages;
    }

    public function userContacted(?User $user): ?bool
    {
        foreach ($this->getListUserMessages() as $userMessage){
            if($user == $userMessage)return true;
        }
        return false;
    }

    /**
     * @return Collection|Location[]
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): self
    {
        if (!$this->locations->contains($location)) {
            $this->locations[] = $location;
            $location->setUser($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->locations->contains($location)) {
            $this->locations->removeElement($location);
            // set the owning side to null (unless already changed)
            if ($location->getUser() === $this) {
                $location->setUser(null);
            }
        }

        return $this;
    }

}
