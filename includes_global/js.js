
///// TEMP HOLDER \\\\\

	function isEmail (s) {
		if (isEmpty(s)) return false;
		if (isWhitespace(s)) return false;
		var i = 1;
		var sLength = s.length;
		while ((i < sLength) && (s.charAt(i) != "@")) {i++}
		if ((i >= sLength) || (s.charAt(i) != "@")) return false;
		else i += 2;
		while ((i < sLength) && (s.charAt(i) != ".")) { i++ }
		if ((i >= sLength - 1) || (s.charAt(i) != ".")) return false;
		else return true;
	}

	function Validate(myForm) {
		if (!isLength(myForm.txtEmail.value, 1, 255)) return warnInvalid(myForm.txtEmail, "Email must be between 1 and 255 characters in length");
		if (!isEmail(myForm.txtEmail.value)) return warnInvalid(myForm.txtEmail, "Email address is invalid.");
		if (!isLength(myForm.txtPassword.value, 6, 255)) return warnInvalid(myForm.txtPassword, "Password must be at least 6 characters in length.");
		if (myForm.txtPassword.value!=myForm.txtPassword2.value) {
			return warnInvalid(myForm.txtPassword, "You did not retype your password correctly.  Please try again.");
		}
		return true;
	}




///// SITE-WIDE \\\\\


// SEARCH TEXTBOX AUTOMATIC CURSOR [almost all pages]

	function setFocus() {
		if(document.SEARCH) {
			document.SEARCH.Keywords.focus();
		}
	}



// SEARCH SUBMIT [almost all pages]

	function trySearch() {
		if(document.forms.SEARCH.Keywords.value != "") {
			document.forms.SEARCH.submit();
		} else {
			alert("Search field is blank.");

			setFocus()
		}
	}



// EMAIL LIST SIGNUP

	function verifyRequired() {
	  if (document.icpsignup.fields_email.value == "") {
		alert("The E-mail field is required.");
		return false;
	  }

		if (
		  !document.icpsignup["listid:34365"].checked &&
		  !document.icpsignup["listid:34370"].checked &&
		  !document.icpsignup["listid:34374"].checked &&
		  !document.icpsignup["listid:34372"].checked &&
		  !document.icpsignup["listid:34375"].checked &&
		  !document.icpsignup["listid:34376"].checked &&
		  !document.icpsignup["listid:34371"].checked &&
		  !document.icpsignup["listid:34373"].checked &&
		  !document.icpsignup["listid:34378"].checked &&
		  !document.icpsignup["listid:34366"].checked &&
		  !document.icpsignup["listid:34363"].checked &&
		  !document.icpsignup["listid:34365"].checked &&
		  !document.icpsignup["listid:34367"].checked &&
		  !document.icpsignup["listid:34369"].checked &&
		  true)  {
		alert("The Lists field is required.");
		return false;
	  } 

	return true;
	}



// POPUP

	function help(theTopic, theOption) {
		var theName = "help";
		var theOptions = "height=300, width=400, status=yes, toolbar=no, menubar=no, location=no, scrollbars=auto";
		var showClose

		if(theOption == "showClose") {
			showClose = "?showclose=true";
		} else {
			showClose = "";
		}

		if(theTopic == "cvv2") {
			theOptions = "height=550, width=700, status=yes, toolbar=no, menubar=no, location=no, scrollbars=no";
			theFile = "http://stockroom.com/general/help/sniplets/cvv2.aspx" + showClose;
		}

		if(theTopic == "livehelpna") {
			theOptions = "height=335, width=500, status=yes, toolbar=no, menubar=no, location=no, scrollbars=no";
			theFile = "http://stockroom.com/general/help/sniplets/livehelpna.aspx" + showClose;
		}

		window.open(theFile, theName, theOptions);
	}
	
	
//DIFFERENT POP UP

function  MM_openBrWindow(theURL,width,height) {window.open(theURL,null,'width=' + width + ',height=' + height + ',toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0');}
	
