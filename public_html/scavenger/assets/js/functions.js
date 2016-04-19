window.onload = function() {
	//Hide loading Gif
		loadingGif = document.getElementById('loading_gif_wrap');
		loadingGif.style.transition = "opacity 2s";
		loadingGif.style.opacity = 0;
// 		setTimeout(hideCompletely, 2000);
		hideCompletely();		
	
	
	function hideCompletely() {
		loadingGif = document.getElementById('loading_gif_wrap');
		loadingGif.style.display = "none";
	}
	
	function GetXmlHttpObject()
	{
		var xmlHttp=null;
		try
		 {
		 // Firefox, Opera 8.0+, Safari
		 xmlHttp=new XMLHttpRequest();
		 }
		catch (e)
		 {
		 //Internet Explorer
		 try
		  {
		  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
		  }
		 catch (e)
		  {
		  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		 }
		return xmlHttp;
	}
	
	// Selects updates numbers
	function updateNumbers(followerArray)
	{ 
	
		xmlHttp=GetXmlHttpObject()
		if (xmlHttp==null)
		 {
		 alert("Browser does not support HTTP Request");
		 return;
		 }
		var url="/scavenger/platforms/twitter/unfollow-ajax.php";
		url+="?array="+followerArray;
		
		xmlHttp.onreadystatechange=stateChanged
		xmlHttp.open("GET",url,true)
		xmlHttp.send(null)
	}
	
	function stateChanged() 
	{ 
	
	    if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete") {
	         document.getElementById("resultWrap").innerHTML=xmlHttp.responseText;
	    } 
	}
};

