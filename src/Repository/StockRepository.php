<?php

namespace App\Repository;

use App\Entity\Stock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Stock|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stock|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stock[]    findAll()
 * @method Stock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stock::class);
    }

    /**
     * @param $page
     * @param $size
     * @return Stock[] Returns an array of Stock objects
     */
    public function getPaginated($page = 0, $size = 10)
    {
        $query = $this->createQueryBuilder('s')->orderBy('s.id', 'ASC');
        $offset = $this->getOffset($page, $size);
        $query->setMaxResults($size)->setFirstResult($offset);

        return $query->getQuery()->getResult();
    }

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
     * @return mixed[] Returns an array of Stock objects
     * return array of stocks with descriptions
     */
    public function getPaginatedArray($page = 0, $size = 10)
    {
        $offset = $this->getOffset($page, $size);
        $query = $this->getEntityManager()->createQuery(
            'SELECT s, d
            FROM App\Entity\Stock s
            INNER JOIN s.description d
            WHERE s.id = d.stock'
        )->setMaxResults($size)->setFirstResult($offset);
        return $query->getArrayResult();
    }

    /**
     * @param $value
     * @return mixed
     * return stock by symbol to verify it all ready exists
     */
    public function findOneBySymbol($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.symbol = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $value
     * @param bool $arr
     * @return mixed[]
     * search stock by symbol
     */
    public function findAllBySymbolLike($value,$arr=false)
    {
        if($arr){
            return $this->createQueryBuilder('s')
                ->andWhere("s.symbol like :val")
                ->setParameter('val', '%' . $value . '%')
                ->getQuery()
                ->getArrayResult();
        }
        return $this->createQueryBuilder('s')
            ->andWhere("s.symbol like :val")
            ->setParameter('val', '%' . $value . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string|null $getSymbol
     * @param $id
     * @return mixed|null
     * verify symbol is unique
     */
    public function findOneBySymbolAndNotId(?string $getSymbol, $id)
    {
        try {
            return $this->createQueryBuilder('s')
                ->andWhere('s.symbol = :val and s.id != :valId')
                ->setParameter('val', $getSymbol)
                ->setParameter('valId', $id)
                ->getQuery()
                ->getResult();
        } catch (NonUniqueResultException $e) {
            return null;

        }
    }

}
