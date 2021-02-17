<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 */
class Booking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"booking"})
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"booking"})
     */
    private $endDate;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="booking", fetch="EAGER")
     * @Groups({"booking"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $pro;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="booking", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"booking"})
     */
    private $client;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $start_date): self
    {
        $this->startDate = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $end_date): self
    {
        $this->endDate = $end_date;

        return $this;
    }

    public function getPro(): ?User
    {
        return $this->pro;
    }

    public function setPro(?User $pro): self
    {
        $this->pro = $pro;

        return $this;
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

}
