

var options = {
    chart: {
        renderTo: '',
		events:{
			load: function(){}
		}
    },
    title: {
        text: ''
    },
	subtitle: {
		text: ''
	},
	tooltip: {
		formatter: function(){}
	},
    xAxis: {
        categories: []
    },
    yAxis: {
        title: {
            text: ''
        }
    },
    series: []
};


function resetaOptions(obj){
	obj.chart.events.load = function() {};
}
								
function configToolTip(obj, resp, cfg){
	resetaOptions(obj);
	
	if(cfg == 0){
	 obj.tooltip.formatter = function() { return '<b>'+ this.point.name +'</b>: '+ this.y +' cliques'; }
	}
	else if(cfg == 1 ||cfg == 2 ||cfg == 3 ||cfg == 4){
	 obj.tooltip.formatter = function() { return '<b>'+this.series.name +'</b>: '+ this.y +' cliques';}
	}
	else if(cfg == 5 ||cfg == 6){
	 obj.tooltip.formatter = function() { return '<b>'+this.x+'</b>: '+this.y +' cliques';}
	 obj.chart.events.load = function() {
				
								// set up the updating of the chart each second
								var series = this.series[0];
								setInterval(function() {
									var x =  Math.random(), // current time
										y = Math.random();
									series.addPoint([x, y], true, true);
								}, 1000);
							};
	}
}

function criaGrafico(resposta, render, cfg){
	 options.chart.defaultSeriesType = resposta.tipo;
	 options.title.text = resposta.titulo;
	 options.yAxis.title.text = resposta.legenda;
	 options.xAxis.categories = resposta.categorias;
	 options.series = resposta.series;
	 options.chart.renderTo = render;
	 configToolTip( options, resposta, cfg );
	 var chart = new Highcharts.Chart(options);
}

function geraGrafico(url, onde, cfg){
	$.ajax({
	   type: "POST",
	   url: "dadosEstatisticos.php?rnd="+Math.random(),
	   data: "url="+url+"&cfg="+cfg,
	   dataType: 'json',
	   success: function(resposta){
		   criaGrafico(resposta, onde, cfg);
	   }
	});
}


