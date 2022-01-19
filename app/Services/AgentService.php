<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AgentService
{
    protected $model;

    public function __construct(Agent $model)
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

    public function findById(int $id): Agent
    {
        return $this->model->findOrFail($id);
    }

    public function getOne($id)
    {
        $userColumns = ['users.name', 'users.email', 'users.zipcode', 'users.address', 'users.city', 'users.phone', 'users.state', 'users.region_id', 'users.first_name', 'users.last_name'];
        $officeColumns = ['agents.*'];
        return  $this->model->join('users', 'users.id', 'agents.user_id')
            ->where('agents.id', $id)
            ->select(...$officeColumns, ...$userColumns)
            ->first();
    }

    public function datatable()
    {
        $userColumns = ['users.name', 'users.first_name', 'users.last_name', 'users.email', 'users.zipcode', 'users.address', 'users.city', 'users.phone', 'users.state'];
        $agentsColumns = ['agents.*'];
        $officeColumns = ['office_users.name as office_name', 'office_users.phone as office_phone'];
        $query = $this->model
            ->join('users', 'users.id', 'agents.user_id')
            ->leftJoin('offices', 'offices.id', 'agents.agent_office')
            ->leftJoin('users as office_users', 'office_users.id', 'offices.user_id')
            ->orderBy('users.name')
            ->select(...$agentsColumns, ...$userColumns, ...$officeColumns);


        $search = $_GET['search']['value'];
        $isNoOffice = str_starts_with(strtolower($search), 'no of');
        if ($isNoOffice) {
            $response = DataTables::eloquent($query)->filter(function ($query) {
                $query->where('office_users.name', null);
                return $query;
            })->toJson();
            session(['agents' => $response]);
            return $response;
        } else {
            $response = DataTables::eloquent($query)->toJson();
            session(['agents' => $response]);
            return  $response;
        }
    }

    public function post_agents()
    {
        $post_id = (int) ($_GET['agent_id'] ?? 0);
        $userColumns = ['users.name', 'users.email', 'users.zipcode', 'users.address', 'users.city', 'users.phone', 'users.state'];
        $postAgentsColumns = ['post_agents.agent_id', 'post_agents.price as post_agent_price', 'post_agents.access', 'post_agents.locked'];
        $agentsColumns = ['agents.*'];

        $query = $this->model->join('users', 'users.id', 'agents.user_id')
            ->leftJoin('post_agents', function ($join) use ($post_id) {
                $join->on('agents.id', 'post_agents.agent_id')
                    ->where('post_agents.post_id', $post_id);
            })
            ->where('agents.inactive', 0)
            ->orderBy('users.name')
            ->select(...$agentsColumns, ...$userColumns, ...$postAgentsColumns);


        return DataTables::eloquent($query)->toJson();
    }

    public function accessory_agents()
    {
        $accessory_id = (int) ($_GET['accessory_id'] ?? 0);
        $userColumns = ['users.name', 'users.email', 'users.zipcode', 'users.address', 'users.city', 'users.phone', 'users.state'];
        $accessoryAgentsColumns = ['accessory_agents.agent_id', 'accessory_agents.price as post_agent_price', 'accessory_agents.access', 'accessory_agents.locked'];
        $agentsColumns = ['agents.*'];

        $query = $this->model->join('users', 'users.id', 'agents.user_id')
            ->leftJoin('accessory_agents', function ($join) use ($accessory_id) {
                $join->on('agents.id', 'accessory_agents.agent_id')
                    ->where('accessory_agents.accessory_id', $accessory_id);
            })
            ->where('agents.inactive', 0)
            ->orderBy('users.name')
            ->select(...$agentsColumns, ...$userColumns, ...$accessoryAgentsColumns);


        return DataTables::eloquent($query)->toJson();
    }

    public function destroyAll()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        $this->model->truncate();
        (new UserService(new User))->deleteWhereRole(User::ROLE_AGENT);

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        return true;
    }
}
