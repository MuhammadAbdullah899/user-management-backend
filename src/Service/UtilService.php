<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class UtilService
 * @package App\Service
 */
class UtilService
{
    const ERROR_RESPONSE_TYPE = 'error';
    const SUCCESS_RESPONSE_TYPE = 'success';

    /**
     * UtilService constructor.
     */
    public function __construct() { }

    /**
     * @param $statusCode
     * @param null $message
     * @param null $data
     * @param string $type
     * @return JsonResponse
     */
    public function makeResponse($statusCode, $message = null, $data = null, string $type = self::ERROR_RESPONSE_TYPE): JsonResponse
    {
        $response['data'] = is_array($data) ? $data : null;

        $response['code'] = $statusCode;

        $response['message'] = !empty($message) ? $message :
            self::SUCCESS_RESPONSE_TYPE;

        $response['status'] = $type;

        return new JsonResponse($response, $statusCode);
    }
}
