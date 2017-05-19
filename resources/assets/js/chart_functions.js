// TODO: refactor this back into the main js file.

require('jquery');
var Chart = require('chart.js');

// Pre-defined variables
var background_color_defaults = [
	"#FF6384",
	"#36A2EB",
	"#FFCE56",
	"#CE2D4F",
	"#CEBBC9",
	"#81F4E1",
	"#56CBF9",
	"#8FC93A"
];

// A simple wrapper around the Chart.js functionality
window.plotChart = function (target, chart_type, data_JSON, options){
	
	// target: What element on the page to use
	// chart_type: bar, line, pie etc.
	// data: JSON object of data labels and points to be graphed
	// options: other options including 
	//			- labels
	//			- colors
	//			- graph specific options
	//				(Probably want to have option 
	//				specific headings?)
	
	// Get the canvas using the target option
	var ctx = target;

	// Parse the data JSON string
	var dataObject = JSON.parse(data_JSON);

	// We will generate some fields based on the data passed in

	// TODO: create and use a function to generate these
	var data_labels = Object.keys(dataObject); 
	var data_values = [];
	for (let key of data_labels){
		data_values.push(dataObject[key]);
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
				position: "right",
				fullWidth: false
			}
		}
	});
}
