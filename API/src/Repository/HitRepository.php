<?php

namespace App\Repository;

use App\Entity\Hit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Image;

/**
 * @extends ServiceEntityRepository<Hit>
 */
class HitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hit::class);
    }

    public function deleteHitByImageId(string $id)
    {
        return $this->createQueryBuilder('h')
            ->delete()
            ->andWhere('h.image = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->execute();
    }

    public function addHit(Image $image): void
    {
        $hit = new Hit();
        $hit->setImage($image);
        $hit->setCreatedAt(new \DateTimeImmutable());
    
        $this->getEntityManager()->persist($hit);
        $this->getEntityManager()->flush();
    }
    

//    /**
//     * @return Hit[] Returns an array of Hit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Hit
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
