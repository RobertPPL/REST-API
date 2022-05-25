<?php

namespace App\Repository;

use App\Entity\CarBrand;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<CarBrand>
 *
 * @method CarBrand|null find($id, $lockMode = null, $lockVersion = null)
 * @method CarBrand|null findOneBy(array $criteria, array $orderBy = null)
 * @method CarBrand[]    findAll()
 * @method CarBrand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarBrandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarBrand::class);
    }

    public function add(CarBrand $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CarBrand $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
