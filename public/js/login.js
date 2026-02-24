/* ===== Login Page – Animations & Interactions ===== */
(function () {
    "use strict";

    /* ---------- Particle System ---------- */
    function initParticles() {
        var container = document.querySelector(".particles-container");
        if (!container) return;

        var PARTICLE_COUNT = 35;
        for (var i = 0; i < PARTICLE_COUNT; i++) {
            var p = document.createElement("span");
            p.className = "particle";
            var size = Math.random() * 3 + 1.5;
            p.style.width = size + "px";
            p.style.height = size + "px";
            p.style.left = Math.random() * 100 + "%";
            p.style.animationDuration = Math.random() * 10 + 8 + "s";
            p.style.animationDelay = Math.random() * 10 + "s";
            p.style.opacity = Math.random() * 0.5 + 0.15;
            container.appendChild(p);
        }
    }

    /* ---------- Count-up for stats (if any) ---------- */
    function animateCountUp(el) {
        var target = parseInt(el.getAttribute("data-target"), 10);
        if (isNaN(target)) return;
        var duration = 1200;
        var start = performance.now();
        function tick(now) {
            var progress = Math.min((now - start) / duration, 1);
            var ease = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.round(ease * target);
            if (progress < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    }

    /* ---------- Interactive tilt on glass card ---------- */
    function initTilt() {
        var card = document.querySelector(".glass-card");
        if (!card) return;

        var wrapper = document.querySelector(".login-wrapper");
        wrapper.addEventListener("mousemove", function (e) {
            var rect = wrapper.getBoundingClientRect();
            var x = (e.clientX - rect.left) / rect.width - 0.5;
            var y = (e.clientY - rect.top) / rect.height - 0.5;
            card.style.transform =
                "perspective(800px) rotateY(" +
                x * 4 +
                "deg) rotateX(" +
                -y * 4 +
                "deg)";
        });

        wrapper.addEventListener("mouseleave", function () {
            card.style.transition = "transform .5s cubic-bezier(.4,0,.2,1)";
            card.style.transform = "perspective(800px) rotateY(0) rotateX(0)";
            setTimeout(function () {
                card.style.transition = "";
            }, 500);
        });
    }

    /* ---------- Password visibility toggle ---------- */
    function initPasswordToggle() {
        var toggles = document.querySelectorAll(".password-toggle");
        toggles.forEach(function (btn) {
            btn.addEventListener("click", function () {
                var input = btn.parentElement.querySelector(".form-input");
                if (!input) return;
                var isPassword = input.type === "password";
                input.type = isPassword ? "text" : "password";
                var icon = btn.querySelector("i");
                if (icon) {
                    icon.className = isPassword
                        ? "fas fa-eye-slash"
                        : "fas fa-eye";
                }
            });
        });
    }

    /* ---------- Input floating label effect ---------- */
    function initInputEffects() {
        var inputs = document.querySelectorAll(".form-input");
        inputs.forEach(function (input) {
            /* Add 'has-value' class when input has content */
            function check() {
                input.classList.toggle("has-value", input.value.length > 0);
            }
            input.addEventListener("input", check);
            input.addEventListener("change", check);
            check(); /* initial check for autofill */
        });
    }

    /* ---------- Ripple effect on button ---------- */
    function initBtnRipple() {
        var btn = document.querySelector(".login-btn");
        if (!btn) return;

        btn.addEventListener("click", function (e) {
            var rect = btn.getBoundingClientRect();
            var ripple = document.createElement("span");
            ripple.style.cssText =
                "position:absolute;border-radius:50%;background:rgba(255,255,255,.25);" +
                "transform:scale(0);animation:rippleOut .6s ease-out forwards;pointer-events:none;" +
                "width:200px;height:200px;" +
                "left:" +
                (e.clientX - rect.left - 100) +
                "px;" +
                "top:" +
                (e.clientY - rect.top - 100) +
                "px;";
            btn.appendChild(ripple);
            setTimeout(function () {
                ripple.remove();
            }, 700);
        });

        /* inject ripple keyframes */
        if (!document.getElementById("ripple-style")) {
            var style = document.createElement("style");
            style.id = "ripple-style";
            style.textContent =
                "@keyframes rippleOut{to{transform:scale(4);opacity:0}}";
            document.head.appendChild(style);
        }
    }

    /* ---------- Mouse-follow glow on background ---------- */
    function initMouseGlow() {
        var glow = document.createElement("div");
        glow.style.cssText =
            "position:fixed;width:350px;height:350px;border-radius:50%;" +
            "background:radial-gradient(circle,rgba(99,102,241,.12) 0%,transparent 70%);" +
            "pointer-events:none;z-index:2;transform:translate(-50%,-50%);" +
            "transition:left .3s ease-out,top .3s ease-out;will-change:left,top;";
        document.body.appendChild(glow);

        document.addEventListener("mousemove", function (e) {
            glow.style.left = e.clientX + "px";
            glow.style.top = e.clientY + "px";
        });
    }

    /* ---------- Text scramble reveal for title ---------- */
    function initTextReveal() {
        var title = document.querySelector(".login-title");
        if (!title) return;

        var finalText = title.textContent;
        var chars =
            "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        var iterations = 0;
        var interval = setInterval(function () {
            title.textContent = finalText
                .split("")
                .map(function (char, index) {
                    if (index < iterations) return finalText[index];
                    if (char === " ") return " ";
                    return chars[Math.floor(Math.random() * chars.length)];
                })
                .join("");

            iterations += 1 / 2;
            if (iterations >= finalText.length) {
                title.textContent = finalText;
                clearInterval(interval);
            }
        }, 40);
    }

    /* ---------- Login form loading overlay ---------- */
    function initLoginLoading() {
        var form = document.querySelector(".glass-card form");
        var overlay = document.getElementById("loginLoadingOverlay");
        if (!form || !overlay) return;

        form.addEventListener("submit", function () {
            overlay.classList.add("active");
        });
    }

    /* ---------- Init everything on DOM ready ---------- */
    function init() {
        initParticles();
        initTilt();
        initPasswordToggle();
        initInputEffects();
        initBtnRipple();
        initMouseGlow();
        initLoginLoading();

        /* Delay text scramble until card animation completes */
        setTimeout(initTextReveal, 900);
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();
