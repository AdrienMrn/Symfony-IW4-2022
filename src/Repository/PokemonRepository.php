<?php

namespace App\Repository;

use App\Entity\Pokemon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Pokemon>
 *
 * @method Pokemon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pokemon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pokemon[]    findAll()
 * @method Pokemon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pokemon::class);
    }

    public function save(Pokemon $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Pokemon $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Pokemon[] Returns an array of Pokemon objects
     */
    public function search(Request $request): array
    {
        $queryResult =  $this->createQueryBuilder('p')
            ->orderBy('p.name', 'ASC')
            ->setMaxResults($request->request->get('limit', 10))
        ;

        if ($query = $request->request->get('search')) {
            $queryResult
                ->andWhere('p.name LIKE :val')
                ->setParameter('val', '%' . $query . '%')
            ;
        }

        if ($nature = $request->request->get('nature')) {
            $queryResult
                ->andWhere('p.nature = :nature')
                ->setParameter('nature', $nature)
            ;
        }

        return $queryResult->getQuery()->getResult();
    }

//    public function findOneBySomeField($value): ?Pokemon
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
