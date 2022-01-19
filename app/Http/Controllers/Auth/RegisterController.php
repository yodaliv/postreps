<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AgentRegister;
use App\Http\Traits\HelperTrait;
use App\Models\User;
use App\Services\AgentService;
use App\Services\NotificationService;
use App\Services\OfficeService;
use App\Services\RegionService;
use App\Services\UserService;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers, HelperTrait;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/email/verify';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $officeService;
    protected $agentService;
    protected $regionService;
    protected $notificationService;
    protected $userService;

    public function __construct(
        OfficeService $officeService,
        AgentService $agentService,
        RegionService $regionService,
        NotificationService $notificationService,
        UserService $userService
    ) {
        $this->officeService = $officeService;

        $this->agentService = $agentService;

        $this->regionService = $regionService;

        $this->notificationService = $notificationService;

        $this->userService = $userService;

        $this->middleware('guest');
    }

    public function store(AgentRegister $request)
    {
        $data = $request->all();
        //set user name
        $data['name'] = $data['first_name'] . " " . $data['last_name'];

        //create password for user
        $data['password'] = bcrypt($request->password);

        // set user role
        $data['role'] = User::ROLE_AGENT;

        DB::transaction(function () use (&$data) {
            //create user
            $user = $this->userService->create($data);

            $user->save();
            // user_id for agent
            $data['user_id'] = $user->id;

            //Agent default state is inactive until email is verified
            $data['inactive'] = true;

            //create agent
            $agent = $this->agentService->create($data);

            //mails
            $this->notificationService->verifyUserEmail($user);

            if (!$agent->agent_office) {
                $this->notificationService->newAgentRegisterWithoutOffice($agent);
            } else {
                $this->notificationService->newAgentRegister($agent);
            }

            // login agent
            Auth::login($user);
        });
        //back to form
        return $this->backWithSuccess('Agent created successfully.');
        return redirect('/register');
    }


    public function showRegistrationForm()
    {
        $data = [
            'states' => $this->getStates(),
            'regions' => $this->regionService->getAll(),
        ];
        return view('auth.register', $data);
    }
}
