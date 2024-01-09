<?php

namespace App\Repository;

use App\Entity\Teste;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Teste>
 *
 * @method Teste|null find($id, $lockMode = null, $lockVersion = null)
 * @method Teste|null findOneBy(array $criteria, array $orderBy = null)
 * @method Teste[]    findAll()
 * @method Teste[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TesteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Teste::class);
    }

    public function save(Teste $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Teste $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getByOld($id) : array {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('p')
        ->from(Teste::class,'p')
        ->where('p.date <= CURRENT_DATE()')
        ->where('p.equipeid = '.$id)
        ->orderBy('p.date','ASC');
        return $qb->getQuery()->getResult();
    }

    public function getByNew($id): array {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('p')
            ->from(Teste::class, 'p')
            ->where($qb->expr()->eq('p.status', ':status'))
            ->andWhere($qb->expr()->eq('p.equipeid', ':equipeid'))
            ->orderBy('p.date', 'ASC')
            ->setParameter('status', 'effectué')
            ->setParameter('equipeid', $id);

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Teste[] Returns an array of Teste objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Teste
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
