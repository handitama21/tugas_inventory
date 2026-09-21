document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.getElementById("searchHistory");
    const table = document.getElementById("historyTable");

    if (!searchInput || !table) {
        return;
    }

    searchInput.addEventListener("input", function () {

        const keyword = this.value.toLowerCase();
        const rows = table.querySelectorAll("tbody tr");

        rows.forEach(function (row) {

            const text = row.textContent.toLowerCase();

            if (text.includes(keyword)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }

        });

    });

});