// ROLLOVER

	function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}




///// NON-SECURE \\\\\


// ECARD VALIDATION

	function validateEmail(theForm) {
	
		// Check for zero length, empty and null values
		if(theForm.mailTo.value.length == 0 || theForm.mailTo.value == "" || theForm.mailTo.value == null || theForm.mailTo.value == "[Valid Email Address]") {
			alert("A valid 'To' email address is required.");

			theForm.mailTo.focus();

			return false
		}

		// Check for zero length, empty and null values
		if(theForm.mailFrom.value.length == 0 || theForm.mailFrom.value == "" || theForm.mailFrom.value == null || theForm.mailFrom.value == "[Valid Email Address]") {
				alert("A valid 'From' email address is required.");

			theForm.mailFrom.focus();

			return false
		}

		return true
	}

	function clearIt(theElement) {
		if(theElement.name == "mailTo") {
			theElement.value = "";
		}
		
		if(theElement.name == "mailFrom") {
			theElement.value = "";
		}
		
		if(theElement.name == "emailCert") {
			theElement.value = "";
		}

		if(theElement.name == "recipient") {
			theElement.value = "";
		}
	}



// CONTACT FORM

	function validateContact(theForm) {
		// Check for zero length, empty and null values
		if(theForm.firstName.value.length == 0 || theForm.firstName.value == "" || theForm.firstName.value == null) {
			alert("Please enter your first name.");

			theForm.firstName.focus();

			return false
		}

		if(theForm.lastName.value.length == 0 || theForm.lastName.value == "" || theForm.lastName.value == null) {
			alert("Please enter your last name.");

			theForm.lastName.focus();

			return false
		}

		if(theForm.email.value.length == 0 || theForm.email.value == "" || theForm.email.value == null) {
			alert("A valid e-mail address is required.");

			theForm.email.focus();

			return false
		}

		// Check for proper subject selection
		if(theForm.subject.value.length == 0 || theForm.subject.value == "" || theForm.subject.value == null || theForm.subject.value == "0") {
			alert("Please select a valid subject.");

			theForm.subject.focus();

			return false
		}

		// Check for valid questions and comments entry
		if(theForm.comments.value.length == 0 || theForm.comments.value == "" || theForm.comments.value == null) {
			alert("Please enter your questions or comments.");

			theForm.comments.focus();

			return false
		}

		return true
	}



// PRODUCT SUGGESTIONS FORM

	function validateSuggestion(theForm) {
		// Check for zero length, empty and null values
		if(theForm.prod_name.value.length == 0 || theForm.prod_name.value == "" || theForm.prod_name.value == null) {
			alert("All fields are required.");

			theForm.prod_name.focus();

			return false
		}

		if(theForm.prod_desc.value.length == 0 || theForm.prod_desc.value == "" || theForm.prod_desc.value == null) {
			alert("All fields are required.");

			theForm.prod_desc.focus();

			return false
		}

		if(theForm.cust_name.value.length == 0 || theForm.cust_name.value == "" || theForm.cust_name.value == null) {
			alert("All fields are required.");

			theForm.cust_name.focus();

			return false
		}

		// Check for proper subject selection
		if(theForm.cust_email.value.length == 0 || theForm.cust_email.value == "" || theForm.cust_email.value == null) {
			alert("All fields are required.");

			theForm.cust_email.focus();

			return false
		}

		return true
	}



// OUR FACILITY POPUPS & MANUFACTURING POPUPS

	function  MM_openBrWindow(theURL,width,height) {
		window.open(theURL,null,'width=' + width + ',height=' + height + ',toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0');
		}



