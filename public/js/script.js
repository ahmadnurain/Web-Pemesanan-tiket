const navbar = document.getElementById("navbar");
const menuBtn = document.getElementById("menu-btn");
const menuIcon = document.getElementById("menu-icon");
const mobileMenu = document.getElementById("mobile-menu");
const borderButtons = document.querySelectorAll(".border-btn");
const bgButtons = document.querySelectorAll(".bg-btn");

// Hamburger menu toggle
menuBtn.addEventListener("click", () => {
    menuIcon.classList.toggle("open");
    mobileMenu.classList.toggle("hidden");
});

// Scroll effect
window.addEventListener("scroll", () => {
    if (window.scrollY > 50) {
        navbar.classList.add("bg-white", "shadow-lg", "text-green-500");
        navbar.classList.remove("bg-transparent", "text-white");

        // Update buttons on scroll
        borderButtons.forEach((button) => {
            button.classList.add("border-green-500", "text-green-500");
            button.classList.remove("border-white", "text-white");
        });
        bgButtons.forEach((button) => {
            button.classList.add("bg-green-500", "text-white");
            button.classList.remove("bg-white", "text-green-500");
        });
    } else {
        navbar.classList.add("bg-transparent", "text-white");
        navbar.classList.remove("bg-white", "shadow-lg", "text-green-500");

        // Revert buttons to default
        borderButtons.forEach((button) => {
            button.classList.add("border-white", "text-white");
            button.classList.remove("border-green-500", "text-green-500");
        });
        bgButtons.forEach((button) => {
            button.classList.add("bg-white", "text-green-500");
            button.classList.remove("bg-green-500", "text-white");
        });
    }
});

$(document).ready(function () {
    $(".owl-carousel").owlCarousel({
        loop: true,
        margin: 16,
        nav: true,
        navText: [
            '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>',
            '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>',
        ],
        responsive: {
            0: { items: 1 },
            768: { items: 2 },
            1024: { items: 3 },
        },
    });
});
document.addEventListener("DOMContentLoaded", function () {
    ScrollReveal().reveal(".reveal", {
        distance: "50px",
        duration: 800,
        easing: "ease-in-out",
        origin: "bottom",
        reset: true,
    });
});
document.addEventListener("DOMContentLoaded", function () {
    ScrollReveal().reveal(".reveal-2", {
        distance: "50px",
        duration: 800,
        delay: 240,
        easing: "ease-in-out",
        origin: "left",
        reset: true,
    });
});
document.addEventListener("DOMContentLoaded", function () {
    ScrollReveal().reveal(".reveal-3", {
        distance: "50px",
        duration: 800,
        delay: 340,
        easing: "ease-in-out",
        origin: "bottom",
        reset: true,
    });
});
document.addEventListener("DOMContentLoaded", function () {
    ScrollReveal().reveal(".reveal-4", {
        distance: "50px",
        duration: 800,
        delay: 440,
        easing: "ease-in-out",
        origin: "bottom",
        reset: true,
    });
});
document.addEventListener("DOMContentLoaded", function () {
    ScrollReveal().reveal(".reveal-5", {
        distance: "50px",
        duration: 800,
        easing: "ease-in-out",
        origin: "top",
        reset: true,
    });
});
document.addEventListener("DOMContentLoaded", function () {
    ScrollReveal().reveal(".reveal-6", {
        distance: "50px",
        duration: 800,
        delay: 240,
        easing: "ease-in-out",
        origin: "bottom",
        reset: true,
    });
});

document.addEventListener("DOMContentLoaded", function () {
    // Konfigurasi umum untuk animasi reveal
    ScrollReveal().reveal(".reveal", {
        distance: "50px", // Jarak pergerakan elemen
        duration: 800, // Durasi animasi dalam ms
        delay: 240, // Delay animasi, semakin besar, semakin lama elemen muncul
        easing: "ease-in-out", // Jenis easing animasi
        origin: "bottom", // Dari bawah
        reset: true, // Mengulang animasi setiap kali elemen masuk viewport
    });

    // Atur delay yang berbeda untuk setiap elemen agar animasi muncul berurutan
    ScrollReveal().reveal(".reveal-1", {
        delay: 200, // Elemen pertama muncul sedikit lebih cepat
    });
    ScrollReveal().reveal(".reveal-2", {
        delay: 400, // Elemen kedua muncul lebih lambat
    });
    ScrollReveal().reveal(".reveal-3", {
        delay: 600, // Elemen ketiga muncul paling lambat
    });
});
