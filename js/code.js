// code.js

/* Test this locally with python -m http.server 3000 */

const urlBase = 'http://cop4331-pal.com/'; // change this to server address
const extension = '.php';

let userId = 0;
let firstName = "";
let lastName = "";
const ids = [];

/* event listeners when the page loads */
// login page
document.addEventListener("DOMContentLoaded", function() {
  const loginForm = document.getElementById("login");
  if (loginForm) {
    loginForm.addEventListener("submit", function(e) {
      e.preventDefault();
      doLogin();
    });
  }

  // signup page
  const signupForm = document.getElementById("signup");
  if (signupForm) {
    signupForm.addEventListener("submit", function(e) {
      e.preventDefault();
      doSignup();
    });
  }

  // addcontact page
  const addContactForm = document.getElementById("add-contact");
  if (addContactForm) {
    addContactForm.addEventListener("submit", function(e) {
      e.preventDefault();
      addContact();
    });
  }
});

// event listener for non-form buttons
document.addEventListener("click", (event) => {
  if (event.target.name === 'modify_button') {

    contactRecordID = event.target.id;
    console.log("button: " + contactRecordID); 

    let queryString = new URLSearchParams({
      recordID: contactRecordID
    }).toString();
    
    // go to selected_contacts.html
    window.location.href = `selected_contact.html?${queryString}`;
  }
});


// Function to handle user login
function doLogin() {
  userId = 0;
  firstName = "";
  lastName = "";
  
  let loginName = document.getElementById("loginName").value;
  let loginPassword = document.getElementById("loginPassword").value;

  document.getElementById("loginResult").innerHTML = "";

  let tmp = { login: loginName, password: loginPassword };
  
  let jsonPayload = JSON.stringify( tmp );
  let url = urlBase + 'api/login' + extension;
  
  // console.log("Login payload:", jsonPayload);
  // console.log("URL:", url);
  
  /* Uncomment this when php is ready */
  let xhr = new XMLHttpRequest();
  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

  console.log(xhr.responseText);
  
  try {
		xhr.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				let jsonObject = JSON.parse(xhr.responseText);
				userId = jsonObject.id;

				if( userId < 1 ) {
					document.getElementById("loginResult").innerHTML = "User/Password combination incorrect";
					console.log("Login failed: " + loginName + " " + loginPassword);
					return;
				}
		
				firstName = jsonObject.firstName;
				lastName = jsonObject.lastName;

        console.log("Login successful: " + firstName + " " + lastName);

				saveCookie();
	
				window.location.href = "contacts.html";
			}
		};
    console.log("Sending:", jsonPayload);
		xhr.send(jsonPayload);
	}
	catch(err) {
		document.getElementById("loginResult").innerHTML = err.message;
	}
}

// Function to handle user signup
function doSignup() {
  firstName = document.getElementById("firstName").value;
  lastName = document.getElementById("lastName").value;
  let email = document.getElementById("email").value; // add this for now, not clear if needed
  let username = document.getElementById("username").value;
  let password = document.getElementById("password").value;

  document.getElementById("signupResult").innerHTML = "";

  let tmp = { firstName: firstName, lastName: lastName, login: username, password: password };

  let jsonPayload = JSON.stringify(tmp);
  let url = urlBase + 'api/signup' + extension;

  console.log("Signup payload:", jsonPayload);
  console.log("URL:", url);
  
  /* Uncomment this when php is ready */
  let xhr = new XMLHttpRequest();
  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
  
  try {
		xhr.onreadystatechange = function() {
			if (this.readyState != 4) {
        return
      }

      if (this.status == 200) {
        let jsonObject = JSON.parse(xhr.responseText);
        
        // check if error in response
        if (jsonObject.error && jsonObject.error !== "") {
          document.getElementById("signupResult").innerHTML = jsonObject.error;
          console.log("Signup failed: " + jsonObject.error);
          return;
        }
        
        userId = jsonObject.id;
        document.getElementById("signupResult").innerHTML = "User added successfully!";
        // firstName = jsonObject.firstName;
        // lastName = jsonObject.lastName;
        console.log("Signup successful: " + firstName + " " + lastName);
        // saveCookie();
        // window.location.href = "contacts.html";
      }
      else if (this.status == 400) {
        let jsonObject = JSON.parse(xhr.responseText);
        document.getElementById("signupResult").innerHTML = jsonObject.Error || "Invalid input";
        console.log("Signup failed: " + this.status);
      }
      else {
        document.getElementById("signupResult").innerHTML = "An error occurred";
        console.log("Signup error: " + this.status);
      }
		};
		xhr.send(jsonPayload);
    
    } catch(err) {
      document.getElementById("signupResult").innerHTML = err.message;
    }
}