// PRODUCT PAGE

	function calculate() {
		if (document.nipple_calculations.circ_mm.value == null || document.nipple_calculations.circ_mm.value == "" || document.nipple_calculations.circ_mm.value == " " )
		{
			document.nipple_calculations.circ_mm.value = 0;
		}
		var circum = document.nipple_calculations.circ_mm.value;
		var diameter = circum/79.7965;
		document.nipple_calculations.diam_in.value = diameter;
	}

	// EMAIL PRODUCT TO FRIEND
	
		function validateEmailProduct(theForm) {
			// Check for zero length, empty and null values
			if(theForm.mailTo.value.length == 0 || theForm.mailTo.value == "" || theForm.mailTo.value == "[Valid Email Address]" || theForm.mailTo.value == null) {
				alert("A valid 'To' email address is required.");

				theForm.mailTo.focus();

				return false
			}

			// Check for zero length, empty and null values
			if(theForm.mailFrom.value.length == 0 || theForm.mailFrom.value == "" || theForm.mailFrom.value == "[Valid Email Address]" || theForm.mailFrom.value == null) {
				alert("A valid 'From' email address is required.");

				theForm.mailFrom.focus();

				return false
			}

			return true
		}

	// GIFT CERTIFICATE FIELDS
	
		function validateGiftCert(theForm) {
			// Check for zero length, empty and null values
			if(theForm.recipient.value.length == 0 || theForm.recipient.value == "" || theForm.recipient.value == "Enter Full Name" || theForm.recipient.value == null) {
				alert("A recipient name is required and will be needed for redemption.");

				theForm.recipient.focus();

				return false
			}

			return true
		}



// CATALOG REQUEST FORM

	function validateCatRequest(theForm) {
		if(stringCheck(theForm.ClubName.value) < 0) {
			alert("Name of Club/Organization required.");

			theForm.ClubName.focus();

			return false
		}

		if(stringCheck(theForm.ContactName.value) < 0) {
			alert("Your Name required.");

			theForm.ContactName.focus();

			return false
		}

		if(stringCheck(theForm.Address1.value) < 0) {
			alert("Address Line 1 required.");

			theForm.Address1.focus();

			return false
		}

		if(stringCheck(theForm.City.value) < 0) {
			alert("City required.");

			theForm.City.focus();

			return false
		}

		if(stringCheck(theForm.State.value) < 0) {
			alert("State required.");

			theForm.State.focus();

			return false
		}

		if(stringCheck(theForm.Country.value) < 0) {
			alert("Country required.");

			theForm.Country.focus();

			return false
		}

		if(stringCheck(theForm.froM.value) < 0) {
			alert("Your Email Address required.");

			theForm.froM.focus();

			return false
		}

		if (echeck(theForm.froM.value)==false){
			theForm.froM.value=""

			theForm.froM.focus()

			return false
		}
		
		if(stringCheck(theForm.FullCatalogQty.value) < 0) {
			alert("Quantity Desired required.");

			theForm.FullCatalogQty.focus();

			return false
		}
		
		return true
	}

	function stringCheck(theText) {
		var trimmedValue = theText.replace(/^\s*|\s*$/g,"");
		var score

		if(trimmedValue.length == 0) {
			score = -10
		} else { 
			score = 10
		}

		if(theText.length == 0 || theText == null) {
			score = -10
		} else {
			score = 10
		}

		return score
	}



///// SECURE \\\\\


