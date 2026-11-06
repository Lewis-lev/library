document.querySelectorAll("[data-table-filter]").forEach((input) => {
    const tableId = input.dataset.tableFilter;
    const table = document.getElementById(tableId);

    if (!table?.tBodies.length) {
        throw new Error(`Table filter target "${tableId}" was not found.`);
    }

    const rows = Array.from(table.tBodies[0].rows).filter(
        (row) => !row.hasAttribute("data-table-no-match") && !row.querySelector("td[colspan]")
    );
    const noMatchRow = table.querySelector("[data-table-no-match]");
    const count = document.querySelector(`[data-table-count="${tableId}"]`);
    const label = input.dataset.tableLabel;
    const update = () => {
        const query = input.value.trim().toLocaleLowerCase();
        let visibleRows = 0;

        rows.forEach((row) => {
            const matches = row.textContent.toLocaleLowerCase().includes(query);
            row.hidden = !matches;
            visibleRows += Number(matches);
        });

        if (noMatchRow) {
            noMatchRow.hidden = visibleRows > 0;
        }

        if (count && label) {
            count.textContent = query
                ? `Showing ${visibleRows} of ${rows.length} ${label}${rows.length === 1 ? "" : "s"}`
                : `${rows.length} ${label}${rows.length === 1 ? "" : "s"}`;
        }
    };

    input.addEventListener("input", update);
});
