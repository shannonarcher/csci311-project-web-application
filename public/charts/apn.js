/** @author Shannon Archer **/
/** prototype class **/
var _apn = function () {};

/** Properties **/
/** Graphic Trackers **/
_apn.prototype.currentX = 0;
_apn.prototype.currentY = 0;
_apn.prototype.currentMouseX = 0;
_apn.prototype.currentMouseY = 0;

_apn.prototype.activeNode = null;
_apn.prototype.activeNodeX = 0;
_apn.prototype.activeNodeY = 0;

/** Canvas and Canvas Objects **/
_apn.prototype.id = "";
_apn.prototype.canvas = null;
_apn.prototype.canvasGrabbable = null;
_apn.prototype.canvasPositions = {
	top:0, left:0, bottom:0, right:0,
};
_apn.prototype.canvasNodes = null;
_apn.prototype.canvasEdges = null;
/** Canvas Settings **/
_apn.prototype.canvasConfig = {
	class: "apn-canvas",
	grabbingClass: "grabbing",
	node: {
		class: "apn-node",
		width: 100,
		height: 80,
		padding: 10,
		margin: {
			top:10,
			bottom:10,
			left:200,
			right:200
		}
	},
	edge: {
		class: "apn-edge"
	},
	gPadding: {
		top:0,
		left:0
	}
};

/** APN Data Structure **/
_apn.prototype.startNode = null;
_apn.prototype.finalNode = null;
_apn.prototype.nodes = [];

/** Initialise canvas **/
_apn.prototype.init = function (id) {
	this.id = id;
	this.canvas = document.getElementById(id);
	this.canvas.className = this.canvasConfig.class;
	this.canvasNodes = [];
	this.canvasEdges = [];

	this.canvasGrabbable = document.createElement('div');
	this.canvasGrabbable.className = "apn-grabbable";
	this.canvas.appendChild(this.canvasGrabbable);

	this.addEvents();
};

/** 
 * Parse data into graph for use by renderer.
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
			this.nodes[n].edges.push(new Edge(this.finalNode, this.nodes[n]));
			this.finalNode.reverseEdges.push(new Edge(this.nodes[n], this.finalNode));
		}
	}

	// link nodes with no reverseEdges to startNode
	for (var n in this.nodes) {
		if (this.nodes[n].reverseEdges.length == 0) {
			this.nodes[n].reverseEdges.push(new Edge(this.startNode, this.nodes[n]));
			this.startNode.edges.push(new Edge(this.nodes[n], this.startNode));
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

/** 
 * Create Node Object for Graph
 */
_apn.prototype.createNode = function (task) {
	this.nodes.push(new Node(
		task.id,
		task.title,
		Math.round(task.estimation_duration / 86400)
	));
};

/** 
 * Create Edge Object for Graph
 */
_apn.prototype.createEdge = function (task1, task2) {
	var n1 = null, n2 = null;

	for (var n in this.nodes) {
		if (task1.id == this.nodes[n].id)
			n1 = this.nodes[n];
		if (task2.id == this.nodes[n].id)
			n2 = this.nodes[n];
	}
	
	if (n1 != null && n2 != null) {
		n1.edges.push(new Edge(n2, n1));
		n2.reverseEdges.push(new Edge(n1, n2));
	}
};

/**
 * Recursively assigns proper depth to all nodes on graph
 */
_apn.prototype.assignDepth = function (node, parent_depth) {
	if (node.depth < parent_depth + 1) {
		node.depth = parent_depth + 1;
	}

	for (var e in node.edges) {
		this.assignDepth(node.edges[e].dest, parent_depth+1);
	}
};

/**
 * Recursively passes over nodes assigning earliest start, earliest finish
 */
_apn.prototype.forwardPass = function (parent, node) {

	if (parent.earliest.finish > node.earliest.start)
		node.earliest.start = parent.earliest.finish;
	node.earliest.finish = node.earliest.start + node.duration;

	for (var e in node.edges) {
		this.forwardPass(node, node.edges[e].dest);
	}

};

