<?php

namespace App\Exports;

use App\Http\Traits\HelperTrait;
use App\Models\Agent;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AgentsExport implements FromView
{
    use HelperTrait;
    public $agents;

    public function __construct($agents)
    {
        $userColumns = ['users.name','users.first_name','users.last_name', 'users.email', 'users.zipcode', 'users.address', 'users.city', 'users.phone', 'users.state'];
        $agentsColumns = ['agents.*'];
        $officeColumns = ['office_users.name as office_name', 'office_users.phone as office_phone'];
        $query = Agent::query()
            ->join('users', 'users.id', 'agents.user_id')
            ->leftJoin('offices', 'offices.id', 'agents.agent_office')
            ->leftJoin('users as office_users', 'office_users.id', 'offices.user_id')
            ->orderBy('users.name')
            ->select(...$agentsColumns, ...$userColumns, ...$officeColumns);
        $this->agents = $query->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        return view("exports.agents", ['self' => $this ]);
    }
}
