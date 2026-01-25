// code.js

/* Test this locally with python -m http.server 3000 */

const urlBase = 'http://localhost:3000/'; // change this to server address
const extension = '.php';

let userId = 0;
let firstName = "";
let lastName = "";

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
  let url = urlBase + '/Login' + extension;
  
  console.log("Login payload:", jsonPayload);
  console.log("URL:", url);
  
  /* Uncomment this when php is ready */
//   let xhr = new XMLHttpRequest();
//   xhr.open("POST", url, true);
//   xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
//   
//   try {
// 		xhr.onreadystatechange = function() {
// 			if (this.readyState == 4 && this.status == 200) {
// 				let jsonObject = JSON.parse( xhr.responseText );
// 				userId = jsonObject.id;
// 
// 				if( userId < 1 ) {
// 					document.getElementById("loginResult").innerHTML = "User/Password combination incorrect";
// 					console.log("Login failed: " + loginName + " " + loginPassword);
// 					return;
// 				}
// 		
// 				firstName = jsonObject.firstName;
// 				lastName = jsonObject.lastName;
// 
//         console.log("Login successful: " + firstName + " " + lastName);
// 
// 				saveCookie();
// 	
// 				window.location.href = "index.html";
// 			}
// 		};
// 		xhr.send(jsonPayload);
// 	}
// 	catch(err) {
// 		document.getElementById("loginResult").innerHTML = err.message;
// 	}
}

// Function to handle user signup
function doSignup() {
  firstName = document.getElementById("firstName").value;
  lastName = document.getElementById("lastName").value;

  let username = document.getElementById("username").value;
  let password = document.getElementById("password").value;

  document.getElementById("signupResult").innerHTML = "";

  let tmp = { firstName: firstName, lastName: lastName, login: username, password: password };

  let jsonPayload = JSON.stringify(tmp);
  let url = urlBase + '/Signup' + extension;

  console.log("Signup payload:", jsonPayload);
  console.log("URL:", url);
  
  /* Uncomment this when php is ready */
//   let xhr = new XMLHttpRequest();
//   xhr.open("POST", url, true);
//   xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
//   
//   try {
// 		xhr.onreadystatechange = function() {
// 			if (this.readState != 4) {
//         return
//       }
// 
// 			if( this.status = 409 ) {
// 			  document.getElementById("signupResult").innerHTML = "User already exists";
// 				 console.log("Signup failed: " + username + " " + password);
// 				return;
// 			}
// 		
//       console.log("Signup successful: " + firstName + " " + lastName);
// 
// 
//       if (this.status == 200) {
//         let jsonObject = JSON.parse(xhr.responseText);
//         userId = jsonObject.id;
//         document.getElementById("signupResult").innerHTML = "User added";
//         firstName = jsonObject.firstName;
//         lastName = jsonObject.lastName;
//         saveCookie();
//       }
// 		};
// 		xhr.send(jsonPayload);
//     
//     } catch(err) {
//       document.getElementById("signupResult").innerHTML = err.message;
//     }
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