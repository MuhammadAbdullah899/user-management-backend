<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use App\Service\UtilService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController
{
    /**
     * @Route(path="api/v1/users", methods={"GET"})
     * @param UtilService $utilService
     * @param UserService $userService
     * @return JsonResponse
     */
    public function listAction(
        UtilService $utilService,
        UserService $userService
    ): JsonResponse
    {
        $response = $userService->getAllUsers();

        return $utilService->makeResponse(
            Response::HTTP_OK,
            "Successfully Listed all users.",
            $response,
            UtilService::SUCCESS_RESPONSE_TYPE
        );
    }

    /**
     * @Route(path="register-user", name="register-user", methods={"POST"})
     * @param Request $request
     * @param UtilService $utilService
     * @param UserService $userService
     * @return JsonResponse
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createAction(
        Request $request,
        UtilService $utilService,
        UserService $userService
    ): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        if (empty($requestData)) {
            $requestData = $request->request->all();
        }
        if (empty($requestData['email'])) {
            return $utilService->makeResponse(Response::HTTP_BAD_REQUEST, "Email is required.", null, UtilService::ERROR_RESPONSE_TYPE);
        }

        if (empty($requestData['password'])) {
            return $utilService->makeResponse(Response::HTTP_BAD_REQUEST, "Password is required.", null, UtilService::ERROR_RESPONSE_TYPE);
        }

        $userByEmail = $userService->getUserByUserName($requestData['email']);

        if ($userByEmail instanceof User) {
            return $utilService->makeResponse(
                Response::HTTP_BAD_REQUEST,
                "User already exists",
                ['email' => $requestData['email']],
                UtilService::ERROR_RESPONSE_TYPE
            );
        }

        $user = $userService->create($requestData);

        return $utilService->makeResponse(
            Response::HTTP_OK,
            "User Successfully Registered.",
            $user->toArray(),
            UtilService::SUCCESS_RESPONSE_TYPE
        );
    }
}
