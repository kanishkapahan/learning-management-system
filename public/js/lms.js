(function ($) {
    function initDataTables() {
        $(".js-datatable").each(function () {
            if (!$.fn.DataTable.isDataTable(this)) {
                $(this).DataTable({ pageLength: 10, order: [] });
            }
        });
    }

    function initSelect2() {
        $(".js-select2").select2({ width: "100%" });
    }

    function initCharts() {
        if (!window.Chart || !window.lmsCharts) return;
        const charts = window.lmsCharts;
        const make = (id, labels, data, type) => {
            const el = document.getElementById(id);
            if (!el) return;
            new Chart(el, {
                type: type || "bar",
                data: {
                    labels,
                    datasets: [
                        {
                            label: id,
                            data,
                            backgroundColor: [
                                "#0ea5e9",
                                "#22c55e",
                                "#f59e0b",
                                "#ef4444",
                                "#8b5cf6",
                                "#06b6d4",
                            ],
                        },
                    ],
                },
                options: { responsive: true, maintainAspectRatio: false },
            });
        };
        make(
            "chartMonthlyEnrollments",
            charts.monthlyEnrollments.map((x) => x.month),
            charts.monthlyEnrollments.map((x) => x.total),
            "line",
        );
        make(
            "chartAvgMarks",
            charts.averageMarksPerCourse.map((x) => x.label),
            charts.averageMarksPerCourse.map((x) => x.value),
            "bar",
        );
        make(
            "chartPassRate",
            charts.passRatePerBatch.map((x) => x.label),
            charts.passRatePerBatch.map((x) => x.value),
            "doughnut",
        );
    }

    function initConfirmActions() {
        // Confirmation is handled by the Bootstrap modal in app.blade.php
        // This function is kept as a no-op for backwards compatibility
    }

    function initLiveValidation() {
        $(document).on("input", ".js-live-email", function () {
            const ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value);
            this.classList.toggle("is-invalid", this.value.length > 0 && !ok);
            this.classList.toggle("is-valid", this.value.length > 0 && ok);
        });

        $(document).on(
            "input",
            ".js-result-marks, .js-pass-marks",
            function () {
                const total = parseFloat(
                    $('input[name="total_marks"]').val() ||
                        $('select[name="exam_id"] option:selected').data(
                            "total",
                        ) ||
                        0,
                );
                const value = parseFloat(this.value || 0);
                if (total && value > total) this.value = total;
            },
        );
    }

    function initLoadingForms() {
        $(document).on("submit", ".js-loading-form", function () {
            const btn = $(this)
                .find('button[type="submit"], button:not([type])')
                .first();
            if (btn.length) {
                btn.data("original-text", btn.html());
                btn.prop("disabled", true).html(
                    '<span class="spinner-border spinner-border-sm me-1"></span>Processing...',
                );
            }
        });
    }

    function initSidebar() {
        $("#openSidebar").on("click", () => $("#sidebar").addClass("show"));
        $("#closeSidebar").on("click", () => $("#sidebar").removeClass("show"));
    }

    function initGlobalSearch() {
        let timer = null;
        $("#global-search-input").on("input", function () {
            clearTimeout(timer);
            const q = this.value.trim();
            if (!q) return;
            timer = setTimeout(() => {
                $.get("/search", { q }).done((res) => {
                    const total =
                        (res.students?.length || 0) +
                        (res.courses?.length || 0) +
                        (res.batches?.length || 0);
                    if (total) {
                        $("#actionToast .toast-body").text(
                            `Search found ${total} quick matches.`,
                        );
                        bootstrap.Toast.getOrCreateInstance(
                            document.getElementById("actionToast"),
                        ).show();
                    }
                });
            }, 350);
        });
    }

    function initAttendancePanelSwitch() {
        $("#attendance-batch").on("change", function () {
            $(".attendance-batch-panel").hide();
            $(
                '.attendance-batch-panel[data-batch="' + this.value + '"]',
            ).show();
        });
    }

    $(function () {
        initDataTables();
        initSelect2();
        initCharts();
        initConfirmActions();
        initLiveValidation();
        initLoadingForms();
        initSidebar();
        initGlobalSearch();
        initAttendancePanelSwitch();
    });
})(jQuery);
