<?php

use App\Classes\ResponseUtils;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Shopify\Clients\Rest;

// Creates a blog template for api's blog posts.
/**
 * @throws \Shopify\Exception\UninitializedContextException
 * @throws \Shopify\Exception\MissingArgumentException
 * @throws JsonException
 * @throws \Psr\Http\Client\ClientExceptionInterface
 */
function ensureBlog($setting)
{   
    $client = new Rest($setting->shop, $setting->access_token);
    $check_blog = $client->get("/admin/api/2022-01/blogs/$setting->blog_id.json");
    $res_body = $check_blog->getDecodedBody();
    if (isset($res_body['errors']) && $res_body['errors'] == "Not Found") {
        $data = [
            "blog" => [
                "title" => "Hello to Woofy Stories",
                "metafields" => [
                    [
                        "key" => "author",
                        "value" => "Hello Woofy",
                        "type" => "single_line_text_field",
                        "namespace" => "global"
                    ]
                ]
            ]
        ];

        $blogsApi = $client->post('/admin/api/2022-01/blogs.json', $data);
        $res_body = $blogsApi->getDecodedBody();
      
        Log::info("Blogs API object: " . json_encode($res_body));
        if (!isset($res_body['errors'])) {
            return $res_body['blog']['id'];
        }

        return 'error';
    } 
}

