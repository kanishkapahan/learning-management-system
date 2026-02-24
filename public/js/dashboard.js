/* ===================================================================
   LMS Dashboard JS – Charts, Counters, Sparklines
   =================================================================== */

(function () {
    "use strict";

    // ---- Palette ----
    const COLORS = {
        primary: "#6366f1",
        success: "#22c55e",
        warning: "#f59e0b",
        danger: "#ef4444",
        info: "#06b6d4",
        purple: "#a855f7",
        indigo: "#4f46e5",
        slate: "#64748b",
    };

    const GRADIENTS = {};

    function getGradient(ctx, color1, color2) {
        const g = ctx.createLinearGradient(0, 0, 0, ctx.canvas.height);
        g.addColorStop(0, color1);
        g.addColorStop(1, color2);
        return g;
    }

    // ---- Shared defaults ----
    const isDark = () =>
        document.documentElement.getAttribute("data-bs-theme") === "dark";
    const gridColor = () =>
        isDark() ? "rgba(255,255,255,.08)" : "rgba(0,0,0,.06)";
    const textColor = () => (isDark() ? "#94a3b8" : "#64748b");

    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.font.size = 12;
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
    Chart.defaults.plugins.legend.labels.padding = 16;
    Chart.defaults.responsive = true;
    Chart.defaults.maintainAspectRatio = false;

    // ---- Data from Blade ----
    const DATA = window.lmsDashboard || {};

    // ---- A) Monthly Enrollments (Line) ----
    function initMonthlyEnrollments() {
        const el = document.getElementById("chartMonthlyEnrollments");
        if (!el || !DATA.monthlyEnrollments) return;

        const labels = DATA.monthlyEnrollments.map((r) => r.month);
        const values = DATA.monthlyEnrollments.map((r) => Number(r.total));

        const ctx = el.getContext("2d");
        const gradient = getGradient(
            ctx,
            "rgba(99,102,241,.25)",
            "rgba(99,102,241,.01)",
        );

        new Chart(ctx, {
            type: "line",
            data: {
                labels,
                datasets: [
                    {
                        label: "Enrollments",
                        data: values,
                        borderColor: COLORS.primary,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: "#fff",
                        pointBorderColor: COLORS.primary,
                        pointBorderWidth: 2,
                        borderWidth: 2.5,
                    },
                ],
            },
            options: {
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark() ? "#1e293b" : "#fff",
                        titleColor: isDark() ? "#f1f5f9" : "#1e293b",
                        bodyColor: isDark() ? "#cbd5e1" : "#475569",
                        borderColor: isDark() ? "#334155" : "#e2e8f0",
                        borderWidth: 1,
                        cornerRadius: 8,
                        padding: 12,
                    },
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor() },
                    },
                    y: {
                        grid: { color: gridColor() },
                        ticks: { color: textColor() },
                        beginAtZero: true,
                    },
                },
            },
        });
    }

    // ---- B) Avg Marks per Course (Bar) ----
    function initAvgMarks() {
        const el = document.getElementById("chartAvgMarks");
        if (!el || !DATA.averageMarksPerCourse) return;

        const labels = DATA.averageMarksPerCourse.map((r) => r.label);
        const values = DATA.averageMarksPerCourse.map((r) => Number(r.value));
        const barColors = [
            COLORS.primary,
            COLORS.info,
            COLORS.purple,
            COLORS.success,
            COLORS.warning,
            COLORS.danger,
            COLORS.indigo,
            COLORS.slate,
        ];

        new Chart(el, {
            type: "bar",
            data: {
                labels,
                datasets: [
                    {
                        label: "Avg Marks",
                        data: values,
                        backgroundColor: labels.map(
                            (_, i) => barColors[i % barColors.length] + "20",
                        ),
                        borderColor: labels.map(
                            (_, i) => barColors[i % barColors.length],
                        ),
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                    },
                ],
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor(), maxRotation: 45 },
                    },
                    y: {
                        grid: { color: gridColor() },
                        ticks: { color: textColor() },
                        beginAtZero: true,
                    },
                },
            },
        });
    }

    // ---- C) Pass Rate per Batch (Doughnut) ----
    function initPassRate() {
        const el = document.getElementById("chartPassRate");
        if (!el || !DATA.passRatePerBatch) return;

        const labels = DATA.passRatePerBatch.map((r) => r.label);
        const values = DATA.passRatePerBatch.map((r) => Number(r.value));
        const colors = [
            COLORS.primary,
            COLORS.success,
            COLORS.warning,
            COLORS.info,
            COLORS.purple,
            COLORS.danger,
            COLORS.indigo,
        ];

        new Chart(el, {
            type: "doughnut",
            data: {
                labels,
                datasets: [
                    {
                        data: values,
                        backgroundColor: colors.slice(0, labels.length),
                        borderWidth: 0,
                        hoverOffset: 8,
                    },
                ],
            },
            options: {
                cutout: "65%",
                plugins: {
                    legend: {
                        position: "bottom",
                        labels: { padding: 12, font: { size: 11 } },
                    },
                },
            },
        });
    }

    // ---- D) Result Status (Pie) ----
    function initResultStatus() {
        const el = document.getElementById("chartResultStatus");
        if (!el || !DATA.resultStatusDistribution) return;

        const statusColors = {
            draft: COLORS.slate,
            approved: COLORS.warning,
            published: COLORS.success,
        };
        const labels = DATA.resultStatusDistribution.map(
            (r) => r.status.charAt(0).toUpperCase() + r.status.slice(1),
        );
        const values = DATA.resultStatusDistribution.map((r) =>
            Number(r.total),
        );
        const colors = DATA.resultStatusDistribution.map(
            (r) => statusColors[r.status] || COLORS.slate,
        );

        new Chart(el, {
            type: "pie",
            data: {
                labels,
                datasets: [
                    {
                        data: values,
                        backgroundColor: colors,
                        borderWidth: 0,
                        hoverOffset: 6,
                    },
                ],
            },
            options: {
                plugins: {
                    legend: {
                        position: "bottom",
                        labels: { padding: 12, font: { size: 11 } },
                    },
                },
            },
        });
    }

    // ---- Animated Counters ----
    function animateCounters() {
        document.querySelectorAll(".count-up").forEach(function (el) {
            const target = parseInt(el.getAttribute("data-target"), 10);
            if (isNaN(target)) return;
            const duration = 1200;
            const start = performance.now();
            function step(now) {
                const progress = Math.min((now - start) / duration, 1);
                const ease = 1 - Math.pow(1 - progress, 3); // ease-out cubic
                el.textContent = Math.floor(ease * target).toLocaleString();
                if (progress < 1) requestAnimationFrame(step);
                else el.textContent = target.toLocaleString();
            }
            requestAnimationFrame(step);
        });
    }

    // ---- Mini Sparklines ----
    function initSparklines() {
        document
            .querySelectorAll(".sparkline-canvas")
            .forEach(function (canvas) {
                const raw = canvas.getAttribute("data-values");
                if (!raw) return;
                const values = JSON.parse(raw);
                const color =
                    canvas.getAttribute("data-color") || COLORS.primary;

                const ctx = canvas.getContext("2d");
                const gradient = ctx.createLinearGradient(
                    0,
                    0,
                    0,
                    canvas.height,
                );
                gradient.addColorStop(0, color + "40");
                gradient.addColorStop(1, color + "00");

                new Chart(ctx, {
                    type: "line",
                    data: {
                        labels: values.map((_, i) => i),
                        datasets: [
                            {
                                data: values,
                                borderColor: color,
                                backgroundColor: gradient,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 0,
                                borderWidth: 1.5,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: { enabled: false },
                        },
                        scales: {
                            x: { display: false },
                            y: { display: false },
                        },
                        elements: { line: { borderCapStyle: "round" } },
                    },
                });
            });
    }

    // ---- Init ----
    document.addEventListener("DOMContentLoaded", function () {
        initMonthlyEnrollments();
        initAvgMarks();
        initPassRate();
        initResultStatus();
        animateCounters();
        initSparklines();
    });
})();
