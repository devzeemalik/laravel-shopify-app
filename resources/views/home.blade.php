@extends('shopify-app::layouts.default')
@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="{{ asset('/css/cards.css') }}" rel="stylesheet">
    <!--contect css -->
    <link href="{{ asset('/context/css/context.bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('/context/css/context.standalone.css') }}" rel="stylesheet">
    <script src="{{ asset('/context/js/context.js') }}"></script>
    <script src="{{ asset('/context/js/initialize.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <style type="text/css">
      .woofy_stories,
     	.woofy_setting{
     		padding: 20px;
     	}
     	.shopify_btn{
     		border: none;
     		background-color:#96BF48;
     		color: white;
     	}
    </style>
    <ul class="nav nav-tabs">
      <li class="nav-item">
        <a class="nav-link active  show_setting" aria-current="page" onclick="showSettingFunction()">Setting</a>
      </li>
      <li class="nav-item">
        <a class="nav-link show_webstories" onclick="showWebstoriesFunction()">Webstories</a>
      </li>
      <li class="nav-item">
        <a class="nav-link create_webstories" onclick="createWebstoriesFunction()">Add New WebStory</a>
      </li>
    </ul>
    <div class="woofy_setting">
    	<h5>Welcome to the stories for: {{ $shopDomain ?? Auth::user()->name }}</h5>

    	<form method="post" action="/api/upd_token" class="mb-3 mt-3" enctype="multipart/form-data" >
    	    {{ csrf_field() }}
            <table class="table table-borderless">
                <tr>
                    <td width="200px">API Token:</td>
                    <td width="300px">
                        <input name='api_secret_key'  class="form-control" type='text'  value="{{Auth::user()->user_token}}">
                        <input name='id' size='100' type='hidden'  value="{{Auth::user()->id}}">
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td width="200px"><label for="widget" class="form-label">Enable / Disable:</label></td>
                    <td width="300px">
                        <div class="form-check">
                            <input class="form-check-input" {{ auth()->user()->is_enabled ? 'checked' : '' }} type="checkbox" name="is_enabled" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                Enable WebStories to display on Front Pages.
                            </label>
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td width="200px">Selection Position:</td>
                    <td width="300px">
                        <select name='widget_position' class="form-select" aria-label="Widget placement options">
                            <option {{ auth()->user()->widget_position === "left" ? 'selected' : '' }} value="left">Left</option>
                            <option {{ auth()->user()->widget_position === "right" ? 'selected' : '' }} value="right">Right</option>
                        </select>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td width="200px">
                        <label for="widget" class="form-label">Upload Icon:</label>
                    </td>
                    <td width="300px">
                        <input name="widget" class="form-control" type="file" accept="image/*" id="widget_image">
                    </td>
                    <td>
                        @if(auth()->user()->widget_url)
                            <img width="200px" src="{{ asset('images') }}/{{ auth()->user()->widget_url }}" alt="woofy story widget">
                        @else
                            <img width="200px" src="{{ asset('img/logo.png') }}" alt="woofy story widget">
                        @endif
                    </td>
                </tr>
            </table>
    	    <p>
    	    <button class="shopify_btn">Update</button>
        </form>
    </div>
    <div class="woofy_stories" style="display:none;">
      <div class="row">
      <div class="col-md-3 ">
          <div class="row">
            <div class="col-md-12 ">
              <img src="{{ asset('/img/logo.png') }}" style="width:180px; padding-top: 30px;">
            </div>
         </div>
      </div>
      <div class="col-md-9 shopify_listing_stor" style="padding-top: 30px;">

        <div class="row  ">
          <div class="col-md-6  ">
          <h2>All Web Stories</h2>
          </div>
          <div class="col-md-6 ">
          <div class="text-right">
                <input type="search" class="max_admin_search" name="max_admin_search" placeholder="Search...">
          </div>
          </div>
          <div class="col-md-12">
          <hr>
          <?php
            $shop = Auth::user();
            $count = 0;
            foreach ($get_stories as $key => $value) {
              $count++;
            }
          ?>
          <h6>Viewing all <span class="max_story_count">{{$count}}</span> webstories</h6>
          </div>
        </div>
        <div class="row">
            <?php
            // max code start
            $count = 0;
            foreach ($get_stories as $key => $value) {
             $permalink = 'https://'.$shop->name.'/apps/single-story?shop_id='.$shop->id.'&story_id='.$value['id'].'';
             $class = 'max-'.$value['id'];
                ?>
              <div class="col-sm-4 col-6  p-4 max_story_cont {{$class}}" data-title="{{strtolower($value['title'])}}">
                  <div class="entry-point-card-container2  ">
                    <img src="{{$value['featured_image']}}" class="entry-point-card-img" alt="A cat">
                    <div class="author-container">
                    <div class="logo-container">
                      <div class="logo-ring"></div>
                      <img class="entry-point-card-logo" src="{{$value['logo_image']}}" alt="Publisher logo">
                    </div>
                    <span class="entry-point-card-subtitle">{{$value['title']}} </span>
                    </div>
                    <div class="card-headline-container_admin">
                      <a class="mws_admin_action" data-shop="{{ $shop->name }}" data-shop-id="{{ $shop->id }}" data-id="{{$value['id']}}">
                        <img src="{{ asset('/img/dot.png') }}">
                      </a>
                    </div>
                  </div>
              </div>
            <?php
            }
            //max code end
          ?>
        </div>
      </div>
      </div>
    </div>
    <div class="add_woofy_stories" style="display:none; padding: 20px;">
        <h1 class="p-2 text-info">Add New WebStories</h1>