// Function to add a contact to user's account
function addContact() {
  let newContactFirstName = document.getElementById("firstname").value;
  let newContactLastName = document.getElementById("lastname").value;
  let newContactEmail = document.getElementById("email").value;
  let newContactPhone = document.getElementById("phone").value;

  document.getElementById("addContactResult").innerHTML = "";

  let tmp = {firstName:newContactFirstName, lastName:newContactLastName, email:newContactEmail, phone:newContactPhone, userID:userId};
  let jsonPayload = JSON.stringify(tmp);

  let url = urlBase + 'api/addcontact' + extension;

  let xhr = new XMLHttpRequest();
  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

  try {
    xhr.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("addContactResult").innerHTML = "Contact has been added"
      } else if (this.status == 400) {
        let jsonObject = JSON.parse(xhr.responseText);
        document.getElementById("addContactResult").innerHTML = jsonObject.Error || "Invalid input";
        console.log("Add Contact failed: " + this.status);
      } else {
        let jsonObject = JSON.parse(xhr.responseText);
        document.getElementById("addContactResult").innerHTML = jsonObject.Error || "Invalid input";
        console.log("Add Contact failed: " + this.status);
      }
    };
    xhr.send(jsonPayload);
  } catch(err) {
    document.getElementById("addContactResult").innerHTML = err.message;
  }
}

// Function to handle user logout
function doLogout() {
  userId = 0;
  firstName = "";
  lastName = "";

  document.cookie = "firstName= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
  window.location.href = "index.html";
}

// Function to save user info in a cookie
function saveCookie() {
	let minutes = 20;
	let date = new Date();
	date.setTime(date.getTime()+(minutes*60*1000));	
	document.cookie = "firstName=" + firstName + ",lastName=" + lastName + ",userId=" + userId + ";expires=" + date.toGMTString();
}

// Function to read user cookies
function readCookie() {
  userId = -1;
  let data = document.cookie;
  let splits = data.split(",");

  for (var i = 0; i < splits.length; i++) {
    let thisOne = splits[i].trim();
    let tokens = thisOne.split("=");

    if (tokens[0] == "firstName") {
      firstName = tokens[1];
    } else if (tokens[0] == "lastName") {
      lastName = tokens[1];
    } else if (tokens[0] == "userId") {
      userId = parseInt(tokens[1].trim());
    }
  }

  if (userId < 0) {
    window.location.href = "index.html";
  }
}

// Function to display the entire contacts table
function displayContactsTable() 
{
  // clear contacts table upon opening page
  document.getElementById("user_contacts_table").innerHTML = "";

  // json being sent out with the http request
  let strObj = { UserID: userId };
  let jsonPayload = JSON.stringify(strObj);
  
  // post http request
  let url = urlBase + 'api/seecontacts' + extension;
  let xhr = new XMLHttpRequest();
  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
  
  try {

    xhr.onreadystatechange = function() {

      if (this.readyState == 4 && this.status == 200) {
        
        // parse json response from api
        let jsonObjArr = JSON.parse(xhr.responseText);
        let numContacts = jsonObjArr.length;
        let strHTML = "";

        // contact list header
        strHTML = "<h3>CONTACT LIST:</h3>";

        if (numContacts == 0) { // enter if the contacts table is empty

          document.getElementById("user_contacts_table").innerHTML = "<p>You currently have no contacts listed!</p>";
          return;
        }
        else { // enter if the contacts table has >=1 contacts

          // number of columns in the contacts table (does not include 'ID' and 'UserID' sent from api)
          let recordCol = Object.keys(jsonObjArr[0]).length;
          let col = recordCol - 1;

          // header labels for the contacts table
          let headerLabelsArr = ["First Name", "Last Name", "Email", "Phone Number", "Update or Delete"];

          // object (represents row of contacts table) key for database record
          let keyArr = Object.keys(jsonObjArr[0]);

          // start table
          strHTML += '<table id="user_contacts_table">';

          // table header
          for (let i = 0; i < col; i++) { // loop thru table columns

            strHTML += '<th>' + headerLabelsArr[i] + '</th>';
          }          

          for (let i = 0; i < numContacts; i++) {

            // start table row
            strHTML += '<tr>';

            for (let j = 2; j < recordCol; j++) { // loop thru table columns

              strHTML += '<td>' + jsonObjArr[i][keyArr[j]] + '</td>';
            }

            // add button into last column of each row
            strHTML += '<td>' + '<button id="' + jsonObjArr[i][keyArr[0]] + '" name="modify_button" type="button" class="btn btn-primary" onclick="window.location.href=\'selected_contact.html?id=' + jsonObjArr[i][keyArr[0]] + '\'">Primary</button>' + '</td>';

            // end table row
            strHTML += '</tr>'
          }

          // end table
          strHTML += '</table>';
        } // end else

        // set markup for contacts table
        document.getElementById("user_contacts_table").innerHTML = strHTML;

      } // end onreadystatechange function
    }; // end try block

    // send http request to api
    xhr.send(jsonPayload);
  } // end try block
  catch (err) {

    document.getElementById("user_contacts_table").innerHTML = err.message;
  } 

} // end function displayContactsTable


