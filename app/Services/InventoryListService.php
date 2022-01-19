<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\InventoryList;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class InventoryListService
{
    protected $model;

    public function __construct(InventoryList $model)
    {
        $this->model = $model;
    }

    public function create(array $attributes)
    {

        if (isset($attributes['item_id']) and isset($attributes['office_id'])) {

            if($attributes['item_id'] and $attributes['office_id']){
                return $this->model->create($attributes);
            }
        }
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

    public function findById(int $id): InventoryList
    {
        return $this->model->findOrFail($id);
    }
}