// CREATE ACCOUNT FORM

	function validateAccount(theForm) {
		// Check for zero length, empty and null values
		if(theForm.txtEmail.value.length == 0 || theForm.txtEmail.value == "" || theForm.txtEmail.value == null) {
			alert("A valid e-mail address is required to create an account.");

			theForm.txtEmail.focus();

			return false
		}

		if (echeck(theForm.txtEmail.value)==false){
			theForm.txtEmail.value=""

			theForm.txtEmail.focus()

			return false
		}

		// Check the password for empty or null values
		if(theForm.txtPassword.value.length == 0 || theForm.txtPassword.value == "" || theForm.txtPassword.value == null) {
			alert("Please provide a valid password.");

			return false
		}

		// Check the password for spaces
		trimmedValue = "";
		trimmedValue = theForm.txtPassword.value.replace(/^\s*|\s*$/g,"");
		
		if(trimmedValue.length == 0) {
			alert("Please provide a valid password.");
			
			return false
		}

		// Check to see if the the password and confirmation password match
		if(theForm.txtPassword.value != theForm.txtPassword2.value) {
			alert("Your passwords do not match.");

			return false
		}

		return true
	}

	// this trims any spaces on left/right of email addresses entered when creating an account
	String.prototype.trim = function() {
	a = this.replace(/^\s+/, '');
	return a.replace(/\s+$/, '');
	};

	function echeck(str) {

		var at="@"
		var dot="."
		var lat=str.indexOf(at)
		var lstr=str.length
		var ldot=str.indexOf(dot)
		if (str.indexOf(at)==-1){
			alert("A valid e-mail address is required.")
		   return false
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
			alert("A valid e-mail address is required.")
		   return false
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
			alert("A valid e-mail address is required.")
			return false
		}

		 if (str.indexOf(at,(lat+1))!=-1){
			alert("A valid e-mail address is required.")
			return false
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
			alert("A valid e-mail address is required.")
			return false
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
			alert("A valid e-mail address is required.")
			return false
		 }
		
		 if (str.indexOf(" ")!=-1){
			alert("A valid e-mail address is required.")
			return false
		 }

		 return true					
	}



// LOGIN FORM

	// [server side validation on /common/framework/login.aspx.vb]



// PASSWORD RETRIEVAL

	function validateRetrievePassword(theForm) {
		// Check for zero length, empty and null values
		if(theForm.email.value.length == 0 || theForm.email.value == "" || theForm.email.value == null) {
			alert("A valid e-mail address is required to retrieve your password.");

			theForm.email.focus();

			return false
		}

		// Check for spaces
		var trimmedValue = theForm.email.value.replace(/^\s*|\s*$/g,"");
		
		if(trimmedValue.length == 0) {
			alert("A valid e-mail address is required to retrieve your password. Make sure there are no spaces before or after.");
			
			theForm.email.focus();

			return false
		}

		return true
	}



// MY ACCOUNT & CHECKOUT - BILLING & SHIPPING

	function validateAddress(theForm) {
		if(stringCheck(theForm.FirstName.value) < 0) {
			alert("Please enter your first name.");

			return false
		}

		if(stringCheck(theForm.LastName.value) < 0) {
			alert("Please enter your last name.");

			return false
		}

		if(stringCheck(theForm.Address1.value) < 0) {
			alert("Please supply a valid address.");

			return false
		}

		if(stringCheck(theForm.City.value) < 0) {
			alert("Please enter your city.");

			return false
		}

		if(stringCheck(theForm.Province.value) < 0) {
			alert("Please select a valid state/province or select 'N/A' and type it in 'Address2'.");

			return false
		}

		if(stringCheck(theForm.PostalCode.value) < 0) {
			alert("A valid postal code is required.");

			return false
		}

		if(stringCheck(theForm.Phone.value) < 0) {
			alert("Please supply a valid phone number.");

			return false
		}
		
		return true
	}

	function stringCheck(theText) {
		var trimmedValue = theText.replace(/^\s*|\s*$/g,"");
		var score

		if(trimmedValue.length == 0) {
			score = -10
		} else { 
			score = 10
		}

		if(theText.length == 0 || theText == null) {
			score = -10
		} else {
			score = 10
		}

		return score
	}



// MY ACCOUNT - EMAIL CHANGE

	function validateEmailChange(theForm) {
		// Check for zero length, empty and null values
		if(theForm.username.value.length == 0 || theForm.username.value == "" || theForm.username.value == null) {
			alert("Old e-mail address field is blank.");

			theForm.username.focus();

			return false
		}

		// Check for zero length, empty and null values
		if(theForm.password.value.length == 0 || theForm.password.value == "" || theForm.password.value == null) {
			alert("Verify Password field is blank.");

			theForm.password.focus();

			return false
		}

		// Check for zero length, empty and null values
		if(theForm.newUsername.value.length == 0 || theForm.newUsername.value == "" || theForm.newUsername.value == null) {
			alert("New e-mail address field is blank.");

			theForm.newUsername.focus();

			return false
		}

		return true
	}



