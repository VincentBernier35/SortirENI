<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\GreaterThan('today UTC+2', message:'La date de l\'évènement doit être dans futur !' )]
    private ?\DateTimeInterface $startTime = null;

    #[ORM\Column]
    #[Assert\GreaterThan(30, message:'La duration de l\'évènement doit durer au moins 30 minutes !' )]
    private ?int $duration = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\GreaterThan('today UTC+2', message:'La date limite d\'inscription doit être dans futur !')]
    #[Assert\LessThan(propertyPath:'startTime', message:'La date limite d\'inscription doit être inférieur à la date de l\'évènement !' )]
    private ?\DateTimeInterface $deadLine = null;

    #[ORM\Column]
    #[Assert\GreaterThan(1, message:'Le numbre des places doit être au moins 2 !')]
    private ?int $placeMax = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $info = null;

    #[ORM\ManyToOne(inversedBy: 'event')]
    private ?Place $place = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?State $state = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    private ?Site $site = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?User $promoter = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'events_registered')]
    private Collection $users_events;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $cancelReason = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $Image = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Comment::class)]
    private Collection $comments;

    /**
     * @param Collection $users_events
     */
    public function __construct()
    {
        $this->users_events = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDeadLine(): ?\DateTimeInterface
    {
        return $this->deadLine;
    }

    public function setDeadLine(\DateTimeInterface $deadLine): self
    {
        $this->deadLine = $deadLine;

        return $this;
    }

    public function getPlaceMax(): ?int
    {
        return $this->placeMax;
    }

    public function setPlaceMax(int $placeMax): static
    {
        $this->placeMax = $placeMax;

        return $this;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(?string $info): static
    {
        $this->info = $info;

        return $this;
    }

    public function getEvents(): ?Place
    {
        return $this->events;
    }

    public function setEvents(?Place $events): static
    {
        $this->events = $events;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): static
    {
        $this->site = $site;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getPromoter(): ?User
    {
        return $this->promoter;
    }

    public function setPromoter(?User $promoter): static
    {
        $this->promoter = $promoter;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsersEvents(): Collection
    {
        return $this->users_events;
    }

    public function addUsersEvent(User $usersEvent): static
    {
        if (!$this->users_events->contains($usersEvent)) {
            $this->users_events->add($usersEvent);
        }

        return $this;
    }

    public function removeUsersEvent(User $usersEvent): static
    {
        $this->users_events->removeElement($usersEvent);

        return $this;
    }

    public function getCancelReason(): ?string
    {
        return $this->cancelReason;
    }

    public function setCancelReason(?string $cancelReason): static
    {
        $this->cancelReason = $cancelReason;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(?string $Image): static
    {
        $this->Image = $Image;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setEvent($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getEvent() === $this) {
                $comment->setEvent(null);
            }
        }

        return $this;
    }
}
