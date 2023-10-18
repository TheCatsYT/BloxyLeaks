var browserInfo = "Unknown";

if (navigator.userAgent.indexOf("Chrome") !== -1) {
    browserInfo = "Google Chrome";
} else if (navigator.userAgent.indexOf("Firefox") !== -1) {
    browserInfo = "Mozilla Firefox";
} else if (navigator.userAgent.indexOf("Safari") !== -1) {
    browserInfo = "Apple Safari";
} else if (navigator.userAgent.indexOf("Edge") !== -1) {
    browserInfo = "Microsoft Edge";
} else if (navigator.userAgent.indexOf("MSIE") !== -1 || !!document.documentMode == true) {
    browserInfo = "Internet Explorer";
}

console.log("User's browser: " + browserInfo);


function isBlockedIP(ip) {
  // Replace this with the actual URL of your blocked_ips.txt file
  var blockedIPsURL = 'blocked_ips.html';

  // Fetch the blocked IP list
  return fetch(blockedIPsURL)
      .then(response => response.text())
      .then(text => {
          var blockedIPs = text.split('\n');
          return blockedIPs.includes(ip);
      })
      .catch(error => {
          console.error("Error fetching blocked IPs: " + error);
          return false;
      });
    }

    // Function to log the user's IP address and check if it's blocked
function logAndCheckUserIP() {
  fetch('https://api.ipify.org/?format=json')
      .then(response => response.json())
      .then(data => {
          var userIP = data.ip;

          isBlockedIP(userIP)
              .then(isBlocked => {
                  if (isBlocked) {
                      window.location.href = "blocked.html";
                  } else {
                      return
                  }
              });
      })
      .catch(error => console.error("Error fetching IP: " + error));
}

// Call the function to log and check the user's IP when the page loads
logAndCheckUserIP();

function showPage(pageId) {
// Hide all pages
document.querySelectorAll(".form-page").forEach(function(page) {
  page.style.display = "none";
});

// Show the selected page
document.getElementById(pageId).style.display = "block";
}

function submitForm() {
// Ensure all form elements are included in the submission
var form = document.getElementById("application-form");
form.submit();
}

console.log(`
   _______      _________      _____       ______     _
  / _____ \    |____ ____|    / ___ \     | ____ \   | |
 / /     \_\       | |       / /   \ \    | |   \ \  | |
 | |               | |      / /     \ \   | |   | |  | |
 \ \______         | |      | |     | |   | |___/ /  | |
  \______ \        | |      | |     | |   |  ____/   | |
         \ \       | |      | |     | |   | |        | |
  _      | |       | |      \ \     / /   | |        |_|
 \ \_____/ /       | |       \ \___/ /    | |         _
  \_______/        |_|        \_____/     |_|        |_|

  Keep your account safe! Do not send any information from
  here to anyone or paste any text here.

  If someone is asking you to copy or paste text here then
  you may be giving someone access to your account, your IP, your cookies.`);