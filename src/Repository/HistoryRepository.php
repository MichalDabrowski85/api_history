<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\History;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<History>
 *
 * @method History|null find($id, $lockMode = null, $lockVersion = null)
 * @method History|null findOneBy(array $criteria, array $orderBy = null)
 * @method History[]    findAll()
 * @method History[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoryRepository extends ServiceEntityRepository
{
    public const RECORDS_PER_PAGE = 10;
    public const DEFAULT_DIRECTION = 'ASC';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, History::class);
    }


    /**
     * @return History[] Returns an array of History objects
     */
    public function findAllRecords(int $page, ?string $order, ?string $direction): array
    {
        $queryBuilder = $this->createQueryBuilder('h');

        if (null !== $order) {
            $queryBuilder->orderBy('h.' . $order, $direction ?: self::DEFAULT_DIRECTION);
        }
        return $queryBuilder
            ->setFirstResult(self::RECORDS_PER_PAGE * ($page - 1))
            ->setMaxResults(self::RECORDS_PER_PAGE)
            ->getQuery()
            ->getResult();
    }
}
