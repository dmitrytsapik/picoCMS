$(function() {
	$(window).scroll(function() {
		if($(this).scrollTop() > 400) {
			$('#toTop').fadeIn();
		} else {
			$('#toTop').fadeOut();
		}
	});
	$('#toTop').click(function() {
		$('body,html').animate({scrollTop:0},400);
	});
});
$(document).ready(function() {
  $('#report_bug').live('click',function(){
    if (confirm("Нажимая \"ОК\", Вы разрешаете отправку снимка текущей страницы администраторам сайта Физико-технического института ФГАОУ ВО \"КФУ им. В.И.Вернадского.\"")) {
			setTimeout("makeIT()", 1000);
		}
  });
});
function makeIT(){
  html2canvas(document.body, {
	 onrendered: function(canvas) {
   var data = canvas.toDataURL('image/png').replace(/data:image\/png;base64,/, '');
   $('canvas').remove();
   $.post('saveCPic.php',{data:data}, function(rep){
    alert(' Изображение доступно по ссылке ' + rep);
   });
   }
  });                
}