/* project_FedorovIgor.js - analog clock
   purpose: project
   author: Igor Fedorov
   last modified: 12/15/2014
*/

// register handler for window load event
window.onload = function() {

// Model
   var timeNow;
   
// Controller
   /* initialize the size (in pixels) of the analog clock (scalable,
      square shape). */
   var CLOCK_SIZE = 300;

   // get references to widgets
   var currentTime = document.getElementById("currentTime");   
   var canvas = document.getElementById("clock");
   var context = canvas.getContext("2d");
   
   /* sets the size of the canvas element and the size of the element for 
      the digital clock. */
   context.canvas.width = CLOCK_SIZE;
   context.canvas.height = CLOCK_SIZE;   
   document.getElementById("currentTime").style.width = String(CLOCK_SIZE) + "px";
   
   // Function that starts a timer for the clocks.
   var startTimer = function() {
      setInterval(displayTime, 1000);
      displayTime();
   }
   
   /* get current time (hours, minutes, seconds) and transfer them to digital
      and analog clocks.*/
   var displayTime = function () {
   
      timeNow = new Date();
      h = timeNow.getHours();
      m = timeNow.getMinutes();
      s = timeNow.getSeconds();
	  
      digitalClock(h, m, s);
	  analogClock(h, m, s);
   }
   
   /* prepare reading for the digitalClock and change the content of the 
      corresponding HTML element to it. */
   var digitalClock = function (h, m, s) {
      var nowString = formatHour(h) + ":" + padZero(m) + ":" + padZero(s) + " "
                   	  + getTimePeriod(h);
      currentTime.innerHTML = nowString;
   }
   
   // adds a zero to in front of the number if needed (i.e. 1 --> 01; 10 -->10)
   var padZero = function (number) {
      if (number < 10) {
	     return "0" + String(number);
      } else {
         return String(number);
      }
   }
   
   // convert hour in army representation to the civil representation.
   var formatHour = function (h) {
      var hour = h % 12;
      if (hour == 0) {
         hour = 12;
      }
	  return String(hour);
   }
   
   // tells it is AM or PM.
   var getTimePeriod = function (h) {
      return (h < 12) ? "AM" : "PM"; 
   }
   
   /* function that adds the face (image) and arms (lines) of the analog clock
      to the canvas element*/
   var analogClock = function(h, m, s){
      
	  var faceImage = new Image();
	  faceImage.src = 'face.jpg';
	  // register onload handler for the face (image) of the analog clock
	  faceImage.onload = function() {
	  
	     var secondArmLength = 0.95;
		 var minuteArmLength = 0.75;
		 var hourArmLength = 0.70;
	     context.drawImage(faceImage, 0, 0, CLOCK_SIZE, CLOCK_SIZE);
		 drawArm(canvas, context, s / 60, 2, secondArmLength, '#FF0000'); // Second
		 drawArm(canvas, context, m / 60, 4, minuteArmLength, '#000000'); // Minute
         drawArm(canvas, context, h / 12, 8, hourArmLength, '#000000'); // Hour
      } // end image-load event handler
   }
   
   // function that finds the position of the corresponding arm and draws it.
   var drawArm = function(canvas, context, position, armThickness, armLength, armColor) {
      // sets the radius.
      var clockRadius = CLOCK_SIZE / 2;
	  
	  // finds the position of the arm.
	  var armRadians = (2 * Math.PI * position) - (2 * Math.PI / 4);
      var targetX = (CLOCK_SIZE / 2) + Math.cos(armRadians) * (armLength * clockRadius);
      var targetY = (CLOCK_SIZE / 2) + Math.sin(armRadians) * (armLength * clockRadius);
	  
	  // draws the corresponding arm.
      context.lineWidth = armThickness;
      context.strokeStyle = armColor; 
      context.beginPath();
      context.moveTo(CLOCK_SIZE / 2, CLOCK_SIZE / 2); // Starts at the centre.
      context.lineTo(targetX, targetY); // Draws an arm outwards.
      context.stroke();
   }
   
   // starts a timer for the clocks.
   startTimer();

};  // end window-load event handler.