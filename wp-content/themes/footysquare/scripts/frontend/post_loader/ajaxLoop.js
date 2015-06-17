// ajaxLoop.js
jQuery(function($){
    var page = 1;
    var loading = true;
    var $window = $(window);
    var $content = $('#shoutbox-content');
	var current_post_className = $('#shoutbox-content').attr('class');
	var post_type ='';
	
	if(current_post_className)
	{
		if(current_post_className.match(/posttype/g)){
			post_type = current_post_className.split('-')[1];
			//alert(post_type);
		}
		
		if(current_post_className)
			current_post_id = current_post_className.replace( /^\D+/g, '');
	/*else
		current_post_id=0;*/
	
	//var $content = $('#shoutbox-content'+post_type);
	
	//get filter value
	var e = document.getElementById("filter-post");
	if(e)
		var filter_opt = e.options[e.selectedIndex].value;
	
	if(!(filter_opt))
		filter_opt=0;
	
	var load_posts = function(){
            $.ajax({
                type       : "GET",
                data       : {numPosts : 5, pageNumber: page},
                dataType   : "html",
                url        : "/fs/wp-content/themes/footysquare/loopHandler.php?pid="+current_post_id+"&filter-post="+filter_opt+"&post_type="+post_type,
                beforeSend : function(){
                    if(page != 1){
                        $content.append('<div id="temp_load'+post_type+'" style="text-align:center">\
                            <div class="loader-shoutbox "></div>\
                            </div>');
                    }
                },
                success    : function(data){
                    $data = $(data);
                    if($data.length){
                        $data.hide();
                        $content.append($data);
                        $data.fadeIn(500, function(){
                            $("#temp_load"+post_type).remove();
                            loading = false;
                        });
                    } else {
                        $("#temp_load"+post_type).remove();
                    }
                },
                error     : function(jqXHR, textStatus, errorThrown) {
                    //alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
                }
        });
    }
	
	$( "#shout-more").click(function() {
			loading = true;
			page++;
			load_posts();
		});
	
	$( "#shout-moreplayer").click(function() {
			loading = true;
			page++;
			load_posts();
		});
		
    load_posts();
	}
	
});

//on click more button
	/*jQuery( document ).ready(function() {
		
	});*/