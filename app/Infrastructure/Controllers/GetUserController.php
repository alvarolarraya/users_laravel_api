<?php

namespace App\Infrastructure\Controllers;

use App\Application\UserDataSource\UserDataSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class GetUserController extends BaseController
{
    private $userDataSource;

    public function __construct(UserDataSource $userDataSource)
    {
        $this->userDataSource = $userDataSource;
    }

    public function __invoke(string $userEmail): JsonResponse
    {
        $user = $this->userDataSource->findByEmail($userEmail);
        if (is_null($user)) {
            return response()->json([
                'status' => 'error',
                'message' => 'usuario no encontrado',
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
        ], Response::HTTP_OK);
    }
}
