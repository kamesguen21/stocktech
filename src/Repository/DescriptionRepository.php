<?php

namespace App\Repository;

use App\Entity\Description;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Description|null find($id, $lockMode = null, $lockVersion = null)
 * @method Description|null findOneBy(array $criteria, array $orderBy = null)
 * @method Description[]    findAll()
 * @method Description[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DescriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Description::class);
    }

    /**
     * @param $page
     * @param $limit
     * @return float|int
     * get pagination offset
     */
    public function getOffset($page, $limit)
    {
        $offset = 0;
        if ($page != 0 && $page != 1) {
            $offset = ($page - 1) * $limit;
        }

        return $offset;
    }

    /**
     * @param int $page
     * @param int $size
     * @return mixed
     * return paginated description
     */
    public function getPaginated($page = 0, $size = 10)
    {
        $query = $this->createQueryBuilder('s')->orderBy('s.id', 'ASC');
        $offset = $this->getOffset($page, $size);
        $query->setMaxResults($size)->setFirstResult($offset);

        return $query->getQuery()->getResult();
    }

    /**
     * @param string|null $symbol
     * @return mixed
     * search by symbol
     */
    public function findAllBySymbolLike(?string $symbol)
    {
        return $this->createQueryBuilder('s')
            ->andWhere("s.symbol like :val")
            ->setParameter('val', '%' . $symbol . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string|null $getSymbol
     * @param int|null $getId
     * @return mixed|null
     * get by symbol and not id to verify if symbol is unique
     */
    public function findOneBySymbolAndNotId(?string $getSymbol, ?int $getId)
    {
        try {
            return $this->createQueryBuilder('s')
                ->andWhere("s.symbol = :val  And s.id!=:id")
                ->setParameter('val', $getSymbol)
                ->setParameter('id', $getId)
                ->getQuery()
                ->getResult();
        } catch (NonUniqueResultException $e) {
            return null;

        }
    }

    /**
     * @param string|null $getSymbol
     * @return mixed|null
     * find by symbol
     */
    public function findBySymbol(?string $getSymbol)
    {

        try {
            return $this->createQueryBuilder('s')
                ->andWhere("s.symbol = :val")
                ->setParameter('val', $getSymbol)
                ->getQuery()
                ->getResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }

    }
}
