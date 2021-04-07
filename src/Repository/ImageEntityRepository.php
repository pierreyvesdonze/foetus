<?php

namespace App\Repository;

use App\Entity\ImageEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImageEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageEntity[]    findAll()
 * @method ImageEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageEntity::class);
    }

    /**
     * @return ImageEntity[]
     */
    public function findByType($type): ?array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.pathName LIKE :val')
            ->setParameter('val', '%'.$type.'%')
            ->getQuery()
            ->getResult()
        ;
    }
}
