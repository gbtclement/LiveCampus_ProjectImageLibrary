<?php

namespace App\Repository;

use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Image>
 */
class ImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    public function getAllImages()
    {
        return $this->createQueryBuilder('i')
            ->select('i')
            ->getQuery()
            ->getArrayResult();
    }

    public function getImageById(string $id)
    {
        return $this->createQueryBuilder('i')
            ->select('i')
            ->andWhere('i.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getArrayResult();
    }

    public function getImagesWithHitCount(): array
    {
        return $this->createQueryBuilder('i')
            ->select('i.name, COUNT(h.id) AS hit_count')
            ->innerJoin('i.hits', 'h')
            ->groupBy('i.id')
            ->orderBy('hit_count', 'DESC')
            ->setMaxResults(20)
            ->getQuery()
            ->getArrayResult();
    }


    public function deleteImageById(string $id)
    {
        return $this->createQueryBuilder('i')
            ->delete()
            ->andWhere('i.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->execute();
    }


    //    /**
    //     * @return Image[] Returns an array of Image objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Image
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
