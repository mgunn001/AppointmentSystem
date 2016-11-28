function start()
{
	$(document).ready(function() {
		$('.message a').click(function(){
		   $('.defalutForms').animate({height: "toggle", opacity: "toggle"}, "slow");
		});
	});
}
start();