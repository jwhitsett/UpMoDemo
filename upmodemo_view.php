<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
<HEAD>
<TITLE>UpMo Demo Code</TITLE>
<META content="text/html; charset=UTF-8" http-equiv="Content-Type">

	<script type="text/javascript" src="http://platform.linkedin.com/in.js">
	  api_key: 36m0chgksues
	  authenticate: true
	</script> 
	<link media="all" type="text/css" href="http://developer.linkedinlabs.com/tutorials/css/jqueryui.css" rel="stylesheet"/>
	<link media="all" type="text/css" href="http://www.whitsett.net/upmodemo/styles/whitsett.css" rel="stylesheet">
</HEAD>
<BODY class="gradient-bg">
	<div id="mainlayout" class="nospace">
		<div id="wrapper" class="nospace">
	
			<div id="leftSide" class="nospace">
				<!-- place profile picture here -->
				<div id="pictureContainer"></div>
			</div><!--  leftSide -->
	
			<div id="rightSide" class="nospace">		
					<!--  All Page Content Goes Here... -->
				<div id="content" class="nospace">
						<div id="login-logout">					
							<img id="logout_btn" src="http://www.whitsett.net/upmodemo/images/LinkedinLogout.png" alt="Log out of Linkedin" onclick="logoutCurrentUser()"/>
						</div>
						<div id="sign-in">
							<p class="legend">Sign into your Linkedin Account<p>
							<script type="IN/Login" data-onAuth="onLinkedInAuth"></script>
						</div>
						<div id="profile"></div>
						<div id="connectionsDiv"></div>
				</div><!--  end of content div -->
			</div><!-- rightSide -->	
		</div>
	</div>
</BODY>
	<script type="text/javascript">
	
	function logoutCurrentUser() {
		IN.User.logout(loggedOutHandler, null)	
	}
	
	function loggedOutHandler(result) {
		if (result) {
			clearDisplay();
		}
	}
	
	function clearDisplay() {
		document.getElementById("sign-in").style.display = "inline-block";
		document.getElementById("logout_btn").style.display = "none";
		var profile = document.getElementById("profile");
		if (profile) {
			cleanElement(profile);
			profile.style.display = "none";
		}	
		var picture = document.getElementById("pictureContainer");
		if (picture) {
			cleanElement(picture);
		}
		var connections = document.getElementById("connectionsDiv");
		if (connections) {
			cleanElement(connections);
		}
	}
	
	function cleanElement(elem) {
		if (elem) {
			while (elem.hasChildNodes()) {
				elem.removeChild(elem.firstChild);
			}		
		}
	}
	
	function onLinkedInAuth() {
		document.getElementById("sign-in").style.display = "none";
		document.getElementById("logout_btn").style.display = "inline-block";
	
		IN.API.Profile("me")
			.fields(["id", "firstName", "lastName", "pictureUrl", "publicProfileUrl","headline"])
			.result( function (result) {
		      var id = result.values[0].id;
		      displayData(result.values[0]);
		      storeUser(result.values[0]); // We'll use an AJAX call to store this user's info to the MySql database on the server for the 2nd view (tabular view of table rows)
		    })
			.error(displayProfileErrors);
	}
	
	function storeUser(userData) {
		if (userData) {
			var params = "fName=" + userData.firstName + "&lName=" + userData.lastName + "&headline="
				+ userData.headline + "&linkedid=" + userData.id;
			var o_Loader = new XHRLoader("http://www.whitsett.net"); // XMLHTTPRequest Object
			if (o_Loader) {
				o_Loader.load("GET", "/storeUserData.php", params, storeUserDataResultHandler, false);
			}
		}
	}
	
	function storeUserDataResultHandler(result) {
		if (result) {
			// We could let the user know if their info was stored, if it already exists in the database, or if there was an insert error here...	
		}	
	}
	
	function displayData(userData) {
		if (userData.pictureUrl && userData.pictureUrl != "") {
			var pictureDiv = document.getElementById("pictureContainer");
			if (pictureDiv) {
				var pic = document.createElement("IMAGE");
				if (pic) {
					pic.alt = "Linkedin Profile Picture for " + userData.firstName + " " + userData.lastName;
					pic.src = userData.pictureUrl;
					pictureDiv.appendChild(pic);
				}
			}
		}
	      
	      var profileHTML = "<p><a class=\"profilelink\" href=\"" + userData.publicProfileUrl + "\" target=\"_Blank\">";
	      profileHTML += userData.firstName + " " + userData.lastName + "</a><br />";
	      profileHTML += "<span class=title-black>" + userData.headline + "</span></p>";
	      $("#profile").html(profileHTML);
	      $("#profile").css("display","block");
	      
	      IN.API.Connections("me")
	      .fields("firstName", "lastName", "pictureUrl", "publicProfileUrl", "headline")
	      .result(displayConnections)
	      .error(displayConnectionErrors);
	
	}
	
	function displayProfileErrors(error) {
		$("#profile").html("<p>An error occured while loading your profile. Please reload this page and try again.</p>");
		console.log(error);
	}
	
	function displayConnections(result) {
	    if (result && result.values && result.values.length > 0) {
	    	var connDisplay = document.getElementById("connectionsDiv");
	    	if (connDisplay) {
	    		var header = document.createElement("DIV");
	    		if (header) {
	    			header.className = "headerDiv title-black";
	    			header.appendChild(document.createTextNode("Your " + result.values.length + " Connections"));
	    			connDisplay.appendChild(header);
	    		}
				while (result.values.length) {
	    			for (var i=0; i < 4; ++i) {
	    				var connectionObj = result.values.shift();
			    		if (connectionObj) {
							var newConn = buildConnection(connectionObj);
							if (newConn) {
								connDisplay.appendChild(newConn);
							}
			    		}
			    	}
					var br = document.createElement("BR");
					if (br) connDisplay.appendChild(br);
	    		}
	    	}
	    }
	}
	
	function displayConnectionErrors(error) {
		$("#connections").html("<p>An error occured while loading your Connections. Please reload this page and try again.</p>");
		console.log(error);
	}
	
	function buildConnection(connection) {
		if (connection) {
			var wrapper = document.createElement("DIV");
			if (wrapper) {
				wrapper.className = "connection";
	
				var pic = document.createElement("IMG");
				if (pic) {
					pic.alt = "";
					pic.src = (connection.pictureUrl && connection.pictureUrl != "" && connection.pictureUrl != " ") ? connection.pictureUrl : "http://www.whitsett.net/upmodemo/images/missing.jpg";
					pic.className = "connectionpicture";
					wrapper.appendChild(pic);
				}
	
				var name = document.createElement("DIV");
				if (name) {
					name.className = "nameDiv";
					name.appendChild(document.createTextNode(connection.firstName + " " + connection.lastName));
					wrapper.appendChild(name);
				}
				return wrapper;
			}
		}
		return null;
	}
	 
	</script>
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.5b1.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.7/jquery-ui.min.js"></script>
	<script type="text/javascript" src="http://www.whitsett.net/upmodemo/js/upmodemo.js"></script> 
</HTML>
