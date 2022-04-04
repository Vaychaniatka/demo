<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\NotificationRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    collectionOperations: ['GET', 'POST'],
    itemOperations: ['GET'],
    denormalizationContext: [
        'groups'                  => ['notification:write'],
        'swagger_definition_name' => 'Write',
    ],
    normalizationContext:   [
        'groups'                  => ['notification:read'],
        'swagger_definition_name' => 'Read',
    ],
    security: 'is_granted(\'ROLE_ADMIN\')',
)]
#[ApiFilter(SearchFilter::class, properties: ['client' => 'exact'])]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['notification:read'])]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'Notifications')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    #[Groups(['notification:read', 'notification:write'])]
    private Client|null $client;

    #[ORM\Column(type: 'string', length: 5, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Choice(['email', 'sms'])]
    #[Groups(['notification:read', 'notification:write'])]
    private string $channel;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    #[Groups(['notification:read', 'notification:write'])]
    private string $content;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['notification:read'])]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['notification:read'])]
    private DateTimeImmutable $updatedAt;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['notification:read'])]
    private bool $isSent = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function setChannel(string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getIsSent(): bool
    {
        return $this->isSent;
    }

    public function setIsSent(bool $isSent): self
    {
        $this->isSent = $isSent;

        return $this;
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->getChannel() === 'sms' && strlen($this->getContent()) > 140) {
            $context->buildViolation('Sms content length should not be longer than 140 symbols')
                ->atPath('content')
                ->addViolation();
        }
    }

    public function getClient1(): ?Client
    {
        return $this->client1;
    }

    public function setClient1(?Client $client1): self
    {
        $this->client1 = $client1;

        return $this;
    }
}
