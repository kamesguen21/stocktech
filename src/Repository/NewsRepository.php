<?php

namespace App\Repository;

use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    /**
     * @param int $page
     * @param int $size
     * @return mixed[]
     * get news page
     */
    public function getPaginated($page = 0, $size = 10)
    {
        $query = $this->createQueryBuilder('s')->orderBy('s.id', 'ASC');
        $offset = $this->getOffset($page, $size);
        $query->setMaxResults($size)->setFirstResult($offset);

        return $query->getQuery()->getArrayResult();
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
     * @return News|null
     * get last added news
     */
    public function findLast(): ?News
    {
        $allProfiles= $this->createQueryBuilder('n')
            ->addOrderBy('n.id','desc')
            ->getQuery()->setMaxResults(1)->setFirstResult(1)
            ->getResult();
        return isset($allProfiles[0])?$allProfiles[0]:null;
    }
}
