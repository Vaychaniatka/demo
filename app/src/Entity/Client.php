<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ClientRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    denormalizationContext: [
        'groups'                  => ['client:write'],
        'swagger_definition_name' => 'Write',
    ],
    normalizationContext:   [
        'groups'                  => ['client:read'],
        'swagger_definition_name' => 'Read',
    ],
)]
class Client
{
    #[Groups(['client:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[Groups(['client:read', 'client:write'])]
    #[ORM\Column(type: 'string', length: 32)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 32)]
    private string $firstName;

    #[Groups(['client:read', 'client:write'])]
    #[ORM\Column(type: 'string', length: 32)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 32)]
    private string $lastName;

    #[Groups(['client:read', 'client:write'])]
    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 100)]
    private string $email;

    #[Groups(['client:read', 'client:write'])]
    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^\+\d{11,15}$/', message: 'Only phone numbers allowed, i.e. +6434774000')]
    private string $phoneNumber;

    #[Groups(['client:read'])]
    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[Groups(['client:read'])]
    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $updatedAt;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Notification::class, orphanRemoval: true)]
    private $notifications;

    public function __construct()
    {
        $this->notifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): self
    {
        $this->createdAt = new DateTimeImmutable();

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new DateTimeImmutable();

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setClient1($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {

            if ($notification->getClient() === $this) {
                $notification->setClient(null);
            }
        }

        return $this;
    }
}
