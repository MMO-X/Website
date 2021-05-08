$(document).ready((function () {
	$("[data-bss-tooltip]").tooltip();
})), function (e) {
	"use strict";
	e(".menu-toggle").click((function (a) {
		a.preventDefault(), e("#sidebar-wrapper").toggleClass("active"), e(".menu-toggle > .fa-bars, .menu-toggle > .fa-times").toggleClass("fa-bars fa-times"), e(this).toggleClass("active");
	})), e('a.js-scroll-trigger[href*="#"]:not([href="#"])').click((function () {
		if (location.pathname.replace(/^\//, "") == this.pathname.replace(/^\//, "") && location.hostname == this.hostname) {
			var a = e(this.hash);
			if ((a = a.length
				? a
				: e("[name=" + this.hash.slice(1) + "]")).length) {
				return e("html, body").animate({scrollTop: a.offset().top}, 1e3, "easeInOutExpo"), !1;
			}
		}
	})), e("#sidebar-wrapper .js-scroll-trigger").click((function () {
		e("#sidebar-wrapper").removeClass("active"), e(".menu-toggle").removeClass("active"), e(".menu-toggle > .fa-bars, .menu-toggle > .fa-times").toggleClass("fa-bars fa-times");
	})), e(document).scroll((function () {
		e(this).scrollTop() > 100 ? e(".scroll-to-top").fadeIn() : e(".scroll-to-top").fadeOut();
	}));
}(jQuery);
var onMapMouseleaveHandler = function (e) {
	var a = $(this);
	a.on("click", onMapClickHandler), a.off("mouseleave", onMapMouseleaveHandler), a.find("iframe").css("pointer-events", "none");
}, onMapClickHandler       = function (e) {
	var a = $(this);
	a.off("click", onMapClickHandler), a.find("iframe").css("pointer-events", "auto"), a.on("mouseleave", onMapMouseleaveHandler);
};
function sendApplication() {
	var e = document.getElementById("apply-discord-id").value,
	    a = document.getElementById("apply-username").value,
	    t = document.getElementById("apply-group").value,
	    s = document.getElementById("apply-text").value;
	if (18 == e.length) {
		if (a.length > 32) {
			alert("Username is to long!");
		} else if (a.length < 5) {
			alert("Username is to short!");
		} else if (s.length < 120) {
			alert("Text is to short!");
		} else if (s.length > 2e3) {
			alert("Text is to long!");
		} else {
			var i = new XMLHttpRequest;
			i.open("POST", "https://discord.com/api/webhooks/835908393402499084/jIfGZRX10xpKO5v2ZnNPyMSBCjYW13pEhQ6FWHSNdUHFrU9YkwPuSIxTppAD0zp_9srs"), i.setRequestHeader("Content-type", "application/json");
			var n = {
				username: "Web-Application",
				avatar_url: "https://cdn.discordapp.com/attachments/810576465632559185/811548752656859156/white_background.jpg",
				content: null,
				embeds: [
					{
						title: "Application",
						description: s,
						color: 33e4,
						fields: [
							{name: "User", value: "<@" + e + ">"}, {name: "Username", value: a},
							{name: "Rank", value: "<@&" + t + ">"},
						],
					},
				],
			};
			i.send(JSON.stringify(n)), document.getElementById("apply-form").reset(), document.getElementById("apply-form-div").style.display = "none", document.getElementById("apply-form-success").classList.remove("d-none");
		}
	} else {
		alert("Discord-Id is not valid!");
	}
}
function onLoadPage() {
	$.getJSON("http://team-mmo.xxarox.de/", (function (e) {
		document.getElementById("team-members").innerHtml;
		e.forEach((e, a) => {
			var t = a.background ?? "https://cdn.discordapp.com/attachments/810576465632559185/835798305732165652/photo-1597356820660-265957940f25.png",
			    s = a.avatar ?? "https://cdn.discordapp.com/attachments/810576465632559185/811548752656859156/white_background.jpg",
			    i = a.name ?? "n/a",
			    n = a.subtitle ?? "n/a",
			    o = a[ "front-text" ] ?? "n/a",
			    l = a[ "back-title" ] ?? "n/a",
			    c = a[ "back-text" ] ?? "n/a",
			    r = "";
			a.social.forEach((e, a) => {
				r += '<a href="' + a + '"><i class="' + e + '"></i></a>';
			}), '<div class="col-sm-6 col-md-4"><div class="card-container-imagia"><div class="card-imagia"><div class="front-imagia"><div class="cover-imagia"><img alt src="' + t + '" /></div><div class="user-imagia"><img class="img-circle" alt src="' + s + '" /></div><div class="content-imagia"><h3 class="name-imagia">' + i + '</h3><p class="subtitle-imagia">' + n + '</p><p class="text-center">' + o + '</p></div><div class="footer-imagia"><span><i class="fa fa-plus"></i> More info</span></div></div><div class="back-imagia"><div class="content-imagia content-back-imagia"><div><h3 class="text-center">' + l + '</h3><p class="text-center">' + c + '</p></div></div><div class="footer-imagia"><div class="social-imagia text-center">' + r + "</div></div></div></div></div></div>"
		})
	}))
}
$(".map").on("click", onMapClickHandler);