<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Token;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends BaseController
{
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {   
        $data = $request->json()->all();
        $user = User::where(['name' => $data['shop_name'], 'api_token' => $data['api_secret_key']])->first();
        if($user){
            $token = new Token;
            $token->user_id = $user->id; $token->token = Str::random(60);
            $token->save();
            return response()->json([ 'token' => $token->token, ]);
            // Auth::login($user);
            // $user = Auth::user();
            // $token = $user->createToken('API Token')->plainTextToken;
            // return response()->json([
            //     'access_token' => $token,
            //     'token_type' => 'Bearer',
            // ]);
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised! Login to your Shopify store to ']);
        }
    }



    /* Update token from shopify app */
    public function upd_token(Request $request){
        $user = User::where(['id' => $request->id])->first();
        if($user){
            Auth::login($user);
            $user = Auth::user();

            if ($request->has('widget')) {
                $widget = uploadWidget($request->widget);
                $user->widget_url = $widget;
            }

            $user->is_enabled = $request->has('is_enabled') ? 1 : 0;
            $user->widget_position = $request->widget_position;
            $user->user_token = $request->api_secret_key;
            $user->save();

            // update settings for shopify e.g. reset widget position and file, enabled or disabled
            handleSettingsChange($user);

            return redirect()->back()->with('message', 'Settings saved successfully ');
//            return $this->sendResponse('Success.', ('Settings saved successfully '));
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised! Login to your Shopify store to ']);
        }

    }






}
