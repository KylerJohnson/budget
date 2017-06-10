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

window.plotLineChartTEMP = function(target){
	// this is a temporary function that I'm using
	// to test out the line charts.  If I like what I see,
	// it needs to be merged into the plotChart function.
	var data = {
		labels: ["January", "February", "March", "April", "May", "June", "July"],
		datasets: [
			{
				label: "My First dataset",
				fill: true,
				lineTension: 0.2,
				backgroundColor: "rgba(75,192,192,0.4)",
				borderColor: "rgba(75,192,192,1)",
				borderCapStyle: 'butt',
				borderDash: [],
				borderDashOffset: 0.0,
				borderJoinStyle: 'miter',
				pointBorderColor: "rgba(75,192,192,1)",
				pointBackgroundColor: "#fff",
				pointBorderWidth: 1,
				pointHoverRadius: 5,
				pointHoverBackgroundColor: "rgba(75,192,192,1)",
				pointHoverBorderColor: "rgba(220,220,220,1)",
				pointHoverBorderWidth: 2,
				pointRadius: 1,
				pointHitRadius: 10,
				data: [65, 59, 80, 81, 56, 55, 40],
				spanGaps: false,
			},
			{
				label: "My Second dataset",
				fill: true,
				lineTension: 0.2,
				backgroundColor: "rgba(7,12,12,0.4)",
				borderColor: "rgba(7,12,12,1)",
				borderCapStyle: 'butt',
				borderDash: [],
				borderDashOffset: 0.0,
				borderJoinStyle: 'miter',
				pointBorderColor: "rgba(75,192,192,1)",
				pointBackgroundColor: "#fff",
				pointBorderWidth: 1,
				pointHoverRadius: 5,
				pointHoverBackgroundColor: "rgba(75,192,192,1)",
				pointHoverBorderColor: "rgba(220,220,220,1)",
				pointHoverBorderWidth: 2,
				pointRadius: 1,
				pointHitRadius: 10,
				data: [60, 30, 36, 44, 70, 60, 67],
				spanGaps: false,
			}
		]
	};
	var myLineChart = new Chart(target, {
		type: 'line',
		data: data,
		options: {
			scales: {
				xAxes: [{
					display: false
				}]
			}
		}
	});


}
