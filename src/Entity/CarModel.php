<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CarModelRepository;
use Symfony\Component\Validator\Constraints As Assert;
use Symfony\Component\Serializer\Annotation As Serializer;

/**
 * @ORM\Entity(repositoryClass=CarModelRepository::class)
 */
class CarModel
{
    /**
     * @Serializer\Groups({"car"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Serializer\Groups({"car"})
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=1, max=255)
     */
    private $car_model;

    /**
     * @Serializer\Groups({"model"})
     * @ORM\ManyToOne(targetEntity="App\Entity\CarBrand", inversedBy="cars", fetch="EAGER")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     */
    private $brand;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarModel(): ?string
    {
        return $this->car_model;
    }

    public function setCarModel(string $car_model): self
    {
        $this->car_model = $car_model;

        return $this;
    }

    public function setBrand(?CarBrand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getBrand()
    {
        return is_null($this->brand) ? '' : $this->brand->getBrandName();
    }
}