/**
 * Recursively passes over nodes assigning lastest start, lastest finish and float
 */
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
/**
 * Render entire graph
 */
_apn.prototype.render = function () {
	this.renderNodes();
	this.renderEdges();
	this.setCanvasDimensions();
};

/** 
 * Create canvas objects for all nodes in graph
 */
_apn.prototype.renderNodes = function () {
	this.startNode.renderable = this._renderNode(this.startNode);
	for (var n in this.nodes) {
		this.nodes[n].renderable = this._renderNode(this.nodes[n]);		
	}
	this.finalNode.renderable = this._renderNode(this.finalNode);
};

/**
 * Create canvas objects for all edges in graph
 * Removes previous edges if any exist
 */
_apn.prototype.renderEdges = function () {

	for (var i in this.canvasEdges) {
		this.canvas.removeChild(this.canvasEdges[i]);
	}
	this.canvasEdges = [];

	for (var e in this.startNode.edges) {
		this._renderEdge(this.startNode.edges[e]);
	}
	for (var n in this.nodes) {
		for (var e in this.nodes[n].edges) {
			this._renderEdge(this.nodes[n].edges[e]);
		}
	}

};

/**
 * Set proper draggable size
 */
_apn.prototype.setCanvasDimensions = function () {
	this.canvasGrabbable.style.position = 'absolute';
	this.canvasGrabbable.style.marginTop = (this.canvasPositions.top - this.canvasConfig.gPadding.top) + "px";
	this.canvasGrabbable.style.left = (this.canvasPositions.left - this.canvasConfig.gPadding.left) + "px";
	this.canvasGrabbable.style.height = (Math.abs(this.canvasPositions.top) + Math.abs(this.canvasPositions.bottom) + this.canvasConfig.gPadding.top) + "px";
	this.canvasGrabbable.style.width = (Math.abs(this.canvasPositions.left) + Math.abs(this.canvasPositions.right) + this.canvasConfig.gPadding.left) + "px";
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

	if (left < this.canvasPositions.left)
		this.canvasPositions.left = left;
	if (left + this.canvasConfig.node.width > this.canvasPositions.right)
		this.canvasPositions.right = left + this.canvasConfig.node.width;

	if (top < this.canvasPositions.top)
		this.canvasPositions.top = top;
	if (top + this.canvasConfig.node.height > this.canvasPositions.bottom)
		this.canvasPositions.bottom = top + this.canvasConfig.node.height;

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

	// make grabbable
	(function (r, n, apn) {

		r.addEventListener('mousedown', (function (r, n, a) {
			return function (e) {
				this.className = this.className + " grabbing";
				a.activeNode = r;
				a.activeNodeX = parseInt(r.style.left);
				a.activeNodeY = parseInt(r.style.marginTop);

				a.currentMouseX = e.clientX;
				a.currentMouseY = e.clientY;
			};
		})(r, n, apn), false);

		window.addEventListener('mouseup', (function (r, n, a) {
			return function (e) {
				r.className = r.className.replace(" grabbing", "");
				a.activeNode = null;
				a.activeNodeX = 0;
				a.activeNodeY = 0;

				a.currentMouseX = e.clientX;
				a.currentMouseY = e.clientY;
			};
		})(r, n, apn), false);

	})(renderable, node, this);

	this.canvas.appendChild(renderable);

	this.canvasNodes.push(renderable);

	return renderable;
};

_apn.prototype._renderPropertyBox = function (propertyName, propertyValue) {
	var renderable = document.createElement('div');
	renderable.innerHTML= propertyValue;
	renderable.className = this.canvasConfig.node.class + "-" + propertyName;
	renderable.style.lineHeight = this.canvasConfig.node.height / 3 + "px";
	return renderable;
};

