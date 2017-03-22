function getXHR()
	{
	  var xhr = null;
	  if(window.XMLHttpRequest)
	  {
	      xhr = new XMLHttpRequest();
	  }
	  else if(window.ActiveXObject)
	  {
	      xhr = new ActiveXObject("Microsoft.XMLHTTP");
	  }
          else
	  {
	      alert("XHR error.");
	  }
	  return xhr;
	}


