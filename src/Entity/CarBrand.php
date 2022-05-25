<?php

namespace App\Entity;

use App\Entity\CarModel;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CarBrandRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation As Serializer;

/**
 * @ORM\Entity(repositoryClass=CarBrandRepository::class)
 */
class CarBrand
{
    /**
     * @Serializer\Groups({"brand"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Serializer\Groups({"brand"})
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=1, max=255)
     */
    private $brand_name;

    /**
     * @Serializer\Groups({"brand"})
     * @ORM\OneToMany(targetEntity="App\Entity\CarModel", mappedBy="brand", orphanRemoval=true)
     */
    private $cars;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrandName(): ?string
    {
        return $this->brand_name;
    }

    public function setBrandName(string $brand_name): self
    {
        $this->brand_name = $brand_name;

        return $this;
    }

    public function getCars(): array
    {
        return $this->cars->toArray();
    }

    public function addCar(CarModel $car_model): self
    {
        $this->cars[] = $car_model;

        return $this;
    }

    public function removeCar(CarModel $car_model)
    {
        $findElement = function($element) use ($car_model) {
            return $element->getId() === $car_model->getId();
        };
        
        $car = $this->cars->filter($findElement);
        if($car->count() > 0) {
            $car->first()->setBrand(null);
        }
    }
}
