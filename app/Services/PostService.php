<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Datatables;
use App\Http\Traits\HelperTrait;

class PostService
{
    use HelperTrait;

    protected $model;

    public function __construct(Post $model)
    {
        $this->model = $model;
    }

    public function create(array $attributes)
    {
        //Item id
        $monthChar = $this->getMonthCharFromAlphabet((int) now()->month);

        $lastItemNumber = $this->model->max('item_id_number') ?? 0;
        $itemNumber = ++$lastItemNumber;
        $year = sprintf('%03d', now()->format('y'));
        $counter = sprintf('%05d', $itemNumber);

        $itemCode = "P{$year}{$monthChar}{$counter}";

        $attributes['item_id_number'] = $itemNumber;
        $attributes['item_id_code'] = $itemCode;

        return $this->model->create($attributes);
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getAllOrderBy(string $orderBy, string $sortOrder = 'asc'): EloquentCollection
    {
        $getAllOrderBy = $this->model;

        if ($orderBy) {
            $getAllOrderBy = $getAllOrderBy->orderBy($orderBy, $sortOrder);
        }

        $getAllOrderBy = $getAllOrderBy->get();

        return $getAllOrderBy;
    }


    public function findById(int $id): Post
    {
        return $this->model->findOrFail($id);
    }

    public function getOrderByListingOrderAndName()
    {
        $data = $this->model
            ->orderBy('listing_order', 'asc')
            ->orderBy('post_name', 'asc')
            ->whereNotNull("listing_order")
            ->where('status', 1)
            ->with('post_offices')
            ->with('post_agents')
            ->with('office_access')
            ->with('agent_access')
            ->get()
            ->map(function ($post) {
                return $post;
            });
        $nulls = $this->model->whereNull('listing_order')
            ->with('post_offices')
            ->with('post_agents')
            ->where('status', 1)
            ->get();
        foreach ($nulls as $n) {
            $data->push($n);
        }
        return $data;
    }

    public function getOne($id)
    {
    }

    public function datatable()
    {
        //TEMPORARILY UPDATE EXISTING POSTS
        /*$monthChar = $this->getMonthCharFromAlphabet((int) now()->month);

        $postsWithoutItemId = $this->model->whereNull('item_id_number')->get();
        foreach ($postsWithoutItemId as $post) {
            $lastItemNumber = $this->model->max('item_id_number') ?? 0;
            $itemNumber = ++$lastItemNumber;
            $year = sprintf('%03d', now()->format('y'));
            $counter = sprintf('%05d', $itemNumber);

            $itemCode = "P{$year}{$monthChar}{$counter}";

            $post->item_id_number = $itemNumber;
            $post->item_id_code = $itemCode;
            $post->save();
        }*/

        $postCols = [
            'posts.*',
            'office_user.name as office_name',
            'agent_user.name as agent_name'
        ];
        $query = $this->model
            ->leftJoin('offices', 'offices.id', 'posts.office_id')
            ->leftJoin('agents', 'agents.id', 'posts.agent_id')
            ->leftJoin('users as agent_user', 'agent_user.id', 'agents.user_id')
            ->leftJoin('users as office_user', 'office_user.id', 'offices.user_id')
            ->select(...$postCols);

        return Datatables::of($query)->make(true);
    }

    public function destroyAll()
    {
        // return (new UserService(new User))->deleteWhereRole(User::ROLE_Post);
    }
}
