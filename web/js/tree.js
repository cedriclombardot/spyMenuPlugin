/////// Configuration Variables ///////////////////////////

treemenu.SymbolTag = 'span';// symbol inserted at beginning of <LI> tags
//treemenu.SymbolTag = '';// uncomment to disable insertion of symbols

treemenu.SymbolClassItem = 'symbol-item';
treemenu.SymbolClassClose = 'symbol-close';
treemenu.SymbolClassOpen = 'symbol-open';

treemenu.ClassItem = 'item';// class name added to <LI> tag's class
treemenu.ClassClose = 'close';// class name added to <LI> tag's class
treemenu.ClassOpen = 'open';// class name added to <LI> tag's class
treemenu.ClassLast = 'last';// added to last <LI> and symbol tags' classes

treemenu.CookieSaveStates = true;// flag to use a cookie to save menu state
treemenu.CookieExpire = 90;// days before cookie saving menu states expires

/////// End of Configuration Variables ///////////////////

function make_tree_menu(id,omit_symbols,no_save) {
var m = new treemenu(id);
if (omit_symbols) m.SymbolTag = '';
if (no_save) m.CookieSaveStates = false;
m.setup();
return m;
}

/*
* treemenu
*/

function treemenu(ul_id) {// object constructor

this.top_ul_id = ul_id;
this.top_ul = document.getElementById(ul_id);
this.configure();

// Register menu
treemenu.menus[ul_id] = this;

return this;
}

/*
* treemenu Class Variables
*/

treemenu.menus = [];// list of defined menus

/*
* treemenu Class Methods
*/

treemenu.toggle = function(e) {
var m = treemenu.menus[treemenu.get_top_ul(e).id];
var li = treemenu.get_li(e);
var ul = li.getElementsByTagName("UL")[0];
if (ul.style.display == "block") m.hide_menu(li,ul);
else m.show_menu(li,ul);

m.save_menu_states();
}

treemenu.show = function(ul) {
var m = treemenu.menus[treemenu.get_top_ul(ul).id];
var li = treemenu.get_li(ul);
m.show_menu(li,ul);
}

treemenu.hide = function(ul) {
var m = treemenu.menus[treemenu.get_top_ul(ul).id];
var li = treemenu.get_li(ul);
m.hide_menu(li,ul);
}

// Private methods
treemenu.get_top_ul = function(e) {
while (e && ((e.nodeName!= 'UL' )||(! e.id )||(! treemenu.menus[e.id]))){
	e = e.parentNode;
}
return e;
}

treemenu.get_li = function(e) {
while (e && e.nodeName!= 'LI') e = e.parentNode;
return e;
}

/*
* treemenu Object Methods
*/

treemenu.prototype.configure = function() {

// Assign global class settings (capitalized variables) to object settings.

var v,c;
for (v in treemenu) {
c = v.substr(0,1);
if (c == c.toUpperCase()) {
this[v] = treemenu[v];
}
}
}

treemenu.prototype.setup = function() {

// Insert open/close symbols at the beginning of the menu items
// and open or close menus like they were previously.

var states = this.get_menu_states();

var index = 0;
var ul, li, symbol, islast = false;
var ul_elements, li_elements = this.top_ul.getElementsByTagName("LI");
for(var i=0; i < li_elements.length; i++) {
li = li_elements[i];

if (this.ClassLast) islast = this.is_last_item(li);

ul_elements = li.getElementsByTagName("UL");
if(ul_elements.length > 0) {
// Submenus
if (this.SymbolTag) {
symbol = document.createElement(this.SymbolTag);
if (this.ClassLast && islast) symbol.className = this.ClassLast;
symbol.onclick = function() { treemenu.toggle(this); };
li.insertBefore(symbol, li.firstChild);
}

ul = ul_elements[0];
if (states[index] == '1') this.show_menu(li,ul);
else this.hide_menu(li,ul);
index++;
}
else {
// menu item
if (this.SymbolTag) {
symbol = document.createElement(this.SymbolTag);
if (this.SymbolClassItem)
symbol.className = this.SymbolClassItem;
if (this.ClassLast && islast)
symbol.className += ' ' + this.ClassLast;
li.insertBefore(symbol, li.firstChild);
}

if (this.ClassItem) li.className += ' ' + this.ClassItem;
}

if (islast) li.className += ' ' + this.ClassLast;
}
}

treemenu.prototype.is_last_item = function(e) {
// Check if element is the last LI element in the list.
e = e.nextSibling;
// Get next element (Mozilla puts text nodes at same level here).
while (e &&! e.tagName) e = e.nextSibling;
return e? false : true;
}

treemenu.prototype.get_menu_states = function() {
var cookie = getCookie("em_" + this.top_ul_id);
if (cookie) return cookie.split(',');
return [];
}

treemenu.prototype.save_menu_states = function() {

// Save all menu and submenu open/close states in a cookie

if (! this.CookieSaveStates) return;

var states = [];
var ul_elements, li_elements = this.top_ul.getElementsByTagName("LI");
for(var i=0; i < li_elements.length; i++) {
ul_elements = li_elements[i].getElementsByTagName("UL");
if (ul_elements.length > 0) {
states[states.length] = ul_elements[0].style.display == "block"? 1 : 0;
}
}

var expire_date = new Date((new Date().getTime()) + this.CookieExpire*24*60*60*1000);
setCookie("em_" + this.top_ul_id, states.join(','), expire_date);
}

treemenu.prototype.show_menu = function(li,ul) {
ul.style.display = 'block';

if (this.ClassClose)
li.className = li.className.replace(this.ClassClose,'');
if (this.ClassOpen)
li.className += ' ' + this.ClassOpen;

if (this.SymbolTag) {
var symbol = li.getElementsByTagName(this.SymbolTag)[0];
if (this.SymbolClassClose)
symbol.className = symbol.className.replace(this.SymbolClassClose,'');
if (this.SymbolClassOpen)
symbol.className += ' ' + this.SymbolClassOpen;
}
}

treemenu.prototype.hide_menu = function(li,ul) {
ul.style.display = 'none';

if (this.ClassOpen)
li.className = li.className.replace(this.ClassOpen,'');
if (this.ClassClose)
li.className += ' ' + this.ClassClose;

if (this.SymbolTag) {
var symbol = li.getElementsByTagName(this.SymbolTag)[0];
if (this.SymbolClassOpen)
symbol.className = symbol.className.replace(this.SymbolClassOpen,'');
if (this.SymbolClassClose)
symbol.className += ' ' + this.SymbolClassClose;
}
}

/*
* Classic Cookie functions
*/

function setCookie(name, value, expires, path, domain, secure) {
document.cookie= name + "=" + escape(value) +
(expires? "; expires=" + expires.toGMTString(): "") +
(path? "; path=" + path: "") +
(domain? "; domain=" + domain: "") +
(secure? "; secure": "");
}

function getCookie(name) {
var dc = document.cookie;
var prefix = name + "=";
var begin = dc.indexOf("; " + prefix);
if (begin == -1) {
begin = dc.indexOf(prefix);
if (begin!= 0) return null;
}
else {
begin += 2;
}
var end = document.cookie.indexOf(";", begin);
if (end == -1) end = dc.length;
return unescape(dc.substring(begin + prefix.length, end));
} 
