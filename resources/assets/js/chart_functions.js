// TODO: refactor this back into the main js file.

require('jquery');
var Chart = require('chart.js');

// Pre-defined variables
var background_color_defaults = [
	"#FF6384",
	"#36A2EB",
	"#FFCE56",
	"#CE2D4F",
	"#434371",
	"#81F4E1",
	"#8FC93A",
	"#79AEA3",
	"#E18335",
	"#556F44"
];

// A simple wrapper around the Chart.js functionality
window.plotChart = function (target, chart_type, data_object, options_object){
	
	// target: What element on the page to use
	// chart_type: bar, line, pie etc.
	// data_object: JavaScript object of data labels and points to be
	// graphed
	// options_object: other options including
	//			- labels
	//			- colors
	//			- graph specific options
	//				(Probably want to have option
	//				specific headings?)
	
	// Get the canvas using the target option
	var ctx = target;

	// We will generate some fields based on the data passed in

	// TODO: create and use a function to generate these
	var data_labels = Object.keys(data_object);
	var data_values = [];
	for (let key of data_labels){
		data_values.push(data_object[key]);
	}
	var background_color = background_color_defaults.slice(0,data_labels.length);
	var hover_background_color = background_color;

	// Think that it would make sense in the future,
	// when there are more options to deal with, to have
	// a function build the configuration object passed into
	// the Chart constructor.  For the moment, our needs 
	// are simple enough to initialize it right away.
	
	// Initialize the chart
	var myChart = new Chart( ctx, {
		type: chart_type,
		data: {
			labels: data_labels,
			datasets: [{
				data: data_values,
				backgroundColor: background_color,
				hoverBackgroundColor: hover_background_color
			}]
		},
		options: {
			legend: {
				display: false,
				position: "right",
				fullWidth: false,
				labels: {
					boxWidth: 10
				}
			},
			maintainAspectRatio:false,
			title: {
				display:true,
				text: options_object.title
			}
		}
	});

	return myChart;
}

window.plotLineChart = function(target, data_object, options_object){
	// Get the canvas using the target option
	var ctx = target;

	// We will generate some fields based on the data passed in
	var data_labels = Object.keys(data_object); 
	var background_colors = background_color_defaults.slice(0,data_labels.length);
	var labels = data_object[data_labels[0]]["displayDate"];
	var datasets = [];

	for (let key of data_labels){
		var color = background_colors.shift();
		datasets.push({
			label: key,
			fill: false,
			lineTension: 0.2,
			backgroundColor: color,
			borderColor: color,
			borderCapStyle: 'butt',
			borderDash: [],
			borderDashOffset: 0.0,
			borderJoinStyle: 'miter',
			pointBorderColor: color,
			pointBackgroundColor: "#fff",
			pointBorderWidth: 1,
			pointHoverRadius: 5,
			pointHoverBackgroundColor: color,
			pointHoverBorderColor: color,
			pointHoverBorderWidth: 2,
			pointRadius: 1,
			pointHitRadius: 10,
			data: data_object[key]["amount"],
			spanGaps: false,
		});
	}

	var data = {
		labels: labels,
		datasets: datasets
	};
	var myLineChart = new Chart(ctx, {
		type: 'line',
		data: data,
		options: {
			scales: {
				xAxes: [{
					display: false
				}]
			},
			legend: {
				display:false,
				position: "bottom",
				fullWidth: false,
				labels: {
					boxWidth: 10
				}
			},
			maintainAspectRatio: false,
			title: {
				display:true,
				text: options_object.title
			},
			scales: {
				yAxes:[{
					ticks: {
						callback: function(value, index, values){
							return "$"+value;
						}
					}
				}]
			}
		}
	});

	return myLineChart;
}
