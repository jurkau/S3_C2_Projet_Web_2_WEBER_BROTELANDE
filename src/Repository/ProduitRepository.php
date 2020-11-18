<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function getDetailsProduits()
    {
//        http://symfony.com/doc/current/doctrine/associations.html#joining-related-records
//        $query = $this->getEntityManager()
//            ->createQuery("SELECT count(p.id) as nbProduits, avg(p.prix) as PrixMoyen, min(p.prix) as PrixMin,
//                                  max(p.prix) as PrixMax, t.libelle
//                            FROM App:Produit as p
//                            JOIN App:TypeProduit as t
//                            WHERE p.typeProduit=t.id
//                            GROUP BY t.libelle
//                            ORDER BY p.nom");           // ?????????
//
//        return $query->getResult();


        $qb=$this->createQueryBuilder('p'); // il lui faut une lettre, i : item en base de données donc schéma
        $qb->select('count(p.id) as nbProduits', 'avg(p.prix) as PrixMoyen', 'min(p.prix) as PrixMin')
            ->addSelect('max(p.prix) as PrixMax', 't.libelle')
            ->join( 'App:TypeProduit', 't')
            ->where('p.typeProduit=t.id')
            ->groupBy('t.libelle');
        //    ->addOrderBy('p.nom', 'ASC');     // ?????????
        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Produit[] Returns an array of Produit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Produit
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
