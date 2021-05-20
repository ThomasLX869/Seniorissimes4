<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActivityRepository::class)
 */
class Activity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $temperatureMax;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $temperatureMin;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isOutdoor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTemperatureMax(): ?int
    {
        return $this->temperatureMax;
    }

    public function setTemperatureMax(?int $temperatureMax): self
    {
        $this->temperatureMax = $temperatureMax;

        return $this;
    }

    public function getTemperatureMin(): ?int
    {
        return $this->temperatureMin;
    }

    public function setTemperatureMin(?int $temperatureMin): self
    {
        $this->temperatureMin = $temperatureMin;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getIsOutdoor(): ?bool
    {
        return $this->isOutdoor;
    }

    public function setIsOutdoor(bool $isOutdoor): self
    {
        $this->isOutdoor = $isOutdoor;

        return $this;
    }
}