<!--         <div class="alert alert-danger">
            <ul class="list-unstyled">
                @foreach ($errors->all() as $error)
                @if($errors->any())
                <script type="text/javascript">
                    alert('error');
                </script>
                @endif
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div> -->
        <form method="Post" action="{{ route('addStories') }}" class="mb-3 mt-3" >
            {{ csrf_field() }}
            <input name='mws_id' size='100' type='hidden'  value="{{Auth::user()->id}}">
            <?php
            ?>
            <table class="table table-borderless">
                <tbody>
                <tr>
                    <td width="200px">Title:</td>
                    <td width="550px">
                        <input name='story-title'  class="form-control" type='text'  required>
                    </td>
                    <td></td>
                </tr>
               <tr>
                    <td width="200px">Publisher Logo Src:</td>
                    <td width="550px">
                        <input name='publisher-logo-src' pattern='(https:\/\/)([^\s(["<,>/]*)(\/)[^\s[",><]*(.png|.jpg)(\?[^\s[",><]*)?' title="Enter a valid image url" class="form-control" type='text'  required>
                    </td>
                    <td></td>
                </tr>
                <tr>

                    <td width="200px">Poster Potrait Src:</td>
                    <td width="550px">
                        <input name='poster-portrait-src' pattern='(https:\/\/)([^\s(["<,>/]*)(\/)[^\s[",><]*(.png|.jpg)(\?[^\s[",><]*)?' title="Enter a valid image url"   class="form-control" type='text'  required>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td width="200px">Description:</td>
                    <td width="550px">
                        <input name='description'  class="form-control" type='text'  required>
                    </td>
                    <td></td>
                </tr>
                </tbody>
                <tbody class="mws_page_parent">
                <tr>
                    <td width="200px">Page Title:</td>
                    <td width="550px">
                        <input name="pages[0][page-title][title-text]" pattern="^.{1,26}$" title="Page title should not be greater than 26 characters" max="26" class="form-control" type='text'  required>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td width="200px">Page Description:</td>
                    <td width="550px">
                        <input name="pages[0][page-description][description-text]" pattern="^.{1,120}$" title="Page description should not be greater than 120 characters" max="120" class="form-control" type='text'  required>
                    </td>
                    <td></td>
                </tr>
                <tr>
                <td width="200px">Page Img:</td>
                    <td width="550px">
                        <input name="pages[0][page-image]" pattern='(https:\/\/)([^\s(["<,>/]*)(\/)[^\s[",><]*(.png|.jpg)(\?[^\s[",><]*)?' title="Enter a valid image url"  class="form-control"  type='text'  required>
                    </td>
                    <td></td>
                </tr>
                <tr>
                <td width="200px">Button:</td>
                    <td width="550px">
                        <input name="pages[0][button-info][button-text]" pattern="^.{1,40}$" title="Button text should not be greater than 40 characters" max="40" class="form-control" type='text'  required>
                    </td>
                    <td></td>
                </tr>
            </tbody>
            </table>
            <p>
            <button type="submit" class="btn btn-info m-2">Create</button>
            <button class="btn btn-dark mws_add_page">Add Page</button>
        </form>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
      jQuery(document).ready(function($) {
        context.init({preventDoubleContext: false});
        context.attach('.mws_admin_action', test_menu);
      });


    actions.TitleBar.create(app, { title: 'Stories' });
		function showSettingFunction() {
            // hide and show.
		    document.getElementsByClassName('woofy_stories')[0].style.display = 'none';
            document.getElementsByClassName('add_woofy_stories')[0].style.display = 'none';
		    document.getElementsByClassName('woofy_setting')[0].style.display = 'block';
            // add and remove class
            document.getElementsByClassName('show_webstories')[0].classList.remove("active");
            document.getElementsByClassName('create_webstories')[0].classList.remove("active");
            document.getElementsByClassName('show_setting')[0].classList.add("active");
		}
		function showWebstoriesFunction() {
		    document.getElementsByClassName('woofy_setting')[0].style.display = 'none';
            document.getElementsByClassName('add_woofy_stories')[0].style.display = 'none';
		    document.getElementsByClassName('woofy_stories')[0].style.display = 'block';
            // add and remove class
            document.getElementsByClassName('show_webstories')[0].classList.add("active");
            document.getElementsByClassName('create_webstories')[0].classList.remove("active");
            document.getElementsByClassName('show_setting')[0].classList.remove("active");
		}
        function createWebstoriesFunction() {
            document.getElementsByClassName('woofy_setting')[0].style.display = 'none';
            document.getElementsByClassName('add_woofy_stories')[0].style.display = 'block';
            document.getElementsByClassName('woofy_stories')[0].style.display = 'none';
            // add and remove class
            document.getElementsByClassName('show_webstories')[0].classList.remove("active");
            document.getElementsByClassName('create_webstories')[0].classList.add("active");
            document.getElementsByClassName('show_setting')[0].classList.remove("active");

        }

    // MAKE THE SEARCH BAR
    jQuery(".max_admin_search").on('keyup', function(){
      var inpVal = jQuery(this).val().toLowerCase();
      clearTimeout( window.wcpt_search_timeout );
      window.wcpt_search_timeout = setTimeout(function(){
      jQuery('.max_story_cont').each(function(){
        if(jQuery(this).attr('data-title').includes(inpVal)){
           jQuery(this).show();
        }else{
           jQuery(this).hide();
        }
      });
      }, 100);
    });

    var count1 = 1;

    // jquery append 
    jQuery(".mws_add_page").click(function(e){
        e.preventDefault();
        jQuery(".mws_page_parent").append('<tr class="'+ count1 +'">\
                    <td width="200px">Page Title:</td>\
                    <td width="550px">\
                        <input name="pages['+count1+'][page-title][title-text]"  pattern="^.{1,26}$" title="Page title should not be greater than 26 characters"  class="form-control" type="text"  required>\
                    </td>\
                    <td></td>\
                </tr>\
                <tr class="'+ count1 +'" >\
                    <td width="200px">Page Description:</td>\
                    <td width="550px">\
                        <input name="pages['+count1+'][page-description][description-text]"  pattern="^.{1,120}$" title="Page description should not be greater than 120 characters"  class="form-control" type="text"  required>\
                    </td>\
                    <td></td>\
                </tr>\
                <tr class="'+ count1 +'" >\
                <td width="200px">Page Img:</td>\
                    <td width="550px">\
                        <input name="pages['+count1+'][page-image]"   class="form-control" type="text"  required>\
                    </td>\
                    <td></td>\
                </tr>\
                <tr class="'+ count1 +'" >\
                <td width="200px">Button:</td>\
                    <td width="550px">\
                        <input name="pages['+count1+'][button-info][button-text]"  pattern="^.{1,40}$" title="Button text should not be greater than 40 characters"  class="form-control" type="text"  required>\
                    </td>\
                    <td><button data-remove1="'+count1+'" class="btn btn-danger mws_remove_page">Remove</button></td>\
                </tr>');
        count1 = count1 + 1;
    });

    jQuery(document).on('click', '.mws_remove_page', function (e) {
        e.preventDefault();
        var remove1 = jQuery(this).attr('data-remove1');
        jQuery(document).find('tbody.mws_page_parent .'+ remove1).remove();

    });

    

    </script>
@endsection


