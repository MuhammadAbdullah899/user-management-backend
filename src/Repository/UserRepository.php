<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends AbstractRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param $requestData
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return User
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create($requestData, UserPasswordEncoderInterface $passwordEncoder): User
    {
        $user = new User();
        $user->setFirstName($requestData['first_name'] ?? null);
        $user->setLastName($requestData['last_name'] ?? null);
        $user->setEmail($requestData['email']);
        $user->setPassword($passwordEncoder->encodePassword($user, $requestData['password']));
        $user->setRoles($requestData['roles'] ?? []);
        $this->persist($user, true);

        return $user;
    }
}
