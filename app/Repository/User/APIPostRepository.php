<?php

namespace App\Repository\User;

use App\Models\Post;
use App\Repository\CommonRepository;

class APIPostRepository extends CommonRepository
{
    public function __construct(Post $post)
    {
        parent::__construct($post);
    }

    public function getEnablePost($page, $perPage)
    {
        $enablePost = $this->model::where('status', 1)->orderByDesc('id');

        return empty($perPage) ? $enablePost->get() : $enablePost->paginate($perPage, ['*'], 'page', $page);
    }
}
