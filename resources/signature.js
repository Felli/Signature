$(function() {
    if ($("p.likes").length) {
    	$("p.likes").css({'height': '17px'});
        $(".like-button, .like-members, .like-separator").css({'float': 'right'});
        $("p.likes").before($("span.signature"));
    }
});