// MY ACCOUNT - PASSWORD CHANGE

	function validateChangePassword(theForm) {
		// Check for zero length, empty and null values
		if(theForm.oldPassword.value.length == 0 || theForm.oldPassword.value == "" || theForm.oldPassword.value == null) {
			alert("Old Password field is blank.");

			theForm.oldPassword.focus();

			return false
		}

		// Check for zero length, empty and null values
		if(theForm.newPassword.value.length == 0 || theForm.newPassword.value == "" || theForm.newPassword.value == null) {
			alert("New Password field is blank.");

			theForm.newPassword.focus();

			return false
		}

		// Check for zero length, empty and null values
		if(theForm.newPasswordAgain.value.length == 0 || theForm.newPasswordAgain.value == "" || theForm.newPasswordAgain.value == null) {
			alert("Verify New Password field is blank.");

			theForm.newPasswordAgain.focus();

			return false
		}

		return true
	}



// WISH LISTS

	// EMAIL TO FRIEND

		function validateEmailList(theForm) {
			// Check for zero length, empty and null values
			if(theForm.mailTo.value.length == 0 || theForm.mailTo.value == "" || theForm.mailTo.value == "[Valid Email Address]" || theForm.mailTo.value == null) {
				alert("A valid email address is required.");

				theForm.mailTo.focus();

				return false
			}

			return true
		}

	// RENAME

		function validateRenameList(theForm) {
			// Check for zero length, empty and null values
			if(theForm.newName.value.length == 0 || theForm.newName.value == "" || theForm.newName.value == null) {
				alert("Rename Wish List field is blank.");

				theForm.newName.focus();

				return false
			}

			return true
		}



// SHIPMETH

	// SELECT BOX

		function validateShipMeth(theForm) {
			// Check for zero length, empty and null values
			if(theForm.ShipMethID.value.length == 0 || theForm.ShipMethID.value == "" || theForm.ShipMethID.value == null) {
				alert("Please select a valid shipping method.");

				theForm.ShipMethID.focus();

				return false
			}

			return true
		}

	// LOVEBOMB

		function showIt(theID) {
			if(document.getElementById) {
				var theItem = document.getElementById(theID)

				theItem.style.display = "block";
			}
		}

		function hideIt(theID) {
			if(document.getElementById) {
				var theItem = document.getElementById(theID);

				theItem.style.display = "none";
			}
		}

	// CLOCK

		var timerID = null;
		var timerRunning = false;
		var id,pause=0,position=0;

		function stopclock (){
				if(timerRunning)
						clearTimeout(timerID);
				timerRunning = false;
		}

		function showtime () {
				var now = new Date();
				var hours = now.getHours();
				var minutes = now.getMinutes();
				var seconds = now.getSeconds()
				var timeValue = "" + ((hours >12) ? hours -12 :hours)
				timeValue += ((minutes < 10) ? ":0" : ":") + minutes
				timeValue += ((seconds < 10) ? ":0" : ":") + seconds
				timeValue += (hours >= 12) ? " P.M." : " A.M."
				document.getElementById("clockdisplay").innerHTML = timeValue;
				timerID = setTimeout("showtime()",1000);
				timerRunning = true;
		}
		function startclock () {
				stopclock();
				showtime();
		}



// PAYMETH

	// CREDIT CARD [server side validation on paymeth.aspx.vb]



// FEEDBACK

	function validateFeedback(theForm) {
		// Check for zero length, empty and null values
		if(theForm.comments.value.length == 0 || theForm.comments.value == "" || theForm.comments.value == null) {
			alert("Feedback field is blank.");

			theForm.comments.focus();

			return false
		}

		return true
	}

