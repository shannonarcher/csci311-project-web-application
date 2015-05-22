var _apn = function () {};

_apn.prototype.currentX = 0;
_apn.prototype.currentY = 0;
_apn.prototype.currentMouseX = 0;
_apn.prototype.currentMouseY = 0;

_apn.prototype.id = "";
_apn.prototype.canvas = null;
_apn.prototype.canvasWidth = 0;
_apn.prototype.canvasHeight = 0;
_apn.prototype.canvasNodes = null;
_apn.prototype.canvasEdges = null;
_apn.prototype.canvasConfig = {
	class: "apn-canvas",
	grabbingClass: "apn-canvas-grabbing",
	node: {
		class: "apn-node",
		width: 100,
		height: 80,
		padding: 10,
		margin: {
			top:10,
			bottom:10,
			left:100,
			right:100
		}
	},
	edge: {
		class: "apn-edge"
	}
};

_apn.prototype.startNode = null;
_apn.prototype.finalNode = null;
_apn.prototype.nodes = [];

_apn.prototype.init = function (id) {
	this.id = id;
	this.canvas = document.getElementById(id);
	this.canvas.className = this.canvasConfig.class;
	this.canvasNodes = [];
	this.canvasEdges = [];

	this.addEvents();
};

/** 
 * Create graph for use by renderer.
 */
_apn.prototype.parse = function (tasks) {
	this.startNode = new Node("start", "Start", 0);
	this.finalNode = new Node("final", "Final", 0);

	this.nodes = [];

	// create nodes for graph
	for (var t in tasks) {
		this.createNode(tasks[t]);
	}

	// create edges for graph
	for (var t in tasks) {
		for (var d in tasks[t].dependencies) {
			this.createEdge(tasks[t], tasks[t].dependencies[d]);
		}
	}

	// link nodes with no edges to final node
	for (var n in this.nodes) {
		if (this.nodes[n].edges.length == 0) {
			this.nodes[n].edges.push(new Edge(this.finalNode));
			this.finalNode.reverseEdges.push(new Edge(this.nodes[n]));
		}
	}

	// link nodes with no reverseEdges to startNode
	for (var n in this.nodes) {
		if (this.nodes[n].reverseEdges.length == 0) {
			this.nodes[n].reverseEdges.push(new Edge(this.startNode));
			this.startNode.edges.push(new Edge(this.nodes[n]));
		}
	}

	// derive values
	// forward pass
	for (var e in this.startNode.edges) {
		this.forwardPass(this.startNode, this.startNode.edges[e].dest);
	}
	// backwards pass
	this.finalNode.latest.finish = this.finalNode.earliest.finish;
	this.finalNode.latest.start = this.finalNode.latest.finish - this.finalNode.duration;
	for (var e in this.finalNode.reverseEdges) {
		this.backwardPass(this.finalNode, this.finalNode.reverseEdges[e].dest);	
	}

	// assign depths to nodes
	this.assignDepth(this.startNode, -1);

	this.render();
};

_apn.prototype.createNode = function (task) {
	this.nodes.push(new Node(
		task.id,
		task.title,
		Math.round(task.estimation_duration / 86400)
	));
};

_apn.prototype.createEdge = function (task1, task2) {
	var n1 = null, n2 = null;

	for (var n in this.nodes) {
		if (task1.id == this.nodes[n].id)
			n1 = this.nodes[n];
		if (task2.id == this.nodes[n].id)
			n2 = this.nodes[n];
	}

	n1.edges.push(new Edge(n2));
	n2.reverseEdges.push(new Edge(n1));
};

_apn.prototype.assignDepth = function (node, parent_depth) {
	if (node.depth < parent_depth + 1) {
		node.depth = parent_depth + 1;
	}

	for (var e in node.edges) {
		this.assignDepth(node.edges[e].dest, parent_depth+1);
	}
};

_apn.prototype.forwardPass = function (parent, node) {

	if (parent.earliest.finish > node.earliest.start)
		node.earliest.start = parent.earliest.finish;
	node.earliest.finish = node.earliest.start + node.duration;

	for (var e in node.edges) {
		this.forwardPass(node, node.edges[e].dest);
	}

};

