<?php

namespace App\DataFixtures;

use App\Entity\CarBrand;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CarBrandsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $brands = ['Audi', 'BMW', 'Citroen', 'Dacia', 'Fiat'];

        for($i = 0; $i < count($brands); $i++) {
            $car_brand = new CarBrand();
            $car_brand->setBrandName($brands[$i]);
            $manager->persist($car_brand);
        }

        $manager->flush();
    }
}
