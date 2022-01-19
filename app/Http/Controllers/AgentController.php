<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAgent;
use App\Http\Requests\CreateNote;
use App\Http\Requests\UpdateAgent;
use App\Http\Requests\UpdateOfficePaymentMethod;
use App\Http\Traits\HelperTrait;
use App\Models\Agent;
use App\Models\Office;
use App\Models\Order;
use App\Models\Post;
use App\Models\PostAgent;
use App\Models\User;
use App\Models\Panel;
use App\Services\AccessoryService;
use App\Services\AgentService;
use App\Services\EmailResetPasswordService;
use App\Services\FileService;
use App\Services\PanelService;
use App\Services\PostService;
use App\Services\RegionService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentController extends Controller
{
    use HelperTrait;

    protected $agentService;
    protected $regionService;
    protected $userService;
    protected $fileService;
    protected $postService;
    protected $panelService;
    protected $accessoryService;

    public function __construct(
        AgentService        $agentService,
        RegionService       $regionService,
        UserService         $userService,
        FileService         $fileService,
        PostService         $postService,
        PanelService        $panelService,
        AccessoryService    $accessoryService
    ) {
        $this->agentService = $agentService;
        $this->regionService = $regionService;
        $this->userService = $userService;
        $this->fileService = $fileService;
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAgent $request)
    {
        $data = $request->all();

        $data['agent_office'] = $this->setDefaultNull($data['agent_office'] ?? null);


        //set user name
        $data['name'] = $data['first_name'] . " " . $data['last_name'];
        //create password for user
        $data['password'] = bcrypt('s');

        // set user role
        $data['role'] = User::ROLE_AGENT;

        DB::transaction(function () use (&$data) {
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
        });
        //back to form
        return $this->backWithSuccess('Agent created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function show(Agent $agent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function edit(Agent $agent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAgent $request, Agent $agent)
    {
        $data = $request->all();

        $data['agent_office'] = $this->setDefaultNull($data['agent_office'] ?? null);

        $data['name'] = $data['first_name'] . " " . $data['last_name'];

        if (auth()->user()->role == User::ROLE_SUPER_ADMIN && $agent->inactive && $agent->user->email_verified_at === null) {
            $data['email_verified_at'] = now()->format('Y-m-d');
        }

        DB::transaction(function () use ($agent, $data) {
            $agent->user->update($data);

            $agent->update($data);
        });

        return $this->backWithSuccess('Agent updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Agent $agent)
    {
        //
    }

    public function datatable()
    {
        return $this->agentService->datatable();
    }

    public function post_agents()
    {
        return $this->agentService->post_agents();
    }

    public function resetPassword(Request $request, Agent $agent)
    {
        $EmailResetPassword = new EmailResetPasswordService;
        $request->request->add(['email' => $agent->user->email]);
        $EmailResetPassword->sendResetLinkEmail($request);
        return $this->backWithSuccess('Password reset link sent successfully.');
    }

    public function getAgent($id)
    {
        return $this->agentService->getOne((int)$id);
    }

    public function destroyAll()
    {
        $this->agentService->destroyAll();
        return $this->responseJsonSuccess((object)[]);
    }

    public function updatePaymentMethod(UpdateOfficePaymentMethod $request, Agent $agent)
    {
        return $agent->update($request->all());
    }

    public function storeNote(CreateNote $request, Agent $agent)
    {
        $agent->update($request->all());
        return $this->backWithSuccess("Notes updated successfully.");
    }

    public function newOffice(Office $office, Agent $agent)
    {
        $oldOffice = $agent->office;

        //Update agent office
        $agent->update(['agent_office' => $office->id]);

        //add note in agent
        $note = $agent->note ? $agent->note : '';
        if ($note !== '') $note .= "\n";
        $date = now()->format('m/d/Y h:m A');
        $oldOfficeId = 0;
        if ($oldOffice) {
            if ($oldOffice->id !== $office->id) {
                $oldOfficeName = $agent->office->user->name;
                $note .= "$date Change of Office {$oldOfficeName} to {$office->user->name}";
            }
            $oldOfficeId = $oldOffice->id;
        } else {
            $note .= "$date Assigned Office {$office->user->name}";
        }

        $agent->note = $note;
        $agent->save();

        //Update office_id for posts belonging to agent
        $posts = Post::where("office_id", $oldOfficeId)->where('agent_id', $agent->id);
        $posts->update(['office_id' => $office->id]);

        //Update office_id for panel belonging to agent, if agent is primary
        $this->panelService->updateOfficeIdPrimaryAgent($agent);

        // Update office_id for orders belonging to agent
        $agent_orders = Order::where('agent_id', $agent->id);
        $agent_orders->update(['office_id' => $office->id]);

        return $this->backWithSuccess('Office set successfully.');
    }

    public function updatePostAgentColumn($post, $agent, $column, $value)
    {
        if (in_array($column, ['price', 'locked'])) {
            if ($column == 'locked') {
                $agent = $this->agentService->findById($agent);
                $agent->locked = !$agent->locked;
                $agent->locked_price = $value;
                $agent->save();
                return response(['done' => 1]);
            }
            $value = doubleval($value);
            PostAgent::where('post_id', $post)->where('agent_id', $agent)->update([$column => $value]);
            return response(['done' => 1]);
        }
    }

    public function toggleLocked(Post $post, Agent $agent, $access, $price, $locked)
    {

        $price = doubleval($price);

        if ($locked == "true") {
            PostAgent::updateOrCreate([
                'post_id' => $post->id,
                'agent_id' => $agent->id,
            ], [
                'post_id' => $post->id,
                'agent_id' => $agent->id,
                'price' => $price,
                'access' => $access == "true" ? 1 : 0,
                'locked' => $locked == "true" ? 1 : 0
            ]);

            return response()->json(['action' => 'create', 'ok' => 1]);
        } else {

            PostAgent::where('post_id', $post->id)
                ->where('agent_id', $agent->id)
                ->update(['locked' => $locked == "true" ? 1 : 0]);

            return response()->json(['action' => 'create', 'ok' => 1]);
        }

        return response()->json(['msg' => 'error', 'ok' => 0]);
    }

    public static function toggleAccess(Post $post, Agent $agent, $access, $price = 0)
    {
        $post_office = PostAgent::where('post_id', $post->id)->where('agent_id', $agent->id);

        if ($post_office->exists()) {
            PostAgent::where('post_id', $post->id)
                ->where('agent_id', $agent->id)
                ->update(['access' => $access == "true" ? 1 : 0]);
            return response()->json(['action' => "update", 'ok' => 1]);
        } else {
            PostAgent::create([
                'post_id' => $post->id,
                'agent_id' => $agent->id,
                'price' => $price,
                'access' => 1,
            ]);
            return response()->json(['action' => "create", 'ok' => 1]);
        }
    }

    public function getAgentPosts(Agent $agent)
    {
        $posts = $this->postService->getOrderByListingOrderAndName()
            ->filter(function ($post) use ($agent) {
                //Must add posts that agents office has access to
                if ($post->office_id) {
                    if ($post->office_id == $agent->agent_office) {
                        //Check if office has special price for the post
                        $checkAccess = $post->post_offices->where('office_id', $agent->agent_office)->first();
                        if ($checkAccess && $checkAccess->access == true) {
                            if ($checkAccess->locked == true) {
                                $post->price = $checkAccess->price;
                            }

                            return $post;
                        }
                    }
                }

                if ($post->agent_id) {
                    if ($post->agent_id == $agent->id) {
                        //Check if agent has special price for the post
                        $checkAccess = $post->post_agents->where('agent_id', $agent->id)->first();
                        if ($checkAccess && $checkAccess->access == true) {
                            if ($checkAccess->locked == true) {
                                $post->price = $checkAccess->price;
                            }
                        }

                        return $post;
                    } else {
                        return null;
                    }
                }

                if (!$post->agent_id) {
                    if ($post->default == 1) {
                        return $post;
                    }
                }

                if (!$post->office_id) {
                    $checkAccess = $post->post_agents->where('agent_id', $agent->id)->first();
                    if ($checkAccess) {
                        if ($checkAccess->access == true) {
                            return $post;
                        }
                    }
                }
            });

        return response()->json($posts);
    }

    public function getAgentAcessories(Agent $agent)
    {
        $accessories = $this->accessoryService->getOrderByListingOrderAndName()
            ->filter(function ($accessory) use ($agent) {
                 //Must add accessories that agents office has access to
                $checkAccess = $accessory->accessory_offices->where('office_id', $agent->agent_office)->first();
                if ($checkAccess) {
                    if ($checkAccess->access == true) {
                        if ($checkAccess->locked == true) {
                            $accessory->price = $checkAccess->price;
                        }

                        return $accessory;
                    }
                }

                $checkAccess = $accessory->accessory_agents->where('agent_id', $agent->id)->first();
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

    public function getAgentPanels(Agent $agent)
    {
        $panels = $this->panelService->getOrderByListingOrderAndName()
            ->filter(function ($panel) use ($agent) {
                if ($panel->office_id == $agent->agent_office) {
                    //If no agents assigned then panel will be available to all agents in the office
                    if ($panel->agents->isEmpty()) {
                        return $panel;
                    }

                    //Check if agent has access
                    $hasAccess = $panel->agents->where('agent_id', $agent->id)->first();
                    if ($hasAccess) {
                        return $panel;
                    }
                }

                return null;
            });

        return response()->json($panels);
    }
}
