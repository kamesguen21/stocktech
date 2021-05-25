<?php

namespace App\Repository;

use App\Entity\Stock;
use App\Entity\Ticker;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ticker|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ticker|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ticker[]    findAll()
 * @method Ticker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TickerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticker::class);
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
     * @param $value
     * @return Stock[] Returns an array of Stock objects
     */
    public function findAllBySymbolLike($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere("s.symbol like :val")
            ->setParameter('val', '%' . $value . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param DateTimeInterface|null $getDate
     * @param string|null $getSymbol
     * @return mixed|null
     * search by date and symbol
     */
    public function findByDateAndSymbol(?DateTimeInterface $getDate, ?string $getSymbol)
    {
        try {
            return $this->createQueryBuilder('s')
                ->andWhere("s.symbol = :val And s.date=:date")
                ->setParameter('val', $getSymbol)
                ->setParameter('date', $getDate)
                ->getQuery()
                ->getResult();
        } catch (NonUniqueResultException $e) {
            return null;

        }
    }

    /**
     * @param DateTimeInterface|null $getDate
     * @param string|null $getSymbol
     * @param int|null $getId
     * @return mixed|null
     * verify (date,symbol) are unique
     */
    public function findByDateAndSymbolAndNotId(?DateTimeInterface $getDate, ?string $getSymbol, ?int $getId)
    {

        try {
            return $this->createQueryBuilder('s')
                ->andWhere("s.symbol = :val And s.date=:date And s.id!=:id")
                ->setParameter('val', $getSymbol)
                ->setParameter('date', $getDate)
                ->setParameter('id', $getId)
                ->getQuery()
                ->getResult();
        } catch (NonUniqueResultException $e) {
            return null;

        }

    }

}
