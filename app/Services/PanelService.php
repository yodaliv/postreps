<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Traits\HelperTrait;
use App\Models\Panel;
use App\Models\PanelAgent;
use App\Models\Agent;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;


class PanelService
{
    use HelperTrait;

    protected $model;

    public function __construct(Panel $model)
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

        $itemCode = "S{$year}{$monthChar}{$counter}";

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

    public function findById(int $id): Panel
    {
        return $this->model->findOrFail($id);
    }

    public function getOrderByListingOrderAndName()
    {
        $data = $this->model
            ->orderBy('listing_order', 'asc')
            ->orderBy('panel_name', 'asc')
            ->whereNotNull("listing_order")
            ->where('status', 1)
            ->with('agents')
            ->get();
        $nulls = $this->model->whereNull('listing_order')
            ->with('agents')
			->where('status', 1)
			->get();
        foreach ($nulls as $n) {
            $data->push($n);
        }
        return $data;
    }

    public function datatable()
    {

        $paneltCols = [
            'panels.*',
            'office_user.name as office_name',
            'agent_user.name as agent_name'
        ];
        $query = $this->model
            ->leftJoin('offices', 'offices.id', 'panels.office_id')
            ->leftJoin('agents', 'agents.id', 'panels.agent_id')
            ->leftJoin('users as agent_user', 'agent_user.id', 'agents.user_id')
            ->leftJoin('users as office_user', 'office_user.id', 'offices.user_id')
            ->select(...$paneltCols);
        return Datatables::of($query)->make(true);
    }

    public function getAgentsQuery(int $panel_id)
    {
        return PanelAgent::where('panel_id', $panel_id)
            ->join('agents', 'agents.id', 'panel_agents.agent_id')
            ->leftJoin('users', 'users.id', 'agents.user_id');
    }

    public function getAgents(int $panel_id)
    {
        return $this->getAgentsQuery($panel_id)->select('users.name')->get()->pluck('name');
    }

    public function updateOfficeIdPrimaryAgent(Agent $agent)
    {
        $primary = PanelAgent::where('agent_id', $agent->id)
            ->where('is_primary', true)
            ->get();

        foreach ($primary as $p) {
            Panel::where('id', $p->panel_id)
                ->update(['office_id' => $agent->agent_office]);

            //Remove access to all other agents because the panel must follow primary agent
            PanelAgent::where('panel_id', $p->panel_id)
                ->where('agent_id', '<>', $agent->id)
                ->delete();
        }
    }
}
