var gestMenuDiv = document.getElementById("dropdown");
var gestMenu = document.getElementById("gestButton");

var userMenuDiv = document.getElementById("userMenu");
var userMenu = document.getElementById("userButton");

var navMenuDiv = document.getElementById("nav-content");
var navMenu = document.getElementById("nav-toggle");

document.onclick = check;

function check(e) {
    var target = (e && e.target);

    //User Menu
    if (!checkParent(target, userMenuDiv)) {
        // click NOT on the menu
        if (checkParent(target, userMenu)) {
            // click on the link
            if (userMenuDiv.classList.contains("invisible")) {
                userMenuDiv.classList.remove("invisible");
            } else { userMenuDiv.classList.add("invisible"); }
        } else {
            // click both outside link and outside menu, hide menu
            userMenuDiv.classList.add("invisible");
        }
    }

    //Nav Menu
    if (!checkParent(target, navMenuDiv)) {
        // click NOT on the menu
        if (checkParent(target, navMenu)) {
            // click on the link
            if (navMenuDiv.classList.contains("hidden")) {
                navMenuDiv.classList.remove("hidden");
            } else { navMenuDiv.classList.add("hidden"); }
        } else {
            // click both outside link and outside menu, hide menu
            navMenuDiv.classList.add("hidden");
        }
    }

    //Gestionar
    if (!checkParent(target, gestMenuDiv)) {
        // click NOT on the menu
        if (checkParent(target, gestMenu)) {
            // click on the link
            if (gestMenuDiv.classList.contains("invisible")) {
                gestMenuDiv.classList.remove("invisible");
            } else { gestMenuDiv.classList.add("invisible"); }
        } else {
            // click both outside link and outside menu, hide menu
            gestMenuDiv.classList.add("invisible");
        }
    }
}

function checkParent(t, elm) {
    while (t.parentNode) {
        if (t == elm) { return true; }
        t = t.parentNode;
    }
    return false;
}