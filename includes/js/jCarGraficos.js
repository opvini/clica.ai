
function mycarousel_initCallback(carousel)
{
	$('.jcarousel-skin-graficos .jcarousel-next').hide(0);
	$('.jcarousel-skin-graficos .jcarousel-prev').hide(0);

    jQuery('#ControlGraficos li').bind('click', function() {
        carousel.scroll(jQuery.jcarousel.intval(jQuery(this).index()+1));
        return false;
    });

    carousel.container.hover(function() {
		$('.jcarousel-skin-graficos .jcarousel-next').fadeIn(350);
		$('.jcarousel-skin-graficos .jcarousel-prev').fadeIn(350);
        carousel.stopAuto();
    }, function() {
		$('.jcarousel-skin-graficos .jcarousel-next').fadeOut(350);
		$('.jcarousel-skin-graficos .jcarousel-prev').fadeOut(350);
        carousel.startAuto();
    });
	
};

function terminouRolar(inst, html, index){
	$('#S'+index).addClass('ControlGraficosAtual');
}

function vaiRolar(inst, html, index){
	$('#S'+index).removeClass('ControlGraficosAtual');
}

 
jQuery(document).ready(function() {
    jQuery('#destrol').jcarousel({
        auto: 5,
		animation: 600,
		itemFirstOutCallback: { onBeforeAnimation: vaiRolar },
		itemLastInCallback: { onBeforeAnimation: terminouRolar},
		scroll: 1,
        wrap: 'last',
        initCallback: mycarousel_initCallback
    });
});



