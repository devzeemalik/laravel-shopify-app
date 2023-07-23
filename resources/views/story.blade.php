<!DOCTYPE html>
<html amp lang="en">
    <head>
	    <meta charset="utf-8" />
	    <script async src="https://cdn.ampproject.org/v0.js"></script>
	    <script async custom-element="amp-story" src="https://cdn.ampproject.org/v0/amp-story-1.0.js" ></script>
	    <title>{{$story_title}}</title>
	    <link rel="canonical" href="http://example.ampproject.org/my-story.html" />
	    <script async custom-element="amp-video" src="https://cdn.ampproject.org/v0/amp-video-0.1.js"></script>
	    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1" />
	    <style amp-boilerplate>
		    body {
		        -webkit-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
		        -moz-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
		        -ms-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
		        animation: -amp-start 8s steps(1, end) 0s 1 normal both;
		   	}
		    @-webkit-keyframes -amp-start {
		        from {
		          visibility: hidden;
		        }
		        to {
		          visibility: visible;
		        }
		    }
		    @-moz-keyframes -amp-start {
		        from {
		          visibility: hidden;
		        }
		        to {
		          visibility: visible;
		        }
		    }
		    @-ms-keyframes -amp-start {
		        from {
		          visibility: hidden;
		        }
		        to {
		          visibility: visible;
		        }
		    }
		    @-o-keyframes -amp-start {
		        from {
		          visibility: hidden;
		        }
		        to {
		          visibility: visible;
		        }
		    }
		    @keyframes -amp-start {
		        from {
		          visibility: hidden;
		        }
		        to {
		          visibility: visible;
		        }
		    }
	    </style>
	    <noscript >
	    	<style amp-boilerplate>
		        body {
		          -webkit-animation: none;
		          -moz-animation: none;
		          -ms-animation: none;
		          animation: none;
		        }
	        </style>
	    </noscript>
  	</head>
  	<body>
	    <amp-story
	      standalone
	      title="{{$story_title}}"
	      publisher="The AMP Team"
	      publisher-logo-src="{{$publisher_logo_src}}"
	      poster-portrait-src="{{$poster_portrait_src}}">
	        @php 
				$index = 0;  
			@endphp
	        @foreach ($pages as $page)	
	        @php 
				$index++;  
			@endphp			
	        <amp-story-page id="{{$index}}" auto-advance-after="7s"  >
	        	<amp-story-grid-layer template="fill">
	                @php
		        	if ( pathinfo($page['pageImage'], PATHINFO_EXTENSION) == 'mp4' ) {
		        			@endphp
					    <amp-video autoplay
						  width="640"
						  height="360"
						  layout="responsive"
						  poster="{{$page['pageImage']}}">
						  <source src="{{$page['pageImage']}}" />
						  <source src="{{$page['pageImage']}}" />
						  <div fallback>
						    <p>This browser does not support the video element.</p>
						  </div>
						</amp-video>
					@php
					} elseif ( pathinfo($page['pageImage'], PATHINFO_EXTENSION) == 'wav' ) {
					    @endphp
	            		<amp-audio autoplay
							  width="400"
							  height="300"
							  layout="nodisplay"
							  src="{{$page['pageImage']}}" style="width:400px; height: 300px;">
							  <div fallback>
							    <p>Your browser doesn’t support HTML5 audio</p>
							  </div>
						</amp-audio>
						@php

						
					} elseif ( pathinfo($page['pageImage'], PATHINFO_EXTENSION) != 'mp4' ) {
						$max_page_vid = $page['pageImage'];
						list($width, $height, $type, $attr) = getimagesize($page['pageImage']);
						if($width > 720){
                                    @endphp
                                    <amp-img src="{{$page['pageImage']}}" width="720" height="1280" layout="flex-item"    animate-in="pan-right" animate-in-duration="3s" animate-in-delay="1s" >
									</amp-img>
                                    @php
						}elseif ($height > 1280) {
								    @endphp
								    <amp-img src="{{$page['pageImage']}}" width="720" height="1280" layout="responsive" animate-in="pan-down" >
									</amp-img>
								    @php
						}else{
                                    @endphp
                                    <amp-img src="{{$page['pageImage']}}" width="720" height="1280" layout="responsive">
									</amp-img>
                                    @php
						}
					}
					@endphp
	        	</amp-story-grid-layer>
		        <amp-story-grid-layer template="vertical">
		        	<?php
		        	if(!empty($page['pageTitle'])){
		        		?>
		        		<h1>{{$page['pageTitle']}}</h1>
				    	<br>
		        		<?php
		        	}
		        	if(!empty($page['pageDescription'])){
		        		?>
		        		<p >{{$page['pageDescription']}}</p>
		        		<?php
		        	}
		        	?>
				   
					<div style="text-align: center;position: absolute;bottom: 100px;">
				    <a  style="color: #FFFFFF;padding: 10px 24px;background-color: #000000;border-radius: 10px;">{{$page['pageBtn']}}</a>
				    </div>
				</amp-story-grid-layer>
	      	</amp-story-page>
	      @endforeach
	    </amp-story>
    </body>
</html>
