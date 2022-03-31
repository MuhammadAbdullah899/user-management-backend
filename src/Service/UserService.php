<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserService
 * @package App\Service
 * @property UtilService utilService
 * @property UserPasswordEncoderInterface passwordEncoder
 * @property UserRepository $userRepository
 */
class UserService
{
    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     * @param UtilService $utilService
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        UserRepository $userRepository,
        UtilService $utilService,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->userRepository = $userRepository;
        $this->utilService = $utilService;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param $email
     * @return User|null
     */
    public function getUserByUserName($email): ?User
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }

    /**
     * @return array
     */
    public function getAllUsers(): array
    {
        $users = $this->userRepository->findAll();
        $response = [];
        foreach ($users as $user) {
            $response[] = $user->toArray();
        }
        return $response;
    }

    /**
     * @param $requestData
     * @return User
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create($requestData): User
    {
        return $this->userRepository->create($requestData, $this->passwordEncoder);
    }
}
