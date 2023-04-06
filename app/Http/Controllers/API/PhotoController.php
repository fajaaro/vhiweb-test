<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\PhotoLike;
use App\Models\PhotoTag;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success(
            Photo::with('tags')->latest()->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo'     => 'required|image|max:5024',
            'caption'  => 'nullable',
            'tags' => 'nullable'
        ]);

        if ($validator->fails()) {
            return $this->failure($validator->errors()->first(), 400);
        }

        $photoFile = $request->file('photo');
        $photoPath = Storage::putFile('photos', $photoFile);

        $tags = $request->tags;
        $arrTags = explode(' ', $tags);

        $photo = new Photo();
        $photo->user_id = $request->user()->id;
        $photo->caption = $request->caption;
        $photo->photo_path = $photoPath;
        $photo->save();

        foreach ($arrTags as $tagName) {
            if ($tagName != null) {
                $photoTag = new PhotoTag();
                $photoTag->photo_id = $photo->id;
                $photoTag->tag_name = $tagName;
                $photoTag->save();    
            }
        }        

        $photo->tags;

        return $this->success($photo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $photo = Photo::find($id);

        if (!$photo) return $this->failure('Photo not found.', 404);

        $photo->tags;
        $photo->likes;

        if ($request->type == 'data') return $this->success($photo);
        else return Storage::download($photo->photo_path);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $photo = Photo::find($id);

        if (!$photo) return $this->failure('Photo not found.', 404);

        $tags = $request->tags;
        $arrTags = explode(' ', $tags);

        $photo->caption = $request->caption;
        $photo->save();

        $photo->tags()->delete();
        foreach ($arrTags as $tagName) {
            if ($tagName != null) {
                $photoTag = new PhotoTag();
                $photoTag->photo_id = $photo->id;
                $photoTag->tag_name = $tagName;
                $photoTag->save();    
            }
        }        

        $photo->tags;

        return $this->success($photo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $photo = Photo::find($id);

        if (!$photo) return $this->failure('Photo not found.', 404);

        Storage::delete($photo->photo_path);

        $photo->delete();

        return $this->success(['message' => 'Photo deleted.']);
    }

    public function like(Request $request, string $id)
    {
        $photo = Photo::find($id);

        if (!$photo) return $this->failure('Photo not found.', 404);

        $existsStatus = PhotoLike::where([
            ['user_id', $request->user()->id],
            ['photo_id', $id],
        ])->exists();

        if (!$existsStatus) {
            $like = new PhotoLike();
            $like->user_id = $request->user()->id;
            $like->photo_id = $id;
            $like->save();    
        }

        return $this->success(['message' => 'Like success.']);
    }

    public function unlike(Request $request, string $id)
    {
        $photo = Photo::find($id);

        if (!$photo) return $this->failure('Photo not found.', 404);

        PhotoLike::where([
            ['user_id', $request->user()->id],
            ['photo_id', $id],
        ])->delete();
        
        return $this->success(['message' => 'Unlike success.']);
    }

}