// Create layout and blog template if already not exist
function ensureAssets($setting)
{
    $client = new Rest($setting->shop, $setting->access_token);
    $theme_response = $client->get("/admin/api/2022-01/themes.json");
    $res_body = $theme_response->getDecodedBody();
    if (!isset($res_body['errors'])) {
        $theme_id = null;
        foreach ($res_body['themes'] as $theme) {
            if ($theme['role'] === 'main') {
                $theme_id = $theme['id'];
            }
        }

        if ($theme_id) {
            ensureThemeModification($setting, $theme_id);
            $theme_files = $client->get("/admin/api/2022-01/themes/$theme_id/assets.json");

            $res_body = $theme_files->getDecodedBody();

            if (isset($res_body['errors'])) {
                return response()->json(['status' => 'error', 'message' => $res_body['errors'], 'data' => []]);
            }

            $all_assets = $res_body['assets'];
            $theme_layout = '';
            $theme_template = '';
            $theme_section = '';
            foreach ($all_assets as $file) {
                if ($file['key'] == 'layout/theme.hello-woofy.liquid') {
                    $theme_layout = 'exist';
                }
                if ($file['key'] == 'templates/article.hello-woofy.liquid') {
                    $theme_template = 'exits';
                }
                if ($file['key'] == 'sections/hello-woofy.liquid') {
                    $theme_section = 'exits';
                }
            }
            if (empty($theme_layout)) {
                $layout = [
                    "asset" => [
                        "key" => "layout/theme.hello-woofy.liquid",
                        "value" => "{{content_for_header}}{{content_for_layout}}"
                    ]
                ];
                $client->put("/admin/api/2022-01/themes/$theme_id/assets.json", $layout);
            }
            if (empty($theme_template)) {
                $template = [
                    "asset" => [
                        "key" => "templates/article.hello-woofy.liquid",
                        "value" => "{% layout 'hello-woofy' %}{{ article.content }}<style>
                                            div#shopify-section-header {
                                            display: none;
                                            }
                                            div#shopify-section-footer {
                                            display: none;
                                            }
                                            .announcement-bar__message {
                                                display: none;
                                            }
                                            </style>"
                    ]
                ];
                $client->put("/admin/api/2022-01/themes/$theme_id/assets.json", $template);
            }
            // Create section.
            if (empty($theme_section)) {
                $widget_img = $setting->widget_url ? config("app.url") . 'images/' . $setting->widget_url : asset('img/logo.png');
                $widget_position = $setting->widget_position === 'left' ? "left:100px;" : "right:100px;";
                $cards_css  = asset('css/cards.css');
                $button = $setting->is_enabled ? '<a id="mws_myBtn" href="#"><img src ="' . $widget_img . '" style = "position:fixed; bottom:20px; ' . $widget_position . ' width: 200px; z-index: 999" alt="Hello Woofy Stories" /></a>' : "";
                ?>
                <input type="hidden" name="max_story_name" class="max_story_name" value="<?php echo $setting->shop;?>">
                <input type="hidden" name="max_story_id" class="max_story_id" value="<?php echo $setting->shop;?>">
                <?php
                $stories_url = 'https://' . $setting->shop . '/apps/single-story?id=1&shop_id=45';
                $section = [
                    "asset" => [
                        "key" => "sections/hello-woofy.liquid",
                        "value" => '<section class="welcome">' . $button . '
                                        <input type="hidden" name="max_story_name" class="max_story_name" value="' . $setting->shop . '">
                                        <input type="hidden" name="max_story_id" class="max_story_id" value="' . $setting->id . '">
                                        <input type="hidden" name="max_story_logo" class="max_story_logo" value="' . $widget_img . '">
                                          <!-- The Modal -->
                                          <div id="mws_myModal" class="modal">
                                            <!-- Modal content -->
                                            <div class="modal-content">
                                              <span class="close">&times;</span>
                                               <div class="mws_modal_content_cont">
                                                <div class="modal_content_lft" ></div>
                                                 <div class="modal_content_rght" >
                                                   <div class="main_cont"></div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                    </section>
                                    {% schema %}
                                        {
                                            "name": "Section name",
                                            "settings": [],
                                            "presets": [
                                                {
                                                    "name": "Hello Woofy",
                                                    "category": "ADVANCED LAYOUT"
                                                }
                                            ]
                                        }
                                    {% endschema %}
                                    <link rel="stylesheet" href="' . $cards_css . '">
                                     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                                    <script>
                                    $(document).ready(function(){
                                      var settings = {
                                        "url": "' . config("app.url") . '/api/get/shop/stories",
                                        "method": "GET",
                                        "timeout": 0,
                                      };
                                      $.ajax(settings).done(function (response) {
                                        if(response !=="" && response !== "undefined" ){
                                            if(response == "No record found"){
                                                console.log("No Record Found");
                                            }else{
                                                var story_logo = $(".max_story_logo").val();
                                                $(".main_cont").empty();
                                                $(".modal_content_lft").append("<a href=https://app.hellowoofy.com/><h4 class=mwx_power_by>Powered by</h4><img src="+story_logo+" style=vertical-align:middle width=220></a>");
                                                  var story_name = $(".max_story_name").val();
                                                  var shop_id = $(".max_story_id").val();
                                                  var story_url = "https://"+story_name+"/apps/single-story?shop_id="+ shop_id;
                                                  $.each(response, function( index, value ) {
                                                  var story_url_single = story_url + "&story_id="+value.id;
                                                  $(".main_cont").append("<div class=single_story_cont><a class=story_link href="+story_url_single+" ><div class=entry-point-card-container ><div class=background-cards><div class=background-card-1></div><div class=background-card-2></div></div><img src="+value.featured_image+" class=entry-point-card-img ><div class=author-container><div class=logo-container><div class=logo-ring></div><img class=entry-point-card-logo src="+value.logo_image+" ></div><span class=entry-point-card-subtitle>"+value.title+"</span></div><div class=card-headline-container><span class=entry-point-card-headline>"+value.description+"</span></div></div></a></div>");
                                            });
                                            }
                                         }
                                      });

                                    });

                                    $("#mws_myBtn").click(function(){
                                      $("#mws_myModal").css("display","block");
                                    });

                                    $(".close").click(function(){
                                      $("#mws_myModal").css("display","none");
                                    });
                                    </script>'
                    ]
                ];
                $client->put("/admin/api/2022-01/themes/$theme_id/assets.json", $section);
            }
        }
    }
}

