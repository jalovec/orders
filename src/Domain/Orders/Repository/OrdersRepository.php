<?php

namespace App\Domain\Orders\Repository;

use App\Entity\Orders\Orders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Orders>
 *
 * @method Orders|null find($id, $lockMode = null, $lockVersion = null)
 * @method Orders|null findOneBy(array $criteria, array $orderBy = null)
 * @method Orders[]    findAll()
 * @method Orders[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry
    ) {
        parent::__construct($registry, Orders::class);
    }

    public function doFind(int $id): Orders
    {
        $order = $this->find($id);
        if ($order === null) {
            throw new \Exception('Order not found');
        }

        return $order;
    }

    /**
     * @throws \Exception
     */
    public function save(Orders $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Orders $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOrders(?int $id = null, ?string $customerName = null, ?string $status = null): array
    {
        $qb = $this->createQueryBuilder('o');

        // Add conditions dynamically based on provided values
        if ($id > 0) {
            $qb->andWhere('o.id = :id')
                ->setParameter('id', $id);
        }

        if ($customerName !== null && $customerName !== '') {
            $qb->andWhere('o.customerName LIKE :customerName')
                ->setParameter('customerName', '%' . $customerName . '%');
        }

        if ($status !== null && $status !== '') {
            $qb->andWhere('o.status = :status')
                ->setParameter('status', $status);
        }

        // Execute the query and get the results
        return $qb->getQuery()->getResult();
    }
}