// Function to display the selected contact
function displaySelectedContactTable()
{
  console.log("userId = " + userId);

  // clear contacts table upon opening page
  document.getElementById("selected_contact_table").innerHTML = "";

  let searchParams = new URLSearchParams(window.location.search);
  let contactRecordID = searchParams.get('recordID');
  console.log("contactRecordID = " + contactRecordID);

  // json being sent out with the http request
  let strObj = { UserID: userId };
  let jsonPayload = JSON.stringify(strObj);
  
  // post http request
  let url = urlBase + 'api/seecontacts' + extension;
  let xhr = new XMLHttpRequest();
  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
  
  try {

    xhr.onreadystatechange = function() {

      if (this.readyState == 4 && this.status == 200) {
        
        // parse json response from api
        let jsonObjArr = JSON.parse(xhr.responseText);
        let numContacts = jsonObjArr.length;
        let strHTML = "";

        // contact list header
        strHTML = "<h3>SELECTED CONTACT:</h3>";

        if (numContacts == 0) { // enter if the contacts table is empty

          document.getElementById("selected_contact_table").innerHTML = "<p>There appears to be an error.</p>";
          return;
        }
        else { // enter if the contacts table has >=1 contacts

          // number of columns in the contacts table (does not include 'ID' and 'UserID' sent from api)
          let recordCol = Object.keys(jsonObjArr[0]).length;
          let col = recordCol - 2;

          // header labels for the contacts table
          let headerLabelsArr = ["First Name", "Last Name", "Email", "Phone Number"];

          // object (represents row of contacts table) key for database record
          let keyArr = Object.keys(jsonObjArr[0]);

          // start table
          strHTML += '<table id="selected_contact_table">';

          // table header
          for (let i = 0; i < col; i++) { // loop thru table columns

            strHTML += '<th>' + headerLabelsArr[i] + '</th>';
          } 

          // start table row
          strHTML += '<tr id="selected_contact_row">';         

          // find the recordID
          for (let i = 0; i < numContacts; i++) {

            if (jsonObjArr[i][keyArr[0]] == contactRecordID) {

              let firstName = jsonObjArr[i][keyArr[2]];
              let lastName = jsonObjArr[i][keyArr[3]];
              let email = jsonObjArr[i][keyArr[4]];
              let phone = jsonObjArr[i][keyArr[5]];

              strHTML += '<td>' + firstName + '</td>' + '<td>' + lastName + '</td>' + '<td>' + email + '</td>' + '<td>' + phone + '</td>';
              
              break;
            }
          }

          // end table row
          strHTML += '</tr>'

          // end table
          strHTML += '</table>';
        } // end else

        // set markup for contacts table
        document.getElementById("selected_contact_table").innerHTML = strHTML;

      } // end onreadystatechange function
    }; // end try block

    // send http request to api
    xhr.send(jsonPayload);
  } // end try block
  catch (err) {

    document.getElementById("selected_contact_table").innerHTML = err.message;
  } 
        
} // end function displaySelectedContactTable




