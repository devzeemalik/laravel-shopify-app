<?php

namespace App\Http\Controllers;

use App\Models\story;
use App\Models\Session;
use App\Models\User;
use App\Traits\ValidationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class StoriesController extends Controller
{
    use ValidationTrait;

    public function store(Request $request)
    {  
        try {
            $data = $request->json()->all();
            $session = $request->get('shopifySession');
            $get_session = Session::where('shop', $session->getShop())->first();
            $get_story  = $request->json()->all();
            $story_data = $get_story['webStory'];
            $pages      = $get_story['pages'];
         
            $story_title         = $story_data['title'];
            $publisher_logo_src  = $story_data['Publisher'];
            $poster_portrait_src = $story_data['Poster'];
            $description         = $story_data['Description'];

            $html = view('/api-story', compact('story_title', 'publisher_logo_src', 'poster_portrait_src', 'pages', 'description'))->render();
           
            $insert = new story();
            $insert->shop_id         = $get_session->id;
            $insert->title           = $story_title;
            $insert->description     = $description;
            $insert->body_html       = $html;
            $insert->featured_image  = $poster_portrait_src;
            $insert->logo_image      = $publisher_logo_src;
            $insert->save();
            return response()->json(['status' => 'success', 'message' => 'Success', 'data' => []]);
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'message' => $ex->getMessage(), 'data' => []]);
        }
    }
}


