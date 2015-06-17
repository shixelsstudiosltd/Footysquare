// ajaxLoop.js
jQuery(function($){
    var page = 1;
    var loading = true;
    var $window = $(window);
    var $content = $('#shoutbox-content');
	var search_word = $('#shoutbox-content').attr('class');
	//alert(search_word);
		var load_search_results = function(){
            $.ajax({
                type       : "GET",
                data       : {numPosts : 50, pageNumber: page},
                dataType   : "html",
                url        : "/fs/wp-content/themes/footysquare/loopHandler.php?s="+search_word,
                beforeSend : function(){
                    if(page != 1){
                        $content.append('<div id="temp_load" style="text-align:center">\
                            \
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
	
	$( "#shout-more").click(function() {
			loading = true;
			page++;
			load_search_results();
		});
	
    load_search_results();
	
});