_apn.prototype.backwardPass = function (child, node) {

	if (child.latest.start < node.latest.finish || node.latest.finish == 0) 
		node.latest.finish = child.latest.start;
	node.latest.start = node.latest.finish - node.duration;

	node.float = node.latest.finish - node.earliest.start - node.duration;

	if (node.id == "start")
		node.float = 0;

	for (var e in node.reverseEdges) {
		this.backwardPass(node, node.reverseEdges[e].dest);
	}

};















// rendering methods
_apn.prototype.render = function () {
	this._renderNode(this.startNode);
	for (var e in this.startNode.edges) {
		this._renderEdge(this.startNode, this.startNode.edges[e].dest);
	}

	for (var n in this.nodes) {
		this._renderNode(this.nodes[n]);		
		for (var e in this.nodes[n].edges) {
			this._renderEdge(this.nodes[n], this.nodes[n].edges[e].dest);
		}
	}

	this._renderNode(this.finalNode);

	this.canvas.style.height = this.canvasHeight + "px";
	this.canvas.style.width = this.canvasWidth + "px";
};

_apn.prototype._renderNode = function (node) {
	// create element
	var renderable = document.createElement('div');
	renderable.className = this.canvasConfig.node.class;

	renderable.style.position = "absolute";
	renderable.style.width = this.canvasConfig.node.width + "px";
	renderable.style.height = this.canvasConfig.node.height + "px";

	// add critical class
	if (node.float == 0)
		renderable.className = renderable.className + " " + this.canvasConfig.node.class + "-critical";

	// calculate position
	var total = 0;
	var topOffset = 0;
	for (var n in this.nodes) {
		if (this.nodes[n].depth == node.depth) {

			if (this.nodes[n].id <= node.id) 
				topOffset++;	
			total++;

		}
	}

	var maxTop = topOffset * (this.canvasConfig.node.height + this.canvasConfig.node.margin.top + this.canvasConfig.node.margin.bottom);

	topOffset -= total / 2;

	if (isNaN(node.id))
		topOffset = 0;

	var top = (topOffset * (this.canvasConfig.node.height + this.canvasConfig.node.margin.bottom)) - 
				(this.canvasConfig.node.margin.top + this.canvasConfig.node.height / 2);

	var left = (node.depth * this.canvasConfig.node.width + 
				node.depth * (this.canvasConfig.node.margin.left + this.canvasConfig.node.margin.right));

	renderable.style.marginTop = top + "px";
	renderable.style.left = left + "px";

	var newTop = maxTop;
	var newLeft = left + this.canvasConfig.node.width + this.canvasConfig.node.margin.left + this.canvasConfig.node.margin.right;

	if (newTop > this.canvasHeight)
		this.canvasHeight = newTop;
	if (newLeft > this.canvasWidth)
		this.canvasWidth = newLeft;

	// add property boxes
	renderable.appendChild(this._renderPropertyBox("id", node.id));
	
	if (!isNaN(node.id)) {
		renderable.appendChild(this._renderPropertyBox("es", node.earliest.start));
		renderable.appendChild(this._renderPropertyBox("du", node.duration));
		renderable.appendChild(this._renderPropertyBox("ef", node.earliest.finish));
		renderable.appendChild(this._renderPropertyBox("ls", node.latest.start));
		renderable.appendChild(this._renderPropertyBox("fl",  node.float));
		renderable.appendChild(this._renderPropertyBox("lf", node.latest.finish));
	}

	this.canvas.appendChild(renderable);

	this.canvasNodes.push(renderable);
};

_apn.prototype._renderPropertyBox = function (propertyName, propertyValue) {
	var renderable = document.createElement('div');
	renderable.innerHTML= propertyValue;
	renderable.className = this.canvasConfig.node.class + "-" + propertyName;
	renderable.style.lineHeight = this.canvasConfig.node.height / 3 + "px";
	return renderable;
};

