<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePost;
use App\Http\Requests\UpdatePost;
use App\Http\Traits\HelperTrait;
use App\Models\Office;
use App\Models\Post;
use App\Models\PostOffice;
use App\Services\FileService;
use App\Services\OfficeService;
use App\Services\PostService;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\Double;

class PostController extends Controller
{

    use HelperTrait;

    protected $postService;

    protected $fileService;

    protected $officeService;

    public function __construct(PostService $postService, FileService $fileService, OfficeService $officeService)
    {
        $this->postService = $postService;

        $this->fileService = $fileService;

        $this->officeService = $officeService;
    }

    public function getPost(Post $post)
    {
        return $post;
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
    public function store(CreatePost $request)
    {
        $data = $request->all();
        $data['agent_id'] = $this->setDefaultNull($data['agent_id'] ?? null);
        $data['office_id'] = $this->setDefaultNull($data['office_id'] ?? null);
        //store image if exists
        if ($request->hasFile('image')) {
            $request->file = $request->image;

            $uploadImg = $this->fileService->uploadImage($request, 0, 0);
            if (!$uploadImg['success']) {
                return $this->backWithError($uploadImg['msg']);
            }

            $data['image_path'] = $uploadImg['fileName'];
        } else {
            $data['image_path'] = 'no_post_image.png';
        }
        $post = $this->postService->create($data);

        /*$access = $post->default == 1 ? "true" : "false";
        if ($post->office == null and $post->agent == null) {
            $this->toggleAcessAll($post, $access);
        } elseif ($post->office and $post->agent == null) {
            $this->toggleAcessAllForOffice($post->office, $post, $access);
        } elseif ($post->office and $post->agent) {
            $this->toggleAccess($post, $post->office, $access);
            AgentController::toggleAccess($post, $post->agent, $access);
        }
        if ((int)$data['default'] == 1) {
            $this->toggleAcessAll($post, 'true');
        } else {
            $this->toggleAcessAll($post, 'false');
        }*/

        return $this->backWithSuccess("Post created successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePost $request, Post $post)
    {
        //
        $data = $request->all();
        $data['agent_id'] = $this->setDefaultNull($data['agent_id'] ?? null);
        $data['office_id'] = $this->setDefaultNull($data['office_id'] ?? null);
        //store image if exists
        if ($request->hasFile('image_edit')) {
            $request->file = $request->image_edit;

            $uploadImg = $this->fileService->uploadImage($request, 0, 0);
            if (!$uploadImg['success']) {
                return $this->backWithError($uploadImg['msg']);
            }
            $data['image_path'] = $uploadImg['fileName'];
        }
        $post->update($data);

        /*if ((int)$data['default'] == 1) {
            $this->toggleAcessAll($post, 'true');
        } else {
            $this->toggleAcessAll($post, 'false');
        }*/

        return $this->backWithSuccess("Post updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function destroyAll()
    {
        Post::truncate();
        return $this->responseJsonSuccess((object)[]);
    }

    public function datatable()
    {
        return $this->postService->datatable();
    }

    public function updateColumn(Post $post, $column, $value)
    {
        if (in_array($column, ['price', 'loss_damage', 'time_days', 'renewal_fee'])) {
            return $post->update([$column => $value]);
        } else {
            abort(500);
        }
    }

    public function updatePostOfficeColumn($post, $office, $column, $value)
    {
        if (in_array($column, ['price', 'locked'])) {
            if ($column == 'locked') {
                $office = $this->officeService->findById($office);
                $office->locked = !$office->locked;
                $office->locked_price = $value;
                $office->save();
                return response(['done' => 1]);
            }
            $value = doubleval($value);
            PostOffice::where('post_id', $post)->where('office_id', $office)->update([$column => $value]);
            return response(['done' => 1]);
        }
    }

    public function toggleLocked(Post $post, Office $office, $access, $price, $locked)
    {

        $price = doubleval($price);

        if ($locked == "true") {
            PostOffice::updateOrCreate([
                'post_id' => $post->id,
                'office_id' => $office->id,
            ], [
                'post_id' => $post->id,
                'office_id' => $office->id,
                'price' => $price,
                'access' => $access == "true" ? 1 : 0,
                'locked' => $locked == "true" ? 1 : 0
            ]);

            return response()->json(['action' => 'create', 'ok' => 1]);
        } else {

            PostOffice::where('post_id', $post->id)
                ->where('office_id', $office->id)
                ->update(['locked' => $locked == "true" ? 1 : 0]);

            return response()->json(['action' => 'create', 'ok' => 1]);
        }

        return response()->json(['msg' => 'error', 'ok' => 0]);
    }

    public function toggleAccess(Post $post, Office $office, $access, $price = 0)
    {
        $post_office = PostOffice::where('post_id', $post->id)->where('office_id', $office->id);

        if ($post_office->exists()) {
            PostOffice::where('post_id', $post->id)
                ->where('office_id', $office->id)
                ->update(['access' => $access == "true" ? 1 : 0]);
            return response()->json(['action' => "update", 'ok' => 1]);
        } else {
            PostOffice::create([
                'post_id' => $post->id,
                'office_id' => $office->id,
                'price' => $price,
                'access' => 1,
            ]);
            return response()->json(['action' => "create", 'ok' => 1]);
        }
    }

    public function toggleAcessAll(Post $post, $access)
    {
        $offices = Office::all();
        foreach ($offices as $office) {
            $this->toggleAccess($post, $office, $access);
            foreach ($office->agents as $agent) {
                $agentContorller = AgentController::toggleAccess($post, $agent, $access);
            }
        }
        return response()->json($post);
    }
    public function toggleAcessAllForOffice(Office $office, Post $post, $access)
    {
        $this->toggleAccess($post, $office, $access);
        foreach ($office->agents as $agent) {
            $agentContorller = AgentController::toggleAccess($post, $agent, $access);
        }
        return response()->json($post);
    }
}
