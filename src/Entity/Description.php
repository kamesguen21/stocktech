<?php

namespace App\Entity;

use App\Repository\DescriptionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DescriptionRepository::class)
 */
class Description
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $listdate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cik;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bloomberg;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $figi;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lei;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sic;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $industry;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sector;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $marketcap;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $employees;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ceo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=3000, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $exchange;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $symbol;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $exchangeSymbol;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $hq_address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $hq_state;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $hq_country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $updated;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tags;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $similar;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active;

    /**
     * @ORM\OneToOne(targetEntity=Stock::class, inversedBy="description", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $stock;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getListdate(): ?string
    {
        return $this->listdate;
    }

    public function setListdate(?string $listdate): self
    {
        $this->listdate = $listdate;

        return $this;
    }

    public function getCik(): ?string
    {
        return $this->cik;
    }

    public function setCik(?string $cik): self
    {
        $this->cik = $cik;

        return $this;
    }

    public function getBloomberg(): ?string
    {
        return $this->bloomberg;
    }

    public function setBloomberg(?string $bloomberg): self
    {
        $this->bloomberg = $bloomberg;

        return $this;
    }

    public function getFigi(): ?string
    {
        return $this->figi;
    }

    public function setFigi(?string $figi): self
    {
        $this->figi = $figi;

        return $this;
    }

    public function getLei(): ?string
    {
        return $this->lei;
    }

    public function setLei(?string $lei): self
    {
        $this->lei = $lei;

        return $this;
    }

    public function getSic(): ?int
    {
        return $this->sic;
    }

    public function setSic(?int $sic): self
    {
        $this->sic = $sic;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getIndustry(): ?string
    {
        return $this->industry;
    }

    public function setIndustry(?string $industry): self
    {
        $this->industry = $industry;

        return $this;
    }

    public function getSector(): ?string
    {
        return $this->sector;
    }

    public function setSector(?string $sector): self
    {
        $this->sector = $sector;

        return $this;
    }

    public function getMarketcap(): ?string
    {
        return $this->marketcap;
    }

    public function setMarketcap(?string $marketcap): self
    {
        $this->marketcap = $marketcap;

        return $this;
    }

    public function getEmployees(): ?int
    {
        return $this->employees;
    }

    public function setEmployees(?int $employees): self
    {
        $this->employees = $employees;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCeo(): ?string
    {
        return $this->ceo;
    }

    public function setCeo(?string $ceo): self
    {
        $this->ceo = $ceo;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getExchange(): ?string
    {
        return $this->exchange;
    }

    public function setExchange(?string $exchange): self
    {
        $this->exchange = $exchange;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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

    public function getExchangeSymbol(): ?string
    {
        return $this->exchangeSymbol;
    }

    public function setExchangeSymbol(?string $exchangeSymbol): self
    {
        $this->exchangeSymbol = $exchangeSymbol;

        return $this;
    }

    public function getHqAddress(): ?string
    {
        return $this->hq_address;
    }

    public function setHqAddress(?string $hq_address): self
    {
        $this->hq_address = $hq_address;

        return $this;
    }

    public function getHqState(): ?string
    {
        return $this->hq_state;
    }

    public function setHqState(?string $hq_state): self
    {
        $this->hq_state = $hq_state;

        return $this;
    }

    public function getHqCountry(): ?string
    {
        return $this->hq_country;
    }

    public function setHqCountry(?string $hq_country): self
    {
        $this->hq_country = $hq_country;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getSimilar(): ?string
    {
        return $this->similar;
    }

    public function setSimilar(?string $similar): self
    {
        $this->similar = $similar;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(Stock $stock): self
    {
        $this->stock = $stock;

        return $this;
    }
}
