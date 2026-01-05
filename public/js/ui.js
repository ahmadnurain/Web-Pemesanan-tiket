// Global UI helpers and initializations
(function () {
    // Safe query
    const $exists = (sel) => typeof $ !== "undefined" && $(sel).length > 0;

    // Owl Carousel init
    const initCarousel = () => {
        if (typeof $ === "undefined" || typeof $.fn.owlCarousel === "undefined")
            return;
        const $carousels = $(".owl-carousel");
        if (!$carousels.length) return;
        $carousels.owlCarousel({
            loop: true,
            margin: 16,
            nav: true,
            dots: false,
            responsive: {
                0: { items: 1 },
                768: { items: 2 },
                1024: { items: 3 },
            },
        });
    };

    // ScrollReveal init
    const initReveal = () => {
        if (typeof ScrollReveal === "undefined") return;
        try {
            ScrollReveal().reveal(".reveal", {
                distance: "40px",
                duration: 700,
                origin: "bottom",
            });
            ScrollReveal().reveal(".reveal-2", { delay: 200 });
            ScrollReveal().reveal(".reveal-3", { delay: 300 });
            ScrollReveal().reveal(".reveal-4", { delay: 400 });
            ScrollReveal().reveal(".reveal-5", { delay: 500 });
            ScrollReveal().reveal(".reveal-6", { delay: 600 });
        } catch (_) {}
    };

    // Navbar shrink on scroll
    const initNavbar = () => {
        const nav = document.querySelector('nav[role="navigation"]');
        if (!nav) return;
        const onScroll = () => {
            if (window.scrollY > 10) {
                nav.classList.add("shadow", "backdrop-blur");
            } else {
                nav.classList.remove("shadow", "backdrop-blur");
            }
        };
        window.addEventListener("scroll", onScroll, { passive: true });
        onScroll();
    };

    // Format Rupiah helper
    window.formatRupiah = (num) => {
        const n = Number(num || 0);
        return "IDR " + n.toLocaleString("id-ID");
    };

    // Init
    document.addEventListener("DOMContentLoaded", function () {
        initCarousel();
        initReveal();
        initNavbar();
    });
})();
