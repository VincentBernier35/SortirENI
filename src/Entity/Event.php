<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startTime = null;

    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $deadLine = null;

    #[ORM\Column]
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
    private ?User $promoter = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'events_registered')]
    private Collection $users_events;

    /**
     * @param Collection $users_events
     */

    public function __construct(Collection $users_events)
    {
        $this->users_events = $users_events;

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

    public function getStartTime(): ?\DateTimeImmutable
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeImmutable $startTime): static
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

    public function setDeadLine(\DateTimeInterface $deadLine): static
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
}
