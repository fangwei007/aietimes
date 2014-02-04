/***********************************************
* Only use for 
* Popups
* 
***********************************************/

function popup(mylink, windowname){
	if (! window.focus)return true;
	var href;
	if (typeof(mylink) == 'string')
	   href=mylink;
	else
	   href=mylink.href;
	window.open(href, windowname, 'width=1000,height=400,scrollbars=yes');
	return false;
}

function popup1(mylink, windowname){
	if (! window.focus)return true;
	var href;
	if (typeof(mylink) == 'string')
	   href=mylink;
	else
	   href=mylink.href;
	window.open(href, windowname, 'width=600,height=400,scrollbars=yes');
	return false;
}

function popup2(mylink, windowname){
	if (! window.focus)return true;
	var href;
	if (typeof(mylink) == 'string')
	   href=mylink;
	else
	   href=mylink.href;
	window.open(href, windowname, 'width=750,height=400,scrollbars=yes');
	return false;
}

function popup3(mylink, windowname){
	if (! window.focus)return true;
	var href;
	if (typeof(mylink) == 'string')
	   href=mylink;
	else
	   href=mylink.href;
	window.open(href, windowname, 'width=750,height=600,scrollbars=yes');
	return false;
}


function popup4(mylink, windowname){
	if (! window.focus)return true;
	var href;
	if (typeof(mylink) == 'string')
	   href=mylink;
	else
	   href=mylink.href;
	window.open(href, windowname, 'width=600,height=300,scrollbars=yes');
	return false;
}


/***********************************************
* AnyLink Drop Down Menu-  Dynamic Drive (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit http://www.dynamicdrive.com/ for full source code
***********************************************/

//Contents for menu 1 > you shop we ship
var menu1=new Array()
menu1[0]='<a href="howworks.php" style="text-decoration: none;">How it works</a>'
menu1[1]= '<hr>';
menu1[2]='<a href="shoptips.php" style="text-decoration: none;">Shopping Tips</a>'
menu1[3]= '<hr>';
//menu1[4]='<a href="shiptips.php" style="text-decoration: none;">Shipping Tips</a>'
menu1[4]='<a href="personal-assistant.php" style="text-decoration: none;">Personal Assistant</a>'
menu1[5]= '<hr>';
menu1[6]='<a href="conv.php" style="text-decoration: none;">Converter Tips</a>'
menu1[7]= '<hr>';
menu1[8]='<a href="proitems.php" style="text-decoration: none;">Prohibited Items</a>'

//Contents for menu 2 > services
/* var menu2=new Array()
menu2[0]='<a href="shipassist.php" style="text-decoration: none;">USA Shipping Assistant</a>'
menu2[1]= '<hr>';
menu2[2]='<a href="mailadd.php" style="text-decoration: none;">USA Mailing Address</a>'
menu2[3]= '<hr>';
menu2[4]='<a href="phnumber.php" style="text-decoration: none;">USA Phone Numbers</a>'
menu2[5]= '<hr>';
menu2[6]='<a href="cc.php" style="text-decoration: none;">USA Credit Cards</a>'
menu2[7]= '<hr>';
menu2[8]='<a href="intlservice.php" style="text-decoration: none;">International Shipping &nbsp;Services</a>'
menu2[9]= '<hr>';
menu2[10]='<a href="impexp.php" style="text-decoration: none;">Import/Export</a>'
menu2[11]= '<hr>';
menu2[12]='<a href="domesticship.php" style="text-decoration: none;">USA Domestic Shipping</a>' */
var menu2=new Array()
menu2[0]='<a href="youshop.php" style="text-decoration: none;">You Shop We Ship</a>'
menu2[1]= '<hr>';
menu2[2]='<a href="mailadd.php" style="text-decoration: none;">USA Mailing Address</a>'
menu2[3]= '<hr>';
menu2[4]='<a href="office-service.php" style="text-decoration: none;">USA Office Services</a>'
menu2[5]= '<hr>';
menu2[6]='<a href="wirehouse-service.php" style="text-decoration: none;">USA Warehouse Services</a>'
menu2[7]= '<hr>';
menu2[8]='<a href="impexp.php" style="text-decoration: none;">Import/Export</a>'
menu2[9]= '<hr>';
menu2[10]='<a href="domesticship.php" style="text-decoration: none;">USA Domestic Shipping</a>'
menu2[11]= '<hr>';
menu2[12]='<a href="intlservice.php" style="text-decoration: none;">International Shipping</a>'


//Contents for menu 3 > FAQ
var menu3=new Array()
menu3[0]='<a href="contactus.php" style="text-decoration: none;">Contact Us</a>'
menu3[1]='<hr>'
menu3[2]='<a href="faq.php" style="text-decoration: none;">FAQ</a>'

//Contents for menu 4 > FLAG
var menu4=new Array()
menu4[0]='<a href="china-about.php" style="text-decoration: none;"><img src="images/flag/china.gif" width="15" height="15" border="0" />&nbsp;China</a>'
menu4[1]='<a href="germany-about.php" style="text-decoration: none;"><img src="images/flag/germany.gif" width="15" height="15" border="0" />&nbsp;Germany</a>'
//menu4[2]='<a href="turkey-about.php" style="text-decoration: none;"><img src="images/flag/turkey.gif" width="15" height="15" border="0" />&nbsp;Turkey</a>'
	menu4[2]='<a href="http://www.aietr.com" style="text-decoration: none;"><img src="images/flag/turkey.gif" width="15" height="15" border="0" />&nbsp;Turkey</a>'
