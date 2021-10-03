<?php

namespace App\Entity;

use App\Repository\TicketsRepliesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TicketsRepliesRepository::class)
 */
class TicketsReplies
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
    private $content;

    /**
     * @ORM\OneToOne(targetEntity=Tickets::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $ticket_id;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTicketId(): ?Tickets
    {
        return $this->ticket_id;
    }

    public function setTicketId(Tickets $ticket_id): self
    {
        $this->ticket_id = $ticket_id;

        return $this;
    }
}
