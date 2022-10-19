<?php

namespace App\Repository;

use App\Entity\RechercheVoiture;
use App\Entity\Voiture;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Voiture>
 *
 * @method Voiture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voiture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voiture[]    findAll()
 * @method Voiture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoitureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voiture::class);
    }

    // ici on va creer une fonction permettant de récupérer une query par pagination
    public function findAllWithPagination(RechercheVoiture $rechercheVoiture) : Query
    {
        $req = $this->createQueryBuilder('v');
        // on test si l'utilisateur a renseigné une année min
        if($rechercheVoiture->getMinAnnee()){
            $req = $req->andWhere('v.annee >= :minAnnee')
            ->setParameter('minAnnee', $rechercheVoiture->getMinAnnee());
        }
        // on test si l'utilisateur a renseigné une année max
        if ($rechercheVoiture->getMaxAnnee()) {
            $req = $req->andWhere('v.annee <= :maxAnnee')
            ->setParameter('maxAnnee', $rechercheVoiture->getMaxAnnee());
        }
            // ->orderBy('v.id', 'DESC')
            return $req->getQuery();
    }

    public function add(Voiture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Voiture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Voiture[] Returns an array of Voiture objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Voiture
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
