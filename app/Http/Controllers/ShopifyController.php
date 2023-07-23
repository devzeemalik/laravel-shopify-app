<?php

namespace App\Http\Controllers;


use App\Models\Setting;
use App\Models\Session;
use App\Models\story;
use App\Models\User;
use Illuminate\Http\Request;
use Shopify\Clients\Rest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;



class ShopifyController extends Controller
{
    public function home(Request $request)
    {   
        $session = $request->get('shopifySession'); 
        $setting = Session::where('shop', $session->getShop())->first();
        $client = new Rest($session->getShop(), $session->getAccessToken());
        $theme_response = $client->get("/admin/api/2022-01/themes.json");
        $res_body = $theme_response->getDecodedBody();

        if (!isset($setting->api_token) || !isset($setting->blog_id)) {
            $setting = $this->initialize($setting);
        }

        $setting->widget_url = $setting->widget_url ? config("app.url") . '/uploads/' . $setting->widget_url : asset('img/logo.png');
        $get_stories = story::where('shop_id', '=', $setting->id)->get();
        
        return response()->json(['status' => 'success', 'message' => 'Success', 'data' => ['shop_name' => $session->getShop(), 'stories' => $get_stories, 'settings' => $setting]]);
    }

    public function updateSettings(Request $request)
    {   
        $session = $request->get('shopifySession');
        $validator = Validator::make($request->all(), [
            'api_token' => 'required|min:16',
            'widget_position' => 'required',
            'is_enabled' => 'required',
        ]);

        $data = [
            'api_token' => $request->api_token,
            'widget_position' => $request->widget_position,
            'is_enabled' => $request->is_enabled,
        ];
        
         
        if (!empty($request->file)) {
            $fileName = time() .'.'. $request->file->extension();
            $request->file->move(public_path('uploads'), $fileName);
            $data['widget_url'] = $fileName;
        }
        Session::where('shop', $session->getShop())->update($data);
        
        // update token in users tbl.
        User::where('name', $session->getShop())->update(['api_token' => $request->api_token]);

        if($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }
        return response()->json(['success' => 'Todo Added'],200);

        $session = $request->get('shopifySession');
        $setting = Session::where('session_id', $session->getShop())->first();
        return response()->json(['status' => 'success', 'message' => 'Success', 'data' => ['stories' => $get_stories, 'settings' => $setting]]);
    }

    public function initialize($setting)
    {   
        if (!isset($setting->api_token)) {
            $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            $setting->api_token = substr(str_shuffle($str_result), 0, 32);
        }

        $user = User::where('name', $setting->shop)->first();
        if (!$user) {
            // store user.
            $user = User::create([
                'name' => $setting->shop,
                'api_token' => $setting->api_token
            ]);
            $user->save();
        }
        
       

        if (!isset($setting->blog_id)) {
            $setting->blog_id = ensureBlog($setting);
            ensureAssets($setting);
        }

        generateWebhook($setting);

        $setting->save();
        return $setting;
    }


    public function store(Request $request)
    {  
        try {
            $session = $request->get('shopifySession');
            $get_session = Session::where('shop', $session->getShop())->first();
            $get_story  = $request->json()->all();
            $story_data = $get_story['webStory'];
            $pages      = $get_story['pages'];
         
            $story_title         = $story_data['title'];
            $publisher_logo_src  = $story_data['Publisher'];
            $poster_portrait_src = $story_data['Poster'];
            $description         = $story_data['Description'];

            $html = view('/story', compact('story_title', 'publisher_logo_src', 'poster_portrait_src', 'pages', 'description'))->render();
           
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