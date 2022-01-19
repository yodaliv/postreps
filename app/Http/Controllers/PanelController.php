<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePanel;
use App\Http\Requests\UpdatePanel;
use App\Http\Traits\HelperTrait;
use App\Models\Panel;
use App\Models\PanelAgent;
use App\Services\FileService;
use App\Services\PanelService;
use Illuminate\Http\Request;

class PanelController extends Controller
{

    use HelperTrait;

    protected $panelService;

    protected $fileService;

    public function __construct(PanelService $panelService,FileService $fileService)
    {
        $this->panelService = $panelService;

        $this->fileService = $fileService;
    }

    public function getPanel(Panel $panel)
    {
        return $panel;
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
    public function store(CreatePanel $request)
    {
        $data = $request->all();

        $agents = collect($data['agent_id'] ?? [])->reject(function($agent){
            if($agent == 0) return true;
        })->unique();

        $data['agent_id'] = null;

        $data['office_id'] = $this->setDefaultNull($data['office_id']??null);

        $data['width'] = $data['width'] ?? 0;
        $data['height'] = $data['height'] ?? 0;

        //store image if exists
        if ($request->hasFile('panel_image')) {
            $request->file = $request->panel_image;

            $uploadImg = $this->fileService->uploadImage($request, 0, 0);
            if (!$uploadImg['success']) {
                return $this->backWithError($uploadImg['msg']);
            }

            $data['image_path'] = $uploadImg['fileName'];
        } else {
            $data['image_path'] = 'no_panel_image.png';
        }
        $panel = $this->panelService->create($data);

        $c = 0;
        $panel_agents = $agents->map(function($agent) use($panel, &$c) {
            $isPrimary = $c == 0 ? true : false;
            $c++;
            return ['panel_id' => $panel->id, 'agent_id' => (int) $agent, 'is_primary' => $isPrimary];
        });
        $panel->agents()->createMany($panel_agents);

        return $this->backWithSuccess('Panel created successfully.');
    }

    public function datatable()
    {
        return $this->panelService->datatable();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Panel  $panel
     * @return \Illuminate\Http\Response
     */
    public function show(Panel $panel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Panel  $panel
     * @return \Illuminate\Http\Response
     */
    public function edit(Panel $panel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Panel  $panel
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePanel $request, Panel $panel)
    {
        $data = $request->all();

        $agents = collect($data['agent_id'] ?? [])->reject(function($agent){
            if($agent == 0) return true;
        })->unique();

        $data['agent_id'] = null;

        $data['office_id'] = $this->setDefaultNull($data['office_id']??null);

        $data['width'] = $data['width'] ?? 0;
        $data['height'] = $data['height'] ?? 0;

        //store image if exists
        if ($request->hasFile('edit_panel_image')) {
            $request->file = $request->edit_panel_image;

            $uploadImg = $this->fileService->uploadImage($request, 0, 0);
            if (!$uploadImg['success']) {
                return $this->backWithError($uploadImg['msg']);
            }

            $data['image_path'] = $uploadImg['fileName'];
        }

        $panel->update($data);
        $panel->agents()->delete();
        $c = 0;
        $panel_agents = $agents->map(function($agent) use($panel, &$c) {
            $isPrimary = $c == 0 ? true : false;
            $c++;
            return ['panel_id' => $panel->id, 'agent_id' => (int) $agent, 'is_primary' => $isPrimary];
        });

        $panel->agents()->createMany($panel_agents);

        return $this->backWithSuccess('Panel updated successfully.');
    }

    public function destroyAll()
    {
        Panel::query()->delete();
        return $this->responseJsonSuccess((object)[]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Panel  $panel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Panel $panel)
    {
        //
    }

    public function agents($panel)
    {

       return $this->panelService->getAgents($panel);

    }

    public function allAgents($panel)
    {
        return $this->panelService
        ->getAgentsQuery($panel)
        ->select('agents.id as agent_id','users.name as agent_name')
        ->get();
    }
}
