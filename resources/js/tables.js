import { DataTable } from "simple-datatables"

if (document.getElementById("search-table") && typeof DataTable !== 'undefined') {
    const dataTable = new DataTable("#search-table", {
        searchable: true,
        sortable: false
    });
}