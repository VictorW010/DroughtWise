// set the dimensions and margins of the graph
var margin = {top: 10, right: 30, bottom: 30, left: 60},
    width = 600 - margin.left - margin.right,
    height = 400 - margin.top - margin.bottom;

// append the svg object to the body of the page
var svg = d3.select("#my_dataviz")
    .append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
    .append("g")
    .attr("transform",
            "translate(" + margin.left + "," + margin.top + ")");

//Read the data
d3.csv("/wp-content/uploads/file/temp2.csv",function(data) {

    // Add X axis --> it is a date format
    var x = d3.scaleLinear()
        .domain([1990,2018])
        .range([ 0, width ]);
    svg.append("g")
        .attr("transform", "translate(0," + height + ")")
        .call(d3.axisBottom(x));

    // Add Y axis
    var y = d3.scaleLinear()
        .domain([20, 25])
        .range([ height, 0 ]);
    svg.append("g")
        .call(d3.axisLeft(y));

    svg.append("text")
        .attr("transform", "translate(" + (width/2) + "," + (height + margin.bottom) + ")")
        .style("text-anchor", "middle")
        .text("Year");

    svg.append("text")
        .attr("transform", "rotate(-90)")
        .attr("y", 0 - margin.left)
        .attr("x", 0 - (height/2))
        .attr("dy", "1em")
        .style("text-anchor" , "middle")
        .text("Temperature in Celsius");      

    // Show confidence interval
    svg.append("path")
        .datum(data)
        .attr("fill", "#cce5df")
        .attr("stroke", 'none')
        .attr("d", d3.area()
        .x(function(d) { return x(d.Year) })
        .y1(function(d) { return y(d.maxR*100) })
        .y0(function(d) { return y(d.minR*100) })
        )

    // Add the line
    svg
        .append("path")
        .datum(data)
        .attr("fill", "none")
        .attr("stroke", "steelblue")
        .attr("stroke-width", 1.5)
        .attr("d", d3.line()
        .x(function(d) { return x(d.Year) })
        .y(function(d) { return y(d.value) })
        )

    var Tooltip = d3.select("#my_dataviz")
    .append("div")
    .style("opacity", 0)
    .attr("class", "tooltip")
    .style("background-color", "white")
    .style("border", "solid")
    .style("border-width", "2px")
    .style("border-radius", "5px")
    .style("padding", "5px")

    // Three function that change the tooltip when user hover / move / leave a cell
    var mouseover = function(d) {
        Tooltip
        .style("opacity", 1)
    }
    var mousemove = function(d) {
        Tooltip
        .html("Rainfall value: " + d.value + "  Range:" + "[" + d.maxR +", "+d.minR+"]")
        .style("left", (d3.mouse(this)[0])+10 + "px")
        .style("top", (d3.mouse(this)[1])+10 + "px");
    }
    var mouseleave = function(d) {
        Tooltip
        .style("opacity", 0)
    }
    
    // Add the points
    svg
        .append("g")
        .selectAll("dot")
        .data(data)
        .enter()
        .append("circle")
            .attr("cx", function(d) { return x(d.Year) } )
            .attr("cy", function(d) { return y(d.value) } )
            .attr("r", 5)
            .attr("fill", "#69b3a2") 
            .on("mouseover", mouseover)
            .on("mousemove", mousemove)
            .on("mouseleave", mouseleave)
        }
)
 