<?php

namespace App\Http\Controllers\User;

use App\Repository\User\APIPostRepositoryInterface;
use Illuminate\Http\Request;

class APIPostController
{
    protected APIPostRepositoryInterface $apiPostRepository;
    public function __construct(APIPostRepositoryInterface $apiPostRepository)
    {
        $this->apiPostRepository = $apiPostRepository;
    }

    public function get(Request $request)
    {
        $page = (int) $request->query('page', 1);
        $perPage = (int) $request->query('perPage');

        return $this->apiPostRepository->getEnablePost($page, $perPage);
    }

    public function show($id)
    {
        return $this->apiPostRepository->find($id);
    }
}
