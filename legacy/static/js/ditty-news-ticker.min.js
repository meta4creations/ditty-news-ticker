/**
 * Ditty News Ticker
 * Date: 10/06/2014
 *
 * @author Metaphor Creations
 * @version 1.4.12
 *
 **/
!function(ot){var e={init:function(st){return this.each(function(){
/**
		     * Initialize the ticker
		     *
		     * @since 1.0.0
		     */
function r(){
// Save the tick count & total
J.tick_count=N.find(".mtphr-dnt-tick").length,
// Start the first tick
0<J.tick_count&&(
// Setup a ticker scroll
"scroll"===H.type?t():"rotate"===H.type&&v()),
// Trigger the afterLoad callback
H.after_load.call(K,N),K.trigger("mtphr_dnt_after_load_single",[J,Z]),ot("body").trigger("mtphr_dnt_after_load",[K,J,Z])}
/**
		     * Setup the ticker scroll
		     *
		     * @since 1.1.0
		     */function n(){
// Loop through the tick items
N.find(".mtphr-dnt-tick").each(function(){
// Find the greatest tick height
ot(this).height()>Y&&(Y=ot(this).height()),"up"!==H.scroll_direction&&"down"!==H.scroll_direction||ot(this).css("height","auto")}),
// Set the ticker height
N.css("height",Y+"px")}function t(){var t=N.find(".mtphr-dnt-tick:first");if(t.attr("style")){var e,i=t.attr("style").split("width:");tt=!(1<i.length)}
// Reset the ticks
Z=[],
//mtphr_dnt_scroll_set_height();
N.imagesLoaded(function(){n(),
// Loop through the tick items
N.find(".mtphr-dnt-tick").each(function(){
// Make sure the ticker is visible
ot(this).show();
// Add the tick data
var t=[{headline:ot(this)}];
// Add the tick to the array
Z.push(t)}),
// Set the initial position of the ticks
u(),
// Start the scroll loop
a()}),
// Clear the loop on mouse hover
N.hover(function(){H.scroll_pause&&s()},function(){H.scroll_pause&&!J.paused&&o()})}function s(){clearInterval($)}function o(){a()}
/**
		     * Create the ticker scroll loop
		     *
		     * @since 1.0.8
		     */function a(){
// Start the ticker timer
clearInterval($),$=setInterval(function(){for(var t=0;t<J.tick_count;t++)if(!0===Z[t][0].visible){var e="reset";"left"===H.scroll_direction||"right"===H.scroll_direction?"reset"===(e="left"===H.scroll_direction?i(t):c(t))?(e=Z[t][0].reset,Z[t][0].headline.stop(!0,!0).css("left",e+"px")):Z[t][0].headline.stop(!0,!0).animate({left:e+"px"},100,"linear"):"reset"===(e="up"===H.scroll_direction?p(t):l(t))?(e=Z[t][0].reset,Z[t][0].headline.stop(!0,!0).css("top",e+"px")):Z[t][0].headline.stop(!0,!0).animate({top:e+"px"},100,"linear"),Z[t][0].position=e}},100)}
/**
		     * Scroll the ticker left
		     *
		     * @since 1.0.0
		     */function i(t){
// Find the new position
var e=parseFloat(Z[t][0].position-H.scroll_speed);
// Reset the tick if off the screen
return e<-(Z[t][0].headline.width()+H.offset)?e=_(t):e<parseFloat(X-Z[t][0].headline.width()-H.scroll_spacing)&&f(t),e}
/**
		     * Scroll the ticker right
		     *
		     * @since 1.0.0
		     */function c(t){
// Find the new position
var e=Z[t][0].position+H.scroll_speed;
// Reset the tick if off the screen
return e>X+H.offset?e=_(t):e>H.scroll_spacing&&f(t),e}
/**
		     * Scroll the ticker up
		     *
		     * @since 1.0.0
		     */function p(t){
// Find the new position
var e=Z[t][0].position-H.scroll_speed;
// Reset the tick if off the screen
return e<-(Z[t][0].headline.height()+H.offset)?e=_(t):e<Y-Z[t][0].headline.height()-H.scroll_spacing&&f(t),e}
/**
		     * Scroll the ticker down
		     *
		     * @since 1.0.0
		     */function l(t){
// Find the new position
var e=Z[t][0].position+H.scroll_speed;
// Reset the tick if off the screen
return e>Y+H.offset?e=_(t):e>H.scroll_spacing&&f(t),e}
/**
		     * Check the current tick position
		     *
		     * @since 1.0.0
		     */function _(t){return 1<J.tick_count&&(Z[t][0].visible=!1),
// Add a scroll complete trigger
J.tick_count===t+1&&(K.trigger("mtphr_dnt_scroll_complete",[J,Z]),ot("body").trigger("mtphr_dnt_scroll_complete",[K,J,Z])),"reset"}function e(t){!1===Z[t][0].visible&&(J.previous_tick=parseInt(t-1),J.previous_tick<0&&(J.previous_tick=parseInt(J.tick_count-1)),J.current_tick=t,J.next_tick=parseInt(t+1),J.next_tick>=J.tick_count&&(J.next_tick=0))}
/**
		     * Check the next tick visibility
		     *
		     * @since 1.0.0
		     */function f(t){t===J.tick_count-1?H.scroll_loop&&(e(0),Z[0][0].visible=!0):(e(parseInt(t+1)),Z[t+1][0].visible=!0)}
/**
		     * Resize the scroll ticks
		     *
		     * @since 1.1.0
		     */function d(){for(var t=0;t<J.tick_count;t++){
// Set the tick position
var e,i=Z[t][0].headline;switch(H.scroll_direction){case"left":e=X+H.offset,!1===Z[t][0].visible&&i.css("left",e+"px");break;case"right":e=parseInt("-"+(i.width()+H.offset)),!1===Z[t][0].visible&&i.css("left",e+"px");break;case"up":tt&&i.css("width",X),e=parseInt(Y+H.offset),!1===Z[t][0].visible&&i.css("top",e+"px");break;case"down":tt&&i.css("width",X),e=parseInt("-"+(i.height()+H.offset)),!1===Z[t][0].visible&&i.css("top",e+"px");break}
// Adjust the tick data
Z[t][0].width=i.width(),Z[t][0].height=i.height(),!1===Z[t][0].visible&&(Z[t][0].position=e),Z[t][0].reset=e}}
/**
		     * Reset the scroller for vertical scrolls
		     *
		     * @since 1.1.0
		     */function u(){for(var t,e,i=0;i<J.tick_count;i++)if(Z[i]){switch(e=Z[i][0].headline,H.scroll_direction){case"left":t=X+H.offset,e.stop(!0,!0).css("left",t+"px");break;case"right":
//console.log(settings.offset);
t=parseInt("-"+(e.width()+H.offset)),
/*
									if( mtphr_dnt_vars.is_rtl ) {
										position = parseInt('-'+($tick.width()+(ticker_width/2)));
									}
*/
e.stop(!0,!0).css("left",t+"px");break;case"up":tt&&e.css("width",X),t=parseInt(Y+H.offset),e.stop(!0,!0).css("top",t+"px");break;case"down":tt&&e.css("width",X),t=parseInt("-"+(e.height()+H.offset)),e.stop(!0,!0).css("top",t+"px");break}Z[i][0].width=e.width(),Z[i][0].height=e.height(),Z[i][0].position=t,Z[i][0].reset=t,Z[i][0].visible=!1}
// Reset the current tick
// Set the ticks to display on init
if(J.current_tick=0,
// Set the first tick visibility
Z[J.current_tick][0].visible=!0,H.scroll_init)for("left"===H.scroll_direction?t=.1*X:"right"===H.scroll_direction?t=.9*X:"up"===H.scroll_direction?t=.1*Y:"down"===H.scroll_direction&&(t=.9*Y),i=0;i<J.tick_count;i++)switch(e=Z[i][0].headline,H.scroll_direction){case"left":t<X&&(e.stop(!0,!0).css("left",t+"px"),Z[i][0].position=t,Z[i][0].visible=!0,t=t+Z[i][0].width+H.scroll_spacing);break;case"right":0<t&&(t-=Z[i][0].width,e.stop(!0,!0).css("left",t+"px"),Z[i][0].position=t,Z[i][0].visible=!0,t-=H.scroll_spacing);break;case"up":t<Y&&(e.stop(!0,!0).css("top",t+"px"),Z[i][0].position=t,Z[i][0].visible=!0,t=t+Z[i][0].height+H.scroll_spacing);break;case"down":0<t&&(t-=Z[i][0].height,e.stop(!0,!0).css("top",t+"px"),Z[i][0].position=t,Z[i][0].visible=!0,t-=H.scroll_spacing);break}}function h(){k()}function g(){clearInterval(et)}
/**
		     * Setup the ticker rotator
		     *
		     * @since 1.0.8
		     */function v(){switch(
// Loop through the tick items
N.find(".mtphr-dnt-tick").each(function(){
// Add the tick to the array
Z.push(ot(this)),ot(this).imagesLoaded(function(){x()})}),
// Resize the ticks
x(),
// Loop through the tick items
N.find(".mtphr-dnt-tick").show(),H.rotate_type){case"fade":I(N,Z,parseInt(100*H.rotate_speed),H.rotate_ease);break;case"slide_left":D(N,Z,parseInt(100*H.rotate_speed),H.rotate_ease);break;case"slide_right":L(N,Z,parseInt(100*H.rotate_speed),H.rotate_ease);break;case"slide_down":j(N,Z,parseInt(100*H.rotate_speed),H.rotate_ease);break;case"slide_up":M(N,Z,parseInt(100*H.rotate_speed),H.rotate_ease);break}m(0),
// Start the rotator rotate
H.auto_rotate&&h(),
// Clear the loop on mouse hover
N.hover(function(){H.auto_rotate&&H.rotate_pause&&!J.running&&g()},function(){H.auto_rotate&&H.rotate_pause&&!J.running&&!J.paused&&h()})}
/**
		     * Create the ticker rotator loop
		     *
		     * @since 1.0.0
		     */function k(){
// Start the ticker timer
g(),et=setInterval(function(){
// Find the new tick
var t=parseInt(J.current_tick+1);t===J.tick_count&&(t=0),y(t)},parseInt(1e3*H.rotate_delay))}
/**
		     * Create the rotator update call
		     *
		     * @since 1.1.7
		     */function y(t){J.current_tick!==t&&(
// Clear the interval
H.auto_rotate&&g(),
// Set the next variable
J.next_tick=t,
// Trigger the before change callback
H.before_change.call(K,N),K.trigger("mtphr_dnt_before_change_single",[J,Z]),ot("body").trigger("mtphr_dnt_before_change",[K,J,Z]),
// Set the running variable
J.running=1,
// Rotate the current tick out
w(t),
// Rotate the new tick in
b(t),
// Set the previous & current tick
J.previous_tick=J.current_tick,J.current_tick=t,
// Trigger the after change callback
rt=setTimeout(function(){H.after_change.call(K,N),K.trigger("mtphr_dnt_after_change_single",[J,Z]),ot("body").trigger("mtphr_dnt_after_change",[K,J,Z]),
// Reset the rotator type & variables
it=H.rotate_type,J.reverse=0,J.running=0,
// Restart the interval
H.auto_rotate&&!J.paused&&k()},parseInt(100*H.rotate_speed)))}
/**
		     * Update the control links
		     *
		     * @since 1.0.0
		     */function m(t){U&&(U.children("a").removeClass("active"),U.children('a[href="'+t+'"]').addClass("active"))}
/**
		     * Create the rotator in function calls
		     *
		     * @since 1.0.0
		     */function b(t){switch(
// Update the links
m(t),it){case"fade":F(N,ot(Z[t]),ot(Z[J.current_tick]),parseInt(100*H.rotate_speed),H.rotate_ease);break;case"slide_left":W(N,ot(Z[t]),ot(Z[J.current_tick]),parseInt(100*H.rotate_speed),H.rotate_ease);break;case"slide_right":O(N,ot(Z[t]),ot(Z[J.current_tick]),parseInt(100*H.rotate_speed),H.rotate_ease);break;case"slide_down":E(N,ot(Z[t]),ot(Z[J.current_tick]),parseInt(100*H.rotate_speed),H.rotate_ease);break;case"slide_up":Q(N,ot(Z[t]),ot(Z[J.current_tick]),parseInt(100*H.rotate_speed),H.rotate_ease);break}}
/**
		     * Create the rotator out function calls
		     *
		     * @since 1.0.0
		     */function w(t){switch(it){case"fade":C(N,ot(Z[J.current_tick]),ot(Z[t]),parseInt(100*H.rotate_speed),H.rotate_ease);break;case"slide_left":z(N,ot(Z[J.current_tick]),ot(Z[t]),parseInt(100*H.rotate_speed),H.rotate_ease);break;case"slide_right":T(N,ot(Z[J.current_tick]),ot(Z[t]),parseInt(100*H.rotate_speed),H.rotate_ease);break;case"slide_down":A(N,ot(Z[J.current_tick]),ot(Z[t]),parseInt(100*H.rotate_speed),H.rotate_ease);break;case"slide_up":R(N,ot(Z[J.current_tick]),ot(Z[t]),parseInt(100*H.rotate_speed),H.rotate_ease);break}}
/**
		     * Resize the rotator ticks
		     *
		     * @since 1.0.8
		     */function x(){for(var t=0;t<J.tick_count;t++)
// Set the width of the tick
ot(Z[t]).width(X+"px"),J.current_tick!==t&&ot(Z[t]).css({left:parseFloat(X+H.offset)+"px"});
// Resize the ticker
var e=ot(Z[J.current_tick]).height();N.stop().css("height",e+"px")}
/**
		     * Rotator fade scripts
		     *
		     * @since 1.0.0
		     */function I(t,e){
// Get the first tick
var i=e[0],r=i.height();
// Find the width of the tick
// Set the height of the ticker
t.css("height",r+"px"),i.css({opacity:1,left:"auto"})}
// Show the new tick
function F(t,e,i,r,n){e.css({opacity:0,left:"auto"}),e.stop().animate({opacity:1},r,n);var s=e.height();
// Resize the ticker
t.stop().animate({height:s+"px"},r,n)}
// Hide the old tick
function C(t,e,i,r,n){e.stop().animate({opacity:0},r,n,function(){ot(this).css({left:parseFloat(X+H.offset)+"px"}),e.remove(),t.append(e)})}
/**
		     * Rotator slide left scripts
		     *
		     * @since 1.0.0
		     */function D(t,e){
// Get the first tick
var i=e[0],r=i.height();
// Find the dimensions of the tick
// Set the height of the ticker
t.css("height",r+"px"),
// Set the initial position of the width & make sure it's visible
i.css({opacity:1,left:0})}
// Show the new tick
function W(t,e,i,r,n){
// Find the dimensions of the tick
var s=e.height();
// Set the initial position of the width & make sure it's visible
e.css({opacity:1,left:parseFloat(X+H.offset)+"px"}),
// Resize the ticker
t.stop().animate({height:s+"px"},r,n,function(){}),
// Slide the tick in
e.stop().animate({left:"0"},r,n,function(){})}
// Hide the old tick
function z(t,e,i,r,n){
// Slide the tick in
e.stop().animate({left:"-"+parseFloat(X+H.offset)+"px"},r,n,function(){e.css({opacity:0}),e.remove(),t.append(e)})}
/**
			   * Rotator slide right scripts
			   *
			   * @since 1.0.0
			   */function L(t,e){
// Get the first tick
var i=e[0],r=i.height();
// Find the dimensions of the tick
// Set the height of the ticker
t.css("height",r+"px"),
// Set the initial position of the width & make sure it's visible
i.css({opacity:1,left:0})}
// Show the new tick
function O(t,e,i,r,n){
// Find the dimensions of the tick
var s=e.height();
// Set the initial position of the width & make sure it's visible
e.css({opacity:1,left:"-"+parseFloat(X+H.offset)+"px"}),
// Resize the ticker
t.stop().animate({height:s+"px"},r,n,function(){}),
// Slide the tick in
e.stop().animate({left:"0"},r,n)}
// Hide the old tick
function T(t,e,i,r,n){
// Slide the tick in
e.stop().animate({left:parseFloat(X+H.offset)+"px"},r,n,function(){e.css({opacity:0}),e.remove(),t.append(e)})}
/**
			   * Rotator slide down scripts
			   *
			   * @since 1.0.0
			   */function j(t,e){
// Get the first tick
var i=e[0],r=i.height();
// Find the height of the tick
// Set the height of the ticker
t.css("height",r+"px"),
// Set the initial position of the width & make sure it's visible
i.css({opacity:1,top:0,left:"auto"})}
// Show the new tick
function E(t,e,i,r,n){
// Find the height of the tick
var s=e.height();
// Set the initial position of the width & make sure it's visible
e.css({opacity:1,top:"-"+parseFloat(s+H.offset)+"px",left:"auto"}),
// Resize the ticker
t.stop().animate({height:s+"px"},r,n),
// Slide the tick in
e.stop().animate({top:"0"},r,n)}
// Hide the old tick
function A(t,e,i,r,n){
// Find the height of the next tick
var s=i.height();
// Slide the tick in
e.stop().animate({top:parseFloat(s+H.offset)+"px"},r,n,function(){e.css({opacity:0}),e.remove(),t.append(e)})}
/**
			   * Rotator slide up scripts
			   *
			   * @since 1.0.0
			   */function M(t,e){
// Get the first tick
var i=e[0],r=i.height();
// Find the height of the tick
// Set the height of the ticker
t.css({height:r+"px",left:"auto"}),
// Set the initial position of the width & make sure it's visible
i.css({opacity:1,top:0})}
// Show the new tick
function Q(t,e,i,r,n){
// Find the height of the tick
var s=e.height();
// Set the initial position of the width & make sure it's visible
e.css({opacity:1,top:parseFloat(i.height()+H.offset)+"px",left:"auto"}),
// Resize the ticker
t.stop().animate({height:s+"px"},r,n),
// Slide the tick in
e.stop().animate({top:"0"},r,n)}
// Hide the old tick
function R(t,e,i,r,n){
// Find the height of the next tick
var s=e.height();
// Slide the tick in
e.stop().animate({top:"-"+parseFloat(s+H.offset)+"px"},r,n,function(){e.css({opacity:0}),e.remove(),t.append(e)})}
/* --------------------------------------------------------- */
/* !Set the next item */
/* --------------------------------------------------------- */function q(){if(J.running)return!1;
// Find the new tick
var t=parseInt(J.current_tick+1);t===J.tick_count&&(t=0),y(t)}
/* --------------------------------------------------------- */
/* !Set the previous item */
/* --------------------------------------------------------- */function B(){if(J.running)return!1;
// Find the new tick
var t=parseInt(J.current_tick-1);t<0&&(t=J.tick_count-1),H.nav_reverse&&("slide_left"===H.rotate_type?it="slide_right":"slide_right"===H.rotate_type?it="slide_left":"slide_down"===H.rotate_type?it="slide_up":"slide_up"===H.rotate_type&&(it="slide_down"),J.reverse=1),y(t)}
/**
		     * Navigation clicks
		     *
		     * @since 1.0.0
		     */
/* --------------------------------------------------------- */
/* !Play and pause - 2.0.4 */
/* --------------------------------------------------------- */
function G(t){t?(J.paused=!1,V.removeClass("paused"),"scroll"===H.type?o():h()):(J.paused=!0,V.addClass("paused"),"scroll"===H.type?s():g()),K.trigger("mtphr_dnt_play_pause",[J,Z])}
// Create default options
var H={id:"",type:"scroll",scroll_direction:"left",scroll_speed:10,scroll_pause:0,scroll_spacing:40,scroll_units:10,scroll_init:0,scroll_loop:1,rotate_type:"fade",auto_rotate:0,rotate_delay:10,rotate_pause:0,rotate_speed:10,rotate_ease:"easeOutExpo",nav_reverse:0,disable_touchswipe:0,offset:20,before_change:function(){},after_change:function(){},after_load:function(){}},J={id:H.id,tick_count:0,previous_tick:0,current_tick:0,next_tick:0,reverse:0,running:0,paused:0};
// Useful variables. Play carefully.
// Add any set options
st&&ot.extend(H,st);
// Create variables
var K=ot(this),N=K.find(".mtphr-dnt-tick-contents"),P=K.find(".mtphr-dnt-nav-prev"),S=K.find(".mtphr-dnt-nav-next"),U=K.find(".mtphr-dnt-control-links"),V=K.find(".mtphr-dnt-play-pause"),X=N.outerWidth(!0),Y=0,Z=[],$,tt=!0,et,it=H.rotate_type,rt;
// Add the vars
if(N.data("ditty:vars",J),P&&"rotate"===H.type&&(P.bind("click",function(t){t.preventDefault(),B()}),S.bind("click",function(t){t.preventDefault(),q()}))
/**
		     * Nav controls
		     *
		     * @since 1.0.2
		     */,U&&"rotate"===H.type&&U.children("a").bind("click",function(t){t.preventDefault();
// Find the new tick
var e=parseInt(ot(this).attr("href"));if(J.running)return!1;if(e===J.current_tick)return!1;var i=e<J.current_tick?1:0;H.nav_reverse&&i&&("slide_left"===H.rotate_type?it="slide_right":"slide_right"===H.rotate_type?it="slide_left":"slide_down"===H.rotate_type?it="slide_up":"slide_up"===H.rotate_type&&(it="slide_down"),J.reverse=1),y(e)}),V.bind("click",function(t){t.preventDefault(),G(J.paused)}),
/* --------------------------------------------------------- */
/* !Mobile swipe - 1.5.0 */
/* --------------------------------------------------------- */
"rotate"!==H.type||H.disable_touchswipe||N.swipe({triggerOnTouchEnd:!0,swipeLeft:function(){if(J.running)return!1;
// Find the new tick
var t=parseInt(J.current_tick+1);t===J.tick_count&&(t=0),"slide_left"!==H.rotate_type&&"slide_right"!==H.rotate_type||(it="slide_left"),y(t)},swipeRight:function(){if(J.running)return!1;
// Find the new tick
var t=parseInt(J.current_tick-1);t<0&&(t=J.tick_count-1),"slide_left"!==H.rotate_type&&"slide_right"!==H.rotate_type||(it="slide_right"),H.nav_reverse&&("slide_down"===H.rotate_type?it="slide_up":"slide_up"===H.rotate_type&&(it="slide_down"),J.reverse=1),y(t)}})
/* --------------------------------------------------------- */
/* !Listen for external events - 1.4.1 */
/* --------------------------------------------------------- */,K.on("mtphr_dnt_next",function(){q()}),K.on("mtphr_dnt_prev",function(){B()}),K.on("mtphr_dnt_goto",function(t,e){y(parseInt(e))}),K.on("mtphr_dnt_pause",function(){G()}),K.on("mtphr_dnt_play",function(){G(!0)}),
/**
		     * Resize listener
		     * Reset the ticker width
		     *
		     * @since 1.4.1
		     */
ot(window).resize(function(){
// Resize the tickers if the width is different
N.outerWidth()!==X&&(X=N.outerWidth(!0),"scroll"===H.type?("up"===H.scroll_direction||"down"===H.scroll_direction)&&tt?u():d():"rotate"===H.type&&x())}),
/* --------------------------------------------------------- */
/* !Listen for resize event from other plugins - 1.4.1 */
/* --------------------------------------------------------- */
K.on("mtphr_dnt_resize_single",function(){"scroll"===H.type?d():"rotate"===H.type&&x()}),ot("body").on("mtphr_dnt_resize",function(t,e){e&&0<=e.indexOf(H.id)&&("scroll"===H.type?d():"rotate"===H.type&&x())}),K.on("mtphr_dnt_replace_ticks",function(t,e,i){clearInterval($),K.find(".mtphr-dnt-tick").remove(),e.each(function(){N.append(ot(this))}),setTimeout(function(){r()},i)}),0===K.width())var nt=setInterval(function(){10<K.width()&&(clearInterval(nt),X=N.outerWidth(!0),r())},100);else r()})}};
/**
	 * Setup the class
	 *
	 * @since 1.0.0
	 */ot.fn.ditty_news_ticker=function(t){return e[t]?e[t].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof t&&t?void ot.error("Method "+t+" does not exist in ditty_news_ticker"):e.init.apply(this,arguments)}}(jQuery);