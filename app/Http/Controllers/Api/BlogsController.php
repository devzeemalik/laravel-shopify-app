<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoryCreateRequest;
use App\Http\Controllers\ShopifyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Shopify\Clients\Rest;
use App\Models\Session;

class BlogsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /* get all blogs */
    public function getAllBlogs()
    {
        try {
            $user = Auth::user();
            $setting = Session::where('shop', $user->name)->first();
            $client = new Rest($setting->shop, $setting->access_token);
            $check_blog = $client->get("/admin/api/2022-01/blogs.json");
            $res_body = $check_blog->getDecodedBody();
            if ($res_body['blogs']){
                return response()->json(['status' => 'success', 'message' => 'blogs fetched successfully', 'data' => $res_body['blogs']]);
            }
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'message' => $ex->getMessage(), 'data' => []]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            if (!isset($request->blog_id)){
                return response()->json(['status' => 'error', 'message' => 'blog id is required', 'data' => []]);
            }
            $blog_id = $request->blog_id;
            $user = Auth::user();
            $setting = Session::where('shop', $user->name)->first();
            $client = new Rest($setting->shop, $setting->access_token);
            $check_blog = $client->get("/admin/api/2022-01/blogs/$blog_id/articles.json");
            $res_body = $check_blog->getDecodedBody();
            if ($res_body['articles']){
                return response()->json(['status' => 'success', 'message' => 'blog posts fetched successfully', 'data' => $res_body['articles']]);
            }
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
            if (!isset($request->blog_id)){
                return response()->json(['status' => 'error', 'message' => 'blog id is required', 'data' => []]);
            }
            $blog_id = $request->blog_id;
            $data = $request->json()->all();
            $article = [
                "article" => [
                    "title" => $data['title'],
                    "author" => $data['author'],
                    "tags" => $data['tags'],
                    "body_html" => $data['body_html'],
                    "published_at" => $data['published_at'],
                    "image" => [
                            "src" => $data['image']
                    ]
                ]
            ];
            $user = Auth::user();
            $setting = Session::where('shop', $user->name)->first();
            $client = new Rest($setting->shop, $setting->access_token);
            $response = $client->post("/admin/api/2022-01/blogs/$blog_id/articles.json", $article);
            $res_body = $response->getDecodedBody();
            if ($res_body['article']){
                return response()->json(['status' => 'success', 'message' => 'blog post added successfully', 'data' => $res_body['article']]);
            }
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'message' => $ex->getMessage(), 'data' => []]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        try {
            if (!isset($request->blog_id)){
                return response()->json(['status' => 'error', 'message' => 'blog id is required', 'data' => []]);
            }
            $blog_id = $request->blog_id;
            $user = Auth::user();
            $setting = Session::where('shop', $user->name)->first();
            $client = new Rest($setting->shop, $setting->access_token);
            $response = $client->get("/admin/api/2022-01/blogs/$blog_id/articles/$id.json");
            $res_body = $response->getDecodedBody();
            if ($res_body['article']){
                return response()->json(['status' => 'success', 'message' => 'blog post fetched successfully', 'data' => $res_body['article']]);
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
            if (!isset($request->blog_id)){
                return response()->json(['status' => 'error', 'message' => 'blog id is required', 'data' => []]);
            }
            $blog_id = $request->blog_id;
            $shop = Auth::user();
            $data = $request->json()->all();
            $article = [
                "article" => [
                    "title" => $data['title'],
                    "author" => $data['author'],
                    "tags" => $data['tags'],
                    "body_html" => $data['body_html'],
                    "published_at" => $data['published_at'],
                    "image" => [
                            "src" => $data['image']
                    ]
                ]
            ];
            $user = Auth::user();
            $setting = Session::where('shop', $user->name)->first();
            $client = new Rest($setting->shop, $setting->access_token);
            $response = $client->put("/admin/api/2022-01/blogs/$blog_id/articles/$id.json", $article);
            $res_body = $response->getDecodedBody();
            if ($res_body['article']){
                return response()->json(['status' => 'success', 'message' => 'blog post updated successfully', 'data' => $res_body['article']]);
            }
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'message' => $ex->getMessage(), 'data' => []]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        try {
            if (!isset($request->blog_id)){
                return response()->json(['status' => 'error', 'message' => 'blog id is required', 'data' => []]);
            }
            $blog_id = $request->blog_id;
            $user = Auth::user();
            $setting = Session::where('shop', $user->name)->first();
            $client = new Rest($setting->shop, $setting->access_token);
            $response = $client->delete("/admin/api/2022-01/blogs/$blog_id/articles/$id.json");
            $res_body = $response->getDecodedBody();
            if ($res_body){
                return response()->json(['status' => 'success', 'message' => 'blog post deleted successfully']);
            }
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'message' => $ex->getMessage(), 'data' => []]);
        }
    }
}
