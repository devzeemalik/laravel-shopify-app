<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\story;
use App\Traits\ValidationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoryCreateRequest;
use Carbon\Carbon;
use function Symfony\Component\String\s;


class StoriesController extends BaseController
{
    use ValidationTrait;
    public function getShopStories(Request $request) {
        try {
            $shop = Auth::user();
            $get_origin = $request->headers->get('origin');
            $origin   =  str_replace("https://","", $get_origin); 
            $shop = User::where('name', $origin)->first();
            // get Seeting from shop name;
            if (!$shop) {
                return 'Shop not found';
            }
            $stories = story::where('shop_id', '=', $shop->id)->get();
            if($stories->isEmpty()){
                return 'No record found';
            }
            return $stories;

        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'message' => $ex->getMessage(), 'data' => []]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $stories = story::where('shop_id', '=', 1)->get();
            return $stories;
            exit;    
            $shop = Auth::user();
            $stories = story::where('shop_id', '=', $shop->id)->get();
            if($stories->isEmpty()){
                return 'No record found';
            }
            return $stories;

        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'message' => $ex->getMessage(), 'data' => []]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoryCreateRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $shop = Auth::user();
            $data = $request->json()->all();
            $response = null;
            foreach ($data as $value) {
                $res = validateRequest($value, $this->storyCreateValidation['rules'], $this->storyCreateValidation['messages']);
                if ($res !== 'success') {return $res;}
                $story_title         = $value['story-title'];
                $publisher_logo_src  = $value['publisher-logo-src'];
                $poster_portrait_src = $value['poster-portrait-src'];
                $pages = $value['pages'];
                $description         = $value['description'];
                foreach ($pages as $page) {
                    $res = validateRequest($page, $this->storyPageValidation['rules'], $this->storyPageValidation['messages']);
                    if ($res !== 'success') {return $res;}
                }
                $html = view('/api-story', compact('story_title','publisher_logo_src', 'poster_portrait_src', 'pages', 'description'))->render();
                $insert = new story;
                $insert->shop_id         = $shop->id;
                $insert->title           = $story_title;
                $insert->description     = $description;
                $insert->body_html       = $html;
                $insert->featured_image  = $poster_portrait_src;
                $insert->logo_image      = $publisher_logo_src;
                $insert->save();
                return 'story added successfully';
            }
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'message' => $ex->getMessage(), 'data' => []]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $shop = Auth::user();
            $get_story = story::where(['shop_id' => $shop->id, 'id' => $id ])->first();
            if(empty($get_story)){
                return 'Enter a valid story id';
            }else{
                return $get_story;
                
            }
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'message' => $ex->getMessage(), 'data' => []]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $shop = Auth::user();
            $data = $request->json()->all();
            foreach ($data as $key => $value) {
                $res = validateRequest($value, $this->storyCreateValidation['rules'], $this->storyCreateValidation['messages']);
                if ($res !== 'success') {return $res;}
                $story_title         = $value['story-title'];
                $publisher_logo_src  = $value['publisher-logo-src'];
                $poster_portrait_src = $value['poster-portrait-src'];
                $pages               = $value['pages'];
                $description         = $value['description'];
                foreach ($pages as $page) {
                    $res = validateRequest($page, $this->storyPageValidation['rules'], $this->storyPageValidation['messages']);
                    if ($res !== 'success') {return $res;}
                }
                $html = view('/api-story', compact('story_title','publisher_logo_src', 'poster_portrait_src', 'pages', 'description'))->render();
                $updStory = story::where(['shop_id' => $shop->id, 'id' => $id ])->first();
                if(empty($updStory)){
                    return 'Enter a valid story id';
                }else{
                    $updStory->shop_id         = $shop->id;
                    $updStory->title           = $story_title;
                    $updStory->description     = $description;
                    $updStory->body_html       = $html;
                    $updStory->featured_image  = $poster_portrait_src;
                    $updStory->logo_image      = $publisher_logo_src;
                    $updStory->save();
                    return 'story Updated successfully';
                }

            }
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'message' => $ex->getMessage(), 'data' => []]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $shop = Auth::user();
            $data = story::where(['shop_id' => $shop->id, 'id' => $id ])->delete();
            if($data){
                return 'story deleted successfully';
            }else{
                return 'Enter a valid story id';
            }

        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'message' => $ex->getMessage(), 'data' => []]);
        }
    }

    public function addProxy(Request $request){
        $id        = $_GET['story_id'];
        $shop_id   =  $_GET['shop_id'];
        $get_story = story::where(['id' => $id, 'shop_id' => $shop_id])->first();
        if(!empty($get_story)){
             return $get_story->body_html;
         }else{
            return 'No Record Found';
         }
    }
    public function deleteStory(Request $request){
        try {
            $data = $request->json()->all();
            $shop_id   = $data['shop_id'];
            $story_id  = $data['id'];
            $data = story::where(['shop_id' => $shop_id, 'id' => $story_id ])->delete();
            return response()->json(['status' => 'Success', 'message' => 'Story Deleted Successfully']);
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'message' => $ex->getMessage(), 'data' => []]);
        }
    }
}
