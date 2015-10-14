function sticky() {
  if($(window).scrollTop()>=$("article").position().top) {
                    $('#sticky').css("left", $(".menu").position().left);
                    $('#sticky').css("width", $(".menu").css("width"));
                    $(".menu a").css("visibility", "hidden");
                    $("#sticky").css('visibility', 'visible');
                    if($("#foot:in-viewport").length>0) {
                       /*position_s = position_s - 10;*/
                       /*$('#sticky').css("top", position_s);*/
                    } else {
                       /*position_s = 0;*/
                       $('#sticky').css("top", 0);
                    }
                    
                    //$("#foot:in-viewport").each(function() {
                       //$('#sticky').css("top", -100);
                       //alert($("#foot:in-viewport"));
                    //});
                } else {
                    $("#sticky").css('visibility', 'hidden');
                    $(".menu a").css("visibility", "visible");
                }
}
$(function() {
  $('#sticky').html($('.menu').html());
  if($(".menu").length>0) sticky();
            $(window).scroll(function() {
                  if($(".menu").length>0) sticky();
            });
});