_apn.prototype._renderEdge = function (edge, reverse) {
	reverse = typeof reverse !== 'undefined' ? reverse : false;

	var node1 = edge.origin;
	var node2 = edge.dest;

	// calculate position
	var top1 = 0, left1 = 0, top2 = 0, left2 = 0;

	top1 = parseInt(node1.renderable.style.marginTop) + this.canvasConfig.node.height / 2;
	left1 = parseInt(node1.renderable.style.left) + this.canvasConfig.node.width;

	top2 = parseInt(node2.renderable.style.marginTop) + this.canvasConfig.node.height / 2;
	left2 = parseInt(node2.renderable.style.left);

	var renderable = this._renderLine(left1, top1, left2, top2);

	// add critical class
	if (node1.float == 0 && node2.float == 0 && (node1.latest.finish == node2.earliest.start || node1.id == "start"))
		renderable.className = renderable.className + " " + this.canvasConfig.edge.class + "-critical";	

	this.canvas.appendChild(renderable);
	this.canvasEdges.push(renderable);

	return renderable;
};

_apn.prototype._renderLine = function (x1, y1, x2, y2) {
	var renderable = document.createElement('div');
	renderable.className = this.canvasConfig.edge.class;


	renderable.style.marginTop = y1 + "px";
	renderable.style.left = x1 + "px";

	renderable.style.position = "absolute";

	// calculate rotation between points
	var angle = -Math.atan2(x2 - x1, y2 - y1) * 180 / Math.PI;

	angle += 90;

	renderable.style.transformOrigin = "0% 0%";
	renderable.style.transform = "rotate(" + angle + "deg)";

	renderable.style.width = Math.sqrt(Math.pow(x1 - x2, 2) + Math.pow(y1 - y2, 2)) + "px";
	renderable.style.height = "1px";

	return renderable;
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

	this.renderable = null;
};

var Edge = function (dest, origin) {
	this.dest = dest;
	this.origin = origin;
};






var apn = new _apn();

// add events to interact with chart
_apn.prototype.addEvents = function () {
	apn.canvasGrabbable.addEventListener('mousedown', apn.mouseDown, false);
	window.addEventListener('mouseup', apn.mouseUp, false);
	window.addEventListener('mousemove', apn.moveNode, true);
};

_apn.prototype.mouseUp = function (e) {
	window.removeEventListener('mousemove', apn.move, true);

	apn.canvas.className = apn.canvas.className.replace(apn.canvasConfig.grabbingClass, '');
};

_apn.prototype.mouseDown = function (e) {
	apn.currentX = parseInt(apn.canvas.style.left) | 0;
	apn.currentY = parseInt(apn.canvas.style.top) | 0;

	apn.currentMouseX = e.clientX;
	apn.currentMouseY = e.clientY;

	window.addEventListener('mousemove',  apn.move, true);

	// apn.canvas.className = apn.canvas.className + " " + apn.canvasConfig.grabbingClass;
};

_apn.prototype.move = function (e) {
	apn.canvas.style.position = "absolute";

	apn.canvas.style.top = (apn.currentY - (apn.currentMouseY - e.clientY)) + "px";
	apn.canvas.style.left = (apn.currentX - (apn.currentMouseX - e.clientX)) + "px";
};

_apn.prototype.moveNode = function (e) {

	if (apn.activeNode != null) {
		var left = (apn.activeNodeX + e.clientX - apn.currentMouseX);
		var top  = (apn.activeNodeY + e.clientY - apn.currentMouseY);

		apn.activeNode.style.left = left + "px";
		apn.activeNode.style.marginTop  = top + "px";

		if (left < apn.canvasPositions.left)
			apn.canvasPositions.left = left;
		if (left + apn.canvasConfig.node.width > apn.canvasPositions.right)
			apn.canvasPositions.right = left + apn.canvasConfig.node.width;

		if (top < apn.canvasPositions.top)
			apn.canvasPositions.top = top;
		if (top + apn.canvasConfig.node.height > apn.canvasPositions.bottom)
			apn.canvasPositions.bottom = top + apn.canvasConfig.node.height;

		apn.renderEdges();
		apn.setCanvasDimensions();
	}
};