// jSnow 
// Licensed under GPL licenses.  
// Copyright (C) 2009 Nikos "DuMmWiaM" Kontis, dummwiam@gmail.com  
// http://www.DuMmWiaM.com/jSnow  

var nbOfFlakes=300;
var maxFlakeRadius=3;
var time=0;
var flakes=Array();
var sky = document.getElementById('sky');
var context = sky.getContext('2d');
var isSnowing;
var t0=null;
var tOff=null;
var nbFlakesOut;
var snowColor= $('#sky').data("color");
			  
function resizeMe() {
	document.getElementById('sky').setAttribute('width', window.innerWidth);
	document.getElementById('sky').setAttribute('height', window.innerHeight);	
}
			
function filterNull(v, i, arr) {
	return (v != null);
}
			
function newFlake() {
	x=Math.ceil(Math.random()*window.innerWidth);
	y=0;
	r=Math.random()*maxFlakeRadius;
	if(r<0.5) r=0.5;
	return Array(x,y,r);
}
			  
function drawSky() {
	flakes=flakes.filter(filterNull);
	context.clearRect(0,0,window.innerWidth,window.innerHeight);
	for(var i=0;i<flakes.length;i++) {
		context.beginPath();
		context.arc(flakes[i][0], flakes[i][1], flakes[i][2], 0, 2 * Math.PI, false);
		context.fillStyle = snowColor;
		context.fill();
	}	
}
			  
function moveFlakes(v,i,arr) {
	x=(v[2]/4)*(Math.sin(i%100*time))+v[0];
	y=v[1]+v[2]*2;
	r=v[2];
	if(x>window.innerWidth) x=-r;
	if(x<-r) x=window.innerWidth;
	if(y>window.innerHeight) {
		if(isSnowing) y = -r;
		else return null;
	}
	return Array(x,y,r);
}
			  
function startSnowing() {
	if(t0==null) {
		tOff=null;
		t0=setInterval(function() {
			if(flakes.length<nbOfFlakes && isSnowing) {
				flakes.push(newFlake());
			}
			drawSky();
			flakes=flakes.map(moveFlakes);
			time=time+0.001;
			if(time>=10) stopSnowing();
		}, 30);
	}
	isSnowing=true;
}
			
// Main
resizeMe(); 
startSnowing();
$(window).resize(resizeMe);