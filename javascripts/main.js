ddaccordion.init({
	headerclass: "submenuheader", 
	contentclass: "submenu", 
	revealtype: "click", 
	mouseoverdelay: 200, 
	collapseprev: true, 
	defaultexpanded: [], 
	onemustopen: false,
	animatedefault: false, 
	persiststate: true, 
	toggleclass: ["", ""], 
	togglehtml: ["suffix", "<img src='../admin/images/plus.gif' class='statusicon' />", "<img src='../admin/images/minus.gif' class='statusicon' />"], 
	animatespeed: "fast", 
	oninit:function(headers, expandedindices){ 
		//do nothing
	},
	onopenclose:function(header, index, state, isuseractivated){ /
	}
})
