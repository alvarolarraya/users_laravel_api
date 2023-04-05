<?php

namespace App\Infrastructure\Controllers;

use App\Application\UserDataSource\UserDataSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class GetUsersListController extends BaseController
{
    private $userDataSource;

    public function __construct(UserDataSource $userDataSource)
    {
        $this->userDataSource = $userDataSource;
    }

    public function __invoke(): JsonResponse
    {
        $usersList = $this->userDataSource->getAll();

        if (empty($usersList)) {
            return response()->json([], Response::HTTP_OK);
        }
        $response = [];
        foreach ($usersList as $user) {
            array_push($response, json_encode(['id' => $user->getId(),'email' => $user->getEmail()]));
        }
        return response()->json($response, Response::HTTP_OK);
    }
}
