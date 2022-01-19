<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Zone;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class ZoneService
{
    protected $model;

    public function __construct(Zone $model)
    {
        $this->model = $model;
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function getAll()
    {
        return $this->model->latest()->get();
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

    public function findById(int $id): Zone
    {
        return $this->model->findOrFail($id);
    }
}
