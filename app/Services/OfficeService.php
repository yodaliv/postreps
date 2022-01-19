<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Office;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class OfficeService
{
    protected $model;

    public function __construct(Office $model)
    {
        $this->model = $model;
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function getAll()
    {
        return $this->model->with('user')->get()->sortBy('user.name', SORT_REGULAR, false);
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

    public function findById(int $id): Office
    {
        return $this->model->findOrFail($id);
    }

    public function getOne($id)
    {
        $userColumns = ['users.name', 'users.email', 'users.zipcode', 'users.address', 'users.city', 'users.phone', 'users.state', 'users.region_id'];
        $officeColumns = ['offices.*'];
        return  $this->model->join('users', 'users.id', 'offices.user_id')->select(...$officeColumns, ...$userColumns)->where('offices.id', $id)->first();
    }

    public function datatable()
    {
        $userColumns = ['users.name', 'users.email', 'users.zipcode', 'users.address', 'users.city', 'users.phone', 'users.state'];
        $officeColumns = ['offices.*', DB::raw('(SELECT count(*) FROM agents WHERE offices.id = agents.agent_office) as agents_count')];
        $query = $this->model->join('users', 'users.id', 'offices.user_id')
            ->orderBy('users.name')
            ->select(...$officeColumns, ...$userColumns);
        return DataTables::of($query)->make(true);
    }

    public function datatable_public()
    {
        $userColumns = ['users.name', 'users.email', 'users.zipcode', 'users.address', 'users.city', 'users.phone', 'users.state'];
        $officeColumns = ['offices.*', DB::raw('(SELECT count(*) FROM agents WHERE offices.id = agents.agent_office) as agents_count')];
        $query = $this->model->join('users', 'users.id', 'offices.user_id')
            ->where('offices.inactive', 0)
            ->where('offices.private', 0)
            ->select(...$officeColumns, ...$userColumns);

        return DataTables::of($query)->make(true);
    }

    public function destroyAll()
    {
        // delete images;
        $images = $this->model->select(DB::raw('CONCAT("/private/images/",logo_image) as logo_image'))->pluck('logo_image')->toArray();
        Storage::delete($images);
        return (new UserService(new User))->deleteWhereRole(User::ROLE_OFFICE);
    }

    public function post_offices()
    {
        $post_id = (int) ($_GET['post_id'] ?? 0);

        $userColumns = ['users.name', 'users.email', 'users.zipcode', 'users.address', 'users.city', 'users.phone', 'users.state'];
        $postOfficesColumns = ['post_offices.office_id', 'post_offices.locked', 'post_offices.price as post_office_price', 'post_offices.access', 'post_offices.price'];
        $officeColumns = ['offices.*'];

        $query = $this->model->join('users', 'users.id', 'offices.user_id')
            ->leftJoin('post_offices', function ($join) use ($post_id) {
                $join->on('offices.id', 'post_offices.office_id')
                    ->where('post_offices.post_id', $post_id);
            })
            ->where('offices.inactive', 0)
            ->orderBy('users.name')
            ->select(...$officeColumns, ...$userColumns, ...$postOfficesColumns);

        return DataTables::of($query)->make(true);
    }

    public function accessory_offices()
    {
        $accessory_id = (int) ($_GET['accessory_id'] ?? 0);

        $userColumns = ['users.name', 'users.email', 'users.zipcode', 'users.address', 'users.city', 'users.phone', 'users.state'];
        $accessoryOfficesColumns = ['accessory_offices.office_id', 'accessory_offices.locked', 'accessory_offices.price as post_office_price', 'accessory_offices.access', 'accessory_offices.price'];
        $officeColumns = ['offices.*'];

        $query = $this->model->join('users', 'users.id', 'offices.user_id')
            ->leftJoin('accessory_offices', function ($join) use ($accessory_id) {
                $join->on('offices.id', 'accessory_offices.office_id')
                    ->where('accessory_offices.accessory_id', $accessory_id);
            })
            ->where('offices.inactive', 0)
            ->orderBy('users.name')
            ->select(...$officeColumns, ...$userColumns, ...$accessoryOfficesColumns);

        return DataTables::of($query)->make(true);
    }
}
