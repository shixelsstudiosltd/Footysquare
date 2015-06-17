// ajaxLoop.js
jQuery(function($){
    var page = 1;
    var loading = true;
    var $window = $(window);
    var $content = $('#shoutbox-content-country');
	
		var load_posts_country = function(){
            $.ajax({
                type       : "GET",
                data       : {numPosts : 5, pageNumber: page},
                dataType   : "html",
                url        : "/fs/wp-content/themes/footysquare/loopHandler.php?post_type=country",
                beforeSend : function(){
                    if(page != 1){
                        $content.append('<div id="temp_load" style="text-align:center">\
                            <div class="loader-shoutbox"></div>\
                            </div>');
                    }
                },
                success    : function(data){
                    $data = $(data);
                    if($data.length){
                        $data.hide();
                        $content.append($data);
                        $data.fadeIn(500, function(){
                            $("#temp_load").remove();
                            loading = false;
                        });
                    } else {
                        $("#temp_load").remove();
                    }
                },
                error     : function(jqXHR, textStatus, errorThrown) {
                    //alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
                }
        });
    }
	
	$( "#shout-morecountry").click(function() {
			loading = true;
			page++;
			load_posts_country();
		});
	
    load_posts_country();
	
});
