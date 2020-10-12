

			var chart;
			
			$(document).ready(function() {
									   
				chart = new Highcharts.Chart({
					chart: {
						renderTo: 'grafico2',
						defaultSeriesType: 'line',
						marginRight: 130,
						marginBottom: 25
					},
					title: {
						text: 'Origem dos cliques',
						x: -20
					},
					subtitle: {
						text: 'de onde vieram os cliques',
						x: -20
					},
					xAxis: {
						categories: ['dom', 'seg', 'ter', 'qua', 'qui', 'sex', 'sab']
					},
					yAxis: {
						title: {
							text: 'Cliques'
						},
						plotLines: [{
							value: 0,
							width: 1,
							color: '#808080'
						}]
					},
					tooltip: {
						formatter: function() {
				                return '<b>'+ this.series.name +'</b><br/>'+
								this.x +': '+ this.y +' cliques';
						}
					},
					legend: {
						layout: 'vertical',
						align: 'right',
						verticalAlign: 'top',
						x: -10,
						y: 100,
						borderWidth: 0
					},
					series: [{
						name: '@vouConfessarQue',
						data: [123,23,456,124,1239,23,631]
					}, {
						name: '@bbbAqui',
						data: [123,434,1223,567,974,123,456]
					}]
				});
				
				
				chart2 = new Highcharts.Chart({
					chart: {
						renderTo: 'grafico1',
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: false
					},
					title: {
						text: 'Navegadores'
					},
					subtitle: { text: 'quais navegadores os visitantes usam' },
					tooltip: {
						formatter: function() {
							return '<b>'+ this.point.name +'</b>: '+ this.y +' %';
						}
					},
					plotOptions: {
						pie: {
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: true,
								color: '#000000',
								connectorColor: '#000000',
								formatter: function() {
									return '<b>'+ this.point.name +'</b>: '+ this.y +' %';
								}
							}
						}
					},
				    series: [{
						type: 'pie',
						name: 'Browser share',
						data: [
							['Firefox',   45.0],
							['IE',       26.8],
							{
								name: 'Chrome',    
								y: 12.8,
								sliced: true,
								selected: true
							},
							['Safari',    8.5],
							['Opera',     6.2],
							['Others',   0.7]
						]
					}]
				});
				
				
				chart3 = new Highcharts.Chart({
					chart: {
						renderTo: 'grafico3'
					},
					title: {
						text: 'Idade'
					},
					subtitle: { text: 'qual a idade dos visitantes' },
					xAxis: {
						categories: ['Apples', 'Oranges', 'Pears', 'Bananas', 'Plums']
					},
					tooltip: {
						formatter: function() {
							var s;
							if (this.point.name) { // the pie chart
								s = ''+
									this.point.name +': '+ this.y +' fruits';
							} else {
								s = ''+
									this.x  +': '+ this.y;
							}
							return s;
						}
					},
					labels: {
						items: [{
							html: 'Total fruit consumption',
							style: {
								left: '40px',
								top: '8px',
								color: 'black'				
							}
						}]
					},
					series: [{
						type: 'column',
						name: 'Jane',
						data: [3, 2, 1, 3, 4]
					}, {
						type: 'column',
						name: 'John',
						data: [2, 3, 5, 7, 6]
					}, {
						type: 'column',
						name: 'Joe',
						data: [4, 3, 3, 9, 0]
					}, {
						type: 'spline',
						name: 'Average',
						data: [3, 2.67, 3, 6.33, 3.33]
					}, {
						type: 'pie',
						name: 'Total consumption',
						data: [{
							name: 'Jane',
							y: 13,
							color: '#4572A7' // Jane's color
						}, {
							name: 'John',
							y: 23,
							color: '#AA4643' // John's color
						}, {
							name: 'Joe',
							y: 19,
							color: '#89A54E' // Joe's color
						}],
						center: [100, 80],
						size: 100,
						showInLegend: false,
						dataLabels: {
							enabled: false
						}
					}]
				});
			});

function teste(){
	chart3.redraw();
}
