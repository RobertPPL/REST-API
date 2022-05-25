<?php

namespace App\DataFixtures;

use App\Entity\CarModel;
use App\Repository\CarBrandRepository;
use App\DataFixtures\CarBrandsFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CarModelFixtures extends Fixture
{
    private CarBrandRepository $carBrandRepository;

    public function __construct(CarBrandRepository $carBrandRepository)
    {
        $this->carBrandRepository = $carBrandRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $audi_models = ['A1', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8'];

        $audi = $this->carBrandRepository->findOneBy(['brand_name' => 'Audi']);

        for($i = 0; $i < count($audi_models); $i++) {
            $car_model = new CarModel();
            $car_model->setCarModel($audi_models[$i]);
            $car_model->setBrand($audi);
            $manager->persist($car_model);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CarBrandsFixtures::class,
        ];
    }
}
