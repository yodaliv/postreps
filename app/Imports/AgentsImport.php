<?php

namespace App\Imports;

use App\Http\Traits\HelperTrait;
use App\Models\Agent;
use App\Models\User;
use App\Services\AgentService;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Collection;

class AgentsImport implements ToCollection, WithStartRow
{
    use HelperTrait;

    protected $userService;

    protected $agentService;

    protected $imported = [
        'count' => 0,
        'errors' => [
            // [row => 1 , msg => 'er']
        ]
    ];

    protected $columns = [
        "first name",
        "last name",
        "address",
        "city",
        "State",
        "zipcode",
        "email",
        "phone",
        "re license",
        "inactive",
        "agent office",
    ];

    public function __construct()
    {
        $this->userService =  new UserService(new User);

        $this->agentService = new AgentService(new Agent);
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        $current_date = now()->format("M d,Y");
        foreach ($rows as $rowIndex => $row) {
            if (!$this->isEmptyRow($row)) {
                try {
                    $rowIndex = $rowIndex + 2;
                    $this->validation($row, $rowIndex);

                    $data = [];
                    $data['first_name'] = $row[0];
                    $data['last_name'] = $row[1];
                    $data['address'] = $row[2];
                    $data['city'] = $row[3];
                    $data['state'] = $row[4];
                    $data['zipcode'] = $row[5];
                    $data['email'] = $row[6];
                    $data['phone'] = $row[7];
                    $data['re_license'] = $row[8];
                    //status
                    $data['inactive'] = $row[9] == "active" ? 1 : 0;
                    $data['agent_office'] = User::where('name', $row[10])->first();
                    if ($data['agent_office']) {
                        if ($data['agent_office']->office) {
                            $data['agent_office'] = $data['agent_office']->office->id;
                        } else {
                            $data['note'] = "IMPORT NOTE: $current_date: import office does not exist: [{$row[10]}]";
                        }
                    } else {
                        $data['note'] = "IMPORT NOTE: $current_date: import office does not exist: [{$row[10]}]";
                    }

                    //set user name
                    $data['name'] = $data['first_name'] . " " . $data['last_name'];
                    //create password for user
                    $data['password'] = bcrypt('s');

                    // set user role
                    $data['role'] = User::ROLE_AGENT;
                    if (!$this->isThisRowHaveError($rowIndex)) {
                        //store data
                        try {
                            DB::transaction(function () use ($data) {
                                //create user
                                $user = $this->userService->create($data);
                                //verify email
                                $user->email_verified_at = now()->format('Y-m-d');
                                $user->save();
                                // user_id for agent
                                $data['user_id'] = $user->id;
                                info($user->id);
                                info($data);

                                //create agent
                                $this->agentService->create($data);

                                $this->imported['count'] += 1;
                            });
                        } catch (\Exception $e) {
                            // return $this->backWithError($e->getMessage());
                            if ((int)$e->getCode() === 23000) {
                                $this->newRowError($rowIndex, 'email already exists');
                            } else {
                                $this->newRowError($rowIndex, 'error');
                            }
                        }
                    }
                } catch (\Exception $e) {
                }
            };
        }

        $html = "
        <p class='m-0'>{$this->imported['count']} rows imported</p>
        ";

        if (count($this->imported['errors'])) $html .= "<p class='m-0'><strong>Some rows were not imported due to errors: </strong></p>";
        $ul = "<ul class='p-0 pl-3'>";
        foreach ($this->imported['errors'] as $e) {
            $ul .= "<li class='text-danger'>Error in row {$e['row']}: {$e['msg']}</li>";
        }
        $ul .= "</ul>";


        $html .= $ul;
        return $this->backWithSuccess($html);
    }

    public function newRowError($row, $msg)
    {
        $this->imported['errors'][] = compact('row', 'msg');
    }

    public function validation($row, $rowIndex)
    {
        for ($i = 0; $i < count($this->columns); $i++) {
            if (!isset($row[$i]) and $i != 8) {
                $this->newRowError($rowIndex, "{$this->columns[$i]} is required (column: $i)");
            };
        }
    }

    public function isEmptyRow($row)
    {
        $isEmpty = 0;
        foreach ($row as $r) {
            if ($r == null or empty($r)) $isEmpty += 1;
        }
        $isEmpty == count($row) ? $isEmpty = true : $isEmpty = false;
        return $isEmpty;
    }
    public function isThisRowHaveError($index)
    {
        $errors = $this->imported['errors'];
        $yes = false;
        foreach ($errors as $e) {
            if ($e['row'] == $index) $yes = true;
        }
        return $yes;
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
}
