<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateNote;
use App\Http\Requests\CreateOffice;
use App\Http\Requests\UpdateOffice;
use App\Http\Requests\UpdateOfficePaymentMethod;
use App\Http\Traits\HelperTrait;
use App\Models\Agent;
use App\Models\Office;
use App\Models\User;
use App\Services\{AccessoryService, AgentService, OfficeService, RegionService, UserService, FileService, PanelService, PostService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB};
use App\Services\EmailResetPasswordService;

class OfficeController extends Controller
{
    use HelperTrait;

    protected $officeService;
    protected $agentService;
    protected $regionService;
    protected $userService;
    protected $fileService;
    protected $postService;
    protected $panelService;
    protected $accessoryService;

    public function __construct(
        OfficeService       $officeService,
        RegionService       $regionService,
        UserService         $userService,
        FileService         $fileService,
        AgentService        $agentService,
        PostService         $postService,
        PanelService        $panelService,
        AccessoryService    $accessoryService
    ) {
        $this->officeService = $officeService;
        $this->regionService = $regionService;
        $this->userService = $userService;
        $this->fileService = $fileService;
        $this->agentService = $agentService;
        $this->postService = $postService;
        $this->panelService = $panelService;
        $this->accessoryService = $accessoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'offices' => collect(),
            'states' => $this->getStates(),
            'regions' => $this->regionService->getAll(),
        ];

        return view('office.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'states' => $this->getStates(),
            'regions' => $this->regionService->getAll(),
        ];

        return view('office.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateOffice $request)
    {
        $data = $request->all();
        //dd($data);

        //create password for user
        $data['password'] = bcrypt('s');

        // set user role
        $data['role'] = User::ROLE_OFFICE;

        //store image if exists
        if ($request->hasFile('logo_image')) {
            $request->file = $request->logo_image;

            $uploadImg = $this->fileService->uploadImage($request, 0, 0);
            if (!$uploadImg['success']) {
                return $this->backWithError($uploadImg['msg']);
            }

            $data['logo_image'] = $uploadImg['fileName'];
        } else {
            $data['logo_image'] = 'office_no_logo.png';
        }

        DB::transaction(function () use (&$data) {
            //create user
            $user = $this->userService->create($data);
            //verify email
            $user->email_verified_at = now()->format('Y-m-d');
            $user->save();
            // user_id for office
            $data['user_id'] = $user->id;

            //create office
            $this->officeService->create($data);
        });
        //back to form
        return $this->backWithSuccess('Office created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Office $office
     * @return \Illuminate\Http\Response
     */
    public function show(Office $office)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Office $office
     * @return \Illuminate\Http\Response
     */
    public function edit(Office $office)
    {
        $data = [
            'office' => $office,
            'states' => $this->getStates(),
            'regions' => $this->regionService->getAll(),
        ];
        return view('office.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Office $office
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOffice $request, Office $office)
    {
        $data = $request->all();

        //store image if exists
        if ($request->hasFile('edit_logo_image')) {
            $request->file = $request->edit_logo_image;

            $uploadImg = $this->fileService->uploadImage($request, 0, 0);
            if (!$uploadImg['success']) {
                return $this->backWithError($uploadImg['msg']);
            }

            $data['logo_image'] = $uploadImg['fileName'];
        }

        DB::transaction(function () use ($office, $data) {
            $office->user->update($data);

            $office->update($data);
        });

        return $this->backWithSuccess('Office updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Office $office
     * @return \Illuminate\Http\Response
     */
    public function destroy(Office $office)
    {
        $office->delete();

        return $this->backWithSuccess('Office deleted successfully.');
    }

    public function datatable()
    {
        return $this->officeService->datatable();
    }

    public function post_offices()
    {
        return $this->officeService->post_offices();
    }

    public function accessory_offices()
    {
        return $this->officeService->accessory_offices();
    }
    public function accessory_agents()
    {
        return $this->agentService->accessory_agents();
    }

    public function resetPassword(Request $request, Office $office)
    {
        $EmailResetPassword = new EmailResetPasswordService;
        $request->request->add(['email' => $office->user->email]);
        $EmailResetPassword->sendResetLinkEmail($request);
        return $this->backWithSuccess('Password reset link sent successfully.');
    }

    public function getOffice($id)
    {
        return $this->officeService->getOne((int)$id);
    }

    public function destroyAll()
    {
        $this->officeService->destroyAll();
        return $this->responseJsonSuccess((object)[]);
    }

    public function updatePaymentMethod(UpdateOfficePaymentMethod $request, Office $office)
    {
        return $office->update($request->all());
    }

    public function storeNote(CreateNote $request, Office $office)
    {
        $office->update($request->all());
        return $this->backWithSuccess("Notes updated successfully.");
    }

    public function datatable_public()
    {
        return $this->officeService->datatable_public();
    }

    public function agents($office)
    {
        $office = Office::find($office);
        if ($office) {
            $data = $office->where('id', $office->id)->with('active_agents', 'active_agents.user')->first();
            return $data;
        } else {
            return;
        }
    }

    public function agentsJson(Office $office)
    {
        return response()->json($office->agents()->with('user')->get());
    }

    public function agentsJsonOrderByName(Office $office)
    {
        $collection = $office
            ->agents()->with('user')
            ->with('agent_access')
            ->join('users', 'users.id', 'agents.user_id')
            ->select('agents.*', 'users.last_name')
            ->orderBy('users.last_name')
            ->where('agents.inactive', 0)
            ->get()
            ->map(function ($collect) {
                $collect->user->lastNameFirstName();
                // $collect->agent_access_json = $collect->agent_access->pluck('agent_id');
                return $collect;
            });
        return response()->json($collection);
    }

    public function getOfficePosts(Office $office, Request $request)
    {
        $posts = $this->postService->getOrderByListingOrderAndName()
            ->filter(function ($post) use ($office) {
                if ($post->office_id) {
                    if ($post->office_id == $office->id) {
                        //Check if office has special price for the post
                        $checkAccess = $post->post_offices->where('office_id', $office->id)->first();
                        if ($checkAccess && $checkAccess->access == true) {
                            if ($checkAccess->locked == true) {
                                $post->price = $checkAccess->price;
                            }                            
                        }      
                        
                        return $post;
                    } else {
                        return null;
                    }
                } else {
                    $checkAccess = $post->post_offices->where('office_id', $office->id)->first();
                    if ($checkAccess) {
                        if ($checkAccess->access == true) {
                            return $post;
                        }
                    } else {
                        if ($post->default == 1) {
                            return $post;
                        }
                    }
                }
            });

        return response()->json($posts);
    }

    public function getOfficeAccessories(Office $office, Request $request)
    {
        $accessories = $this->accessoryService->getOrderByListingOrderAndName()
            ->filter(function ($accessory) use ($office) {
                $checkAccess = $accessory->accessory_offices->where('office_id', $office->id)->first();

                if ($checkAccess) {
                    if ($checkAccess->access == true) {
                        if ($checkAccess->locked == true) {
                            $accessory->price = $checkAccess->price;
                        }

                        return $accessory;
                    }
                } else {
                    if ($accessory->default == 1) {
                        return $accessory;
                    }
                }

                return null;
            });

        return response()->json($accessories);
    }

    public function getOfficePanels(Office $office)
    {
        $panels = $this->panelService->getOrderByListingOrderAndName()
            ->filter(function ($panel) use ($office) {
                if ($panel->office_id == $office->id) {
                    return $panel;
                }

                return null;
            });

        return response()->json($panels);
    }
}
