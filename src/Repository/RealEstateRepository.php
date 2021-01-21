<?php

namespace App\Repository;

use App\Entity\RealEstate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RealEstate|null find($id, $lockMode = null, $lockVersion = null)
 * @method RealEstate|null findOneBy(array $criteria, array $orderBy = null)
 * @method RealEstate[]    findAll()
 * @method RealEstate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RealEstateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RealEstate::class);
    }

    public function findAllWithFilters($surface, $price, $rooms)
    {
        // SELECT * FROM real_estate r
        // WHERE surface > 50 AND price < 100000 AND rooms = 3
        $qb = $this->createQueryBuilder('r') // SELECT * FROM real_estate r
                   // ->where('r.surface > :surface') // WHERE r.surface > 50
                   // ->andWhere('r.price < :price') // AND price < 100000
                   //->andWhere('r.rooms = :rooms') // AND rooms = 3
                   //->setParameter('surface', $surface)
                   ->setParameters([
                       // 'surface' => empty($surface) ? 0 : $surface,
                       // 'price' => empty($price) ? 9999999999999 : $price,
                       // 'rooms' => $rooms,
                   ]);
                   // ->getQuery(); // Récupère la requête construite

        if (!empty($surface)) {
            $qb->andWhere('r.surface > :surface')->setParameter('surface', $surface);
        }

        if (!empty($price)) {
            $qb->andWhere('r.price < :price')->setParameter('price', $price);
        }

        if (!empty($rooms)) { // On conditionne la requête SQL
            $qb->andWhere('r.rooms = :rooms')->setParameter('rooms', $rooms);
        }

        return $qb->getQuery()->getResult(); // Renvoie un tableau de RealEstate
    }

    /**
     * Permet de faire la recherche des biens en BDD
     */
    public function search($query)
    {
        $qb = $this->createQueryBuilder('r')
                   ->where('r.title LIKE :query')
                   ->setParameter('query', '%'.$query.'%');

        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return RealEstate[] Returns an array of RealEstate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RealEstate
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
