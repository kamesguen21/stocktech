<?php

namespace App\Entity;

use App\Repository\TickerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TickerRepository::class)
 */
class Ticker
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $date;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    private $open;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    private $hight;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    private $low;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    private $close;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    private $adj_close;

    /**
     * @ORM\Column(type="bigint", nullable=false)
     */
    private $volume;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $symbol;

    /**
     * @ORM\ManyToOne(targetEntity=Stock::class, inversedBy="tickers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $stock;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getOpen(): ?float
    {
        return $this->open;
    }

    public function setOpen(?float $open): self
    {
        $this->open = $open;

        return $this;
    }

    public function getHight(): ?float
    {
        return $this->hight;
    }

    public function setHight(?float $hight): self
    {
        $this->hight = $hight;

        return $this;
    }

    public function getLow(): ?float
    {
        return $this->low;
    }

    public function setLow(?float $low): self
    {
        $this->low = $low;

        return $this;
    }

    public function getClose(): ?float
    {
        return $this->close;
    }

    public function setClose(?float $close): self
    {
        $this->close = $close;

        return $this;
    }

    public function getAdjClose(): ?float
    {
        return $this->adj_close;
    }

    public function setAdjClose(?float $adj_close): self
    {
        $this->adj_close = $adj_close;

        return $this;
    }

    public function getVolume(): ?string
    {
        return $this->volume;
    }

    public function setVolume(?string $volume): self
    {
        $this->volume = $volume;

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(?string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(?Stock $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

}
