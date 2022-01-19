<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Region;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class RegionService
{
    protected $model;

    public function __construct(Region $model)
    {
        $this->model = $model;
    }

    public function create(array $attributes)
    {
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

    public function findById(int $id): Region
    {
        return $this->model->findOrFail($id);
    }
}