menu4[3]='<a href="france-about.php" style="text-decoration: none;"><img src="images/flag/france.gif" width="15" height="15" border="0" />&nbsp;France</a>'
menu4[4]='<a href="austria-about.php" style="text-decoration: none;"><img src="images/flag/austria.gif" width="15" height="15" border="0" />&nbsp;Austria</a>'
menu4[5]='<a href="spain-about.php" style="text-decoration: none;"><img src="images/flag/spanish.gif" width="15" height="15" border="0" />&nbsp;Spain</a>'
menu4[6]='<a href="japan-about.php" style="text-decoration: none;"><img src="images/flag/japan.gif" width="15" height="15" border="0" />&nbsp;Japan</a>'
menu4[7]='<a href="southkorea-about.php" style="text-decoration: none;"><img src="images/flag/s.korea.gif" width="15" height="15" border="0" />&nbsp;South Korea</a>'


var menuwidth='100px' 				//default menu width
var menubgcolor='lightyellow'  		//menu bgcolor
var disappeardelay=250  			//menu disappear speed onMouseout (in miliseconds)
var hidemenu_onclick="yes" 			//hide menu when user clicks within menu?

//No further editting needed
var ie4=document.all
var ns6=document.getElementById&&!document.all

if (ie4||ns6)
	document.write('<div id="dropmenudiv" style="z-index:5000px;visibility:hidden;width:'+menuwidth+';background-color:'+menubgcolor+'" onMouseover="clearhidemenu()" onMouseout="dynamichide(event)"></div>')

function getposOffset(what, offsettype){
	var totaloffset=(offsettype=="left")? what.offsetLeft : what.offsetTop;
	var parentEl=what.offsetParent;
	while (parentEl!=null){
	totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
	parentEl=parentEl.offsetParent;
	}
	return totaloffset;
}


function showhide(obj, e, visible, hidden, menuwidth){
	if (ie4||ns6)
	dropmenuobj.style.left=dropmenuobj.style.top=-500
	if (menuwidth!=""){
	dropmenuobj.widthobj=dropmenuobj.style
	dropmenuobj.widthobj.width=menuwidth
	}
	if (e.type=="click" && obj.visibility==hidden || e.type=="mouseover")
	obj.visibility=visible
	else if (e.type=="click")
	obj.visibility=hidden
}

function iecompattest(){
	return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function clearbrowseredge(obj, whichedge){
	var edgeoffset=0
	if (whichedge=="rightedge"){
	var windowedge=ie4 && !window.opera? iecompattest().scrollLeft+iecompattest().clientWidth-15 : window.pageXOffset+window.innerWidth-15
	dropmenuobj.contentmeasure=dropmenuobj.offsetWidth
	if (windowedge-dropmenuobj.x < dropmenuobj.contentmeasure)
	edgeoffset=dropmenuobj.contentmeasure-obj.offsetWidth
	}
	else{
	var windowedge=ie4 && !window.opera? iecompattest().scrollTop+iecompattest().clientHeight-15 : window.pageYOffset+window.innerHeight-18
	dropmenuobj.contentmeasure=dropmenuobj.offsetHeight
	if (windowedge-dropmenuobj.y < dropmenuobj.contentmeasure)
	edgeoffset=dropmenuobj.contentmeasure+obj.offsetHeight
	}
	return edgeoffset
}

function populatemenu(what){
	if (ie4||ns6)
	dropmenuobj.innerHTML=what.join("")
}


function dropdownmenu(obj, e, menucontents, menuwidth){
	if (window.event) event.cancelBubble=true
	else if (e.stopPropagation) e.stopPropagation()
	clearhidemenu()
	dropmenuobj=document.getElementById? document.getElementById("dropmenudiv") : dropmenudiv
	populatemenu(menucontents)
	
	if (ie4||ns6){
	showhide(dropmenuobj.style, e, "visible", "hidden", menuwidth)
	dropmenuobj.x=getposOffset(obj, "left")
	dropmenuobj.y=getposOffset(obj, "top")
	dropmenuobj.style.left=dropmenuobj.x-clearbrowseredge(obj, "rightedge")+"px"
	dropmenuobj.style.top=dropmenuobj.y-clearbrowseredge(obj, "bottomedge")+obj.offsetHeight+"px"
	}
	
	return clickreturnvalue()
}

function clickreturnvalue(){
	if (ie4||ns6) return false
	else return true
}

function contains_ns6(a, b) {
	while (b.parentNode)
	if ((b = b.parentNode) == a)
	return true;
	return false;
}

function dynamichide(e){
	if (ie4&&!dropmenuobj.contains(e.toElement))
	delayhidemenu()
	else if (ns6&&e.currentTarget!= e.relatedTarget&& !contains_ns6(e.currentTarget, e.relatedTarget))
	delayhidemenu()
}

function hidemenu(e){
	if (typeof dropmenuobj!="undefined"){
	if (ie4||ns6)
	dropmenuobj.style.visibility="hidden"
}
}

function delayhidemenu(){
	if (ie4||ns6)
	delayhide=setTimeout("hidemenu()",disappeardelay)
}

function clearhidemenu(){
	if (typeof delayhide!="undefined")
	clearTimeout(delayhide)
}

if (hidemenu_onclick=="yes")
	document.onclick=hidemenu