_apn.prototype._renderEdge = function (node1, node2, reverse) {
	reverse = typeof reverse !== 'undefined' ? reverse : false;

	var renderable = document.createElement('div');
	renderable.className = this.canvasConfig.edge.class;

	renderable.style.position = "absolute";

	// add critical class
	if (node1.float == 0 && node2.float == 0)
		renderable.className = renderable.className + " " + this.canvasConfig.edge.class + "-critical";

	// calculate position
	var topOffset = 0;
	var top = 0;
	var left = 0;

	left = (node1.depth * this.canvasConfig.node.width + 
			node1.depth * (this.canvasConfig.node.margin.left + this.canvasConfig.node.margin.right) +
			this.canvasConfig.node.width + 1);

	var total = 0;
	var topOffset = 0;
	for (var n in this.nodes) {
		if (this.nodes[n].depth == node1.depth) {

			if (this.nodes[n].id <= node1.id) 
				topOffset++;	
			total++;

		}
	}

	topOffset -= total / 2;

	if (isNaN(node1.id))
		topOffset = 0;

	var top = (topOffset * (this.canvasConfig.node.height + this.canvasConfig.node.margin.bottom)) - 
				(this.canvasConfig.node.margin.top + this.canvasConfig.node.height / 2)  +
				this.canvasConfig.node.height * 0.5;

	renderable.style.marginTop = top + "px";
	renderable.style.left = left + "px";

	// calculate destination
	var topOffset = 0;
	var top2 = 0;
	var left2 = 0;

	left2 = (node2.depth * this.canvasConfig.node.width + 
			node2.depth * (this.canvasConfig.node.margin.left + this.canvasConfig.node.margin.right) + 1);

	var total = 0;
	var topOffset = 0;
	for (var n in this.nodes) {
		if (this.nodes[n].depth == node2.depth) {

			if (this.nodes[n].id <= node2.id) 
				topOffset++;	
			total++;

		}
	}

	topOffset -= total / 2;

	if (isNaN(node2.id))
		topOffset = 0;

	top2 = (topOffset * (this.canvasConfig.node.height + this.canvasConfig.node.margin.bottom)) - 
			(this.canvasConfig.node.margin.top + this.canvasConfig.node.height / 2)  +
			this.canvasConfig.node.height * 0.5;

	// calculate rotation between points
	var angle = -Math.atan2(left2 - left, top2 - top) * 180 / Math.PI;

	angle += 90;

	renderable.style.transformOrigin = "0% 0%";
	renderable.style.transform = "rotate(" + angle + "deg)";

	renderable.style.width = Math.sqrt(Math.pow(left - left2, 2) + Math.pow(top - top2, 2)) + "px";
	renderable.style.height = "1px";

	this.canvas.appendChild(renderable);

	this.canvasEdges.push(renderable);
};






var Node = function (id, title, duration) {
	this.id = id;
	this.title = title;

	this.earliest = {
		start: 0,
		finish: 0
	};
	this.duration = duration;
	this.latest = {
		start: 0,
		finish: 0
	};
	this.float = 0;

	this.edges = [];
	this.reverseEdges = [];

	this.depth = 0;
};

var Edge = function (dest) {
	this.dest = dest;
};






var apn = new _apn();

// add events to interact with chart
_apn.prototype.addEvents = function () {
	apn.canvas.addEventListener('mousedown', apn.mouseDown, false);
	window.addEventListener('mouseup', apn.mouseUp, false);
};

_apn.prototype.mouseUp = function (e) {
	window.removeEventListener('mousemove', apn.move, true);

	apn.canvas.className = apn.canvas.className.replace(apn.canvasConfig.grabbingClass, '');
};

_apn.prototype.mouseDown = function (e) {
	apn.currentX = parseInt(apn.canvas.style.left) | 0;
	apn.currentY = parseInt(apn.canvas.style.top) | 0;

	console.log(apn.currentX, apn.currentY);

	apn.currentMouseX = e.clientX;
	apn.currentMouseY = e.clientY;

	window.addEventListener('mousemove',  apn.move, true);

	apn.canvas.className = apn.canvas.className + " " + apn.canvasConfig.grabbingClass;
};

_apn.prototype.move = function (e) {
	apn.canvas.style.position = "absolute";

	apn.canvas.style.top = (apn.currentY - (apn.currentMouseY - e.clientY)) + "px";
	apn.canvas.style.left = (apn.currentX - (apn.currentMouseX - e.clientX)) + "px";
};