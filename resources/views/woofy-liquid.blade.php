@extends('shopify-app::layouts.default')
@section('content')
<section class="welcome">
    @if(auth()->user()->is_enabled)
    <a id="mws_myBtn" href="#">
        <img
            src ="{{ $widget_img }}"
            style="position:fixed; bottom:20px; {{ $widget_position }} width: 200px; z-index: 999"
            alt="Hello Woofy Stories" />
    </a>
    @endif
    <input type="hidden" name="max_story_name" class="max_story_name" value="{{ $settings->session_id }}">
    <input type="hidden" name="max_story_id" class="max_story_id" value="{{ $settings->session_id }}">
    <input type="hidden" name="max_story_logo" class="max_story_logo" value="{{ $widget_img }}">
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
<link rel="stylesheet" href="{{ $cards_css }}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
@endsection
@section('scripts')
@parent
<script>
    // ajax call
    $(document).ready(function(){
        var settings = {
            "url": "{{ config("app.url") }}api/get/shop/stories",
            "method": "GET",
            "timeout": 0,
        };
        $.ajax(settings).done(function (response) {
            if(response !=="" && response !== "undefined" ){
                if(response === "No record found"){
                    console.log("No Record Found");
                }else{
                    const story_logo = $(".max_story_logo").val();
                    $(".main_cont").empty();
                    $(".modal_content_lft").append("<a href=https://app.hellowoofy.com/><h4 class=mwx_power_by>Powered by</h4><img src="+ story_logo +" style=vertical-align:middle width=220></a>");
                    var story_name = $(".max_story_name").val();
                    var shop_id = $(".max_story_id").val();
                    var story_url = "https://"+ story_name +"/apps/single-story?shop_id="+ shop_id;
                    $.each(response, function( index, value ) {
                        const story_url_single = story_url + "&story_id=" + value.id;
                        $(".main_cont").append("<div class=single_story_cont>" +
                                                    "<a class=story_link href="+ story_url_single +" >" +
                                                        "<div class=entry-point-card-container >" +
                                                            "<div class=background-cards>" +
                                                                "<div class=background-card-1></div>" +
                                                                "<div class=background-card-2></div>" +
                                                            "</div>" +
                                                            "<img src="+ value.featured_image +" class=entry-point-card-img >" +
                                                            "<div class=author-container>" +
                                                                "<div class=logo-container>" +
                                                                    "<div class=logo-ring></div>" +
                                                                    "<img class=entry-point-card-logo src="+value.logo_image+" >" +
                                                                "</div>" +
                                                                "<span class=entry-point-card-subtitle>"+value.title+"</span>" +
                                                            "</div>" +
                                                            "<div class=card-headline-container>" +
                                                                "<span class=entry-point-card-headline>"+value.description+"</span>" +
                                                            "</div>" +
                                                        "</div>" +
                                                    "</a>" +
                                                "</div>");
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
</script>
@endsection