function ensureThemeModification($setting, $theme_id)
{
    $client = new Rest($setting->shop, $setting->access_token);
    $response = $client->get("/admin/api/2022-01/themes/$theme_id/assets.json?asset[key]=layout/theme.liquid");
    $res_body = $response->getDecodedBody();
    if (isset($res_body['errors'])) {
        return response()->json(['status' => 'error', 'message' => $res_body['errors'], 'data' => []]);
    }
    $layout = $res_body['asset']['value'];
    if (strpos($layout, "hello-woofy-stories-modification-widget") === false) {
         $widget_body_end = '</html>{% endif %}';
         $widget_body = '<!doctype html>
                            {% if template == "article.hello-woofy" %}
                        <html lang="en">
                          <head>
                                    <meta charset="utf-8">
                                    <title>{{ article.title }}</title>
                                    <link rel="canonical" href="{{ article.url }}">
                                    <meta name="viewport" content="width=device-width">
                                    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
                                    <script async src="https://cdn.ampproject.org/v0.js"></script>
                                    <script async custom-element="amp-video" src="https://cdn.ampproject.org/v0/amp-video-0.1.js"></script>
                                    <script async custom-element="amp-story" src="https://cdn.ampproject.org/v0/amp-story-1.0.js"></script>
                                    <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
                                    <script async custom-element="amp-audio" src="https://cdn.ampproject.org/v0/amp-audio-0.1.js"></script>
                                    <style amp-custom>
                                    </style>
                          </head>
                          <body>
                          {{ content_for_layout }}
                          </body>
                        </html>
                        {% else %}
                        ';
        $layout = str_replace('</html>', $widget_body_end, $layout);
        $layout = str_replace('<!doctype html>', $widget_body, $layout);
        // $layout = substr_replace($layout, $widget_body, strpos($layout,"<!doctype html>"), 15);
        $layout = [
            "asset" => [
                "key" => "layout/theme.liquid",
                "value" => $layout
            ]
        ];
        $client->put("/admin/api/2022-01/themes/$theme_id/assets.json", $layout);
    }
}

function generateWebhook($setting)
{   
    $client = new Rest($setting->shop, $setting->access_token);
    $get_webhooks = $client->get("/admin/api/2022-04/webhooks.json");
    $res_body = $get_webhooks->getDecodedBody();
    $all_webhooks = $res_body['webhooks'];
    $exist = '';

    if (!empty($all_webhooks)) {
        foreach ($all_webhooks as $value) {
            if ($value['topic']  == 'themes/publish') {
                  $exist = 'exist';
            }
        }
    }
    if (empty($exist)) {
        $data = [
                "webhook" => [
                    "address" => config("app.url") . "webhook/theme/publish/$setting->shop",
                    "topic" => 'themes/publish',
                    "format" => 'json'
                ]
        ];
        $response = $client->post("/admin/api/2022-04/webhooks.json", $data);
    }
}

function validateRequest($data, $rules, $messages = [])
{
    $orderValidated = Validator::make($data, $rules, $messages);
    if ($orderValidated->fails()) {
        return ResponseUtils::invalidInput($orderValidated->errors()->toArray());
    }

    return 'success';
}

function uploadWidget($widget)
{
    $imageName = time() . '.' . $widget->extension();
    $widget->move(public_path('images'), $imageName);
    return $imageName;
}

function handleSettingsChange($session, $setting)
{
    $widget_img = $setting->widget_url ? config('app.url') . 'images/' . $setting->widget_url : asset('img/logo.png');
    $widget_position = $setting->widget_position === 'left' ? "left:100px;" : "right:100px;";
    $cards_css  = asset('css/cards.css');

    $client = new Rest($session->getShop(), $session->getAccessToken());
    $theme_response = $client->get("/admin/api/2022-01/themes.json");
    $res_body = $theme_response->getDecodedBody();

    if (!$res_body['errors']) {
        $theme_id = null;
        foreach ($res_body['themes'] as $theme) {
            if ($theme['role'] === 'main') {
                $theme_id = $theme['id'];
            }
        }

        if ($theme_id) {
            $section = [
                "asset" => [
                    "key" => "sections/hello-woofy.liquid",
                    "value" => view('woofy-liquid', compact(['settings', 'widget_img', 'cards_css', 'widget_position']))->render()
                ]
            ];

            $client->put("/admin/api/2022-01/themes/$theme_id/assets.json", $section);
        }
    }
}
