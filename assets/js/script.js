jQuery(document).ready(function ($) {
    let sortOrder = 'asc';
    let sortField = 'name';

    // Function to toggle sorting order
    $('#toggleSortOrder').click(function () {
        sortOrder = sortOrder === 'asc' ? 'desc' : 'asc';
        $('#toggleSortOrder').text('Toggle Sort Order (' + (sortOrder === 'asc' ? 'Ascending' : 'Descending') + ')');
        sortEmployees();
    });

    // Function to change the sorting field
    $('th[data-sort]').click(function () {
        sortField = $(this).data('sort');
        resetSortIndicators();
        updateSortIndicator(sortField, sortOrder);
        sortEmployees();
    });

    // Function to reset all sort indicators
    function resetSortIndicators() {
        $('#nameSortIndicator, #emailSortIndicator, #ageSortIndicator, #date_of_hiringSortIndicator').text('');
    }

    // Function to update the sort indicator for the given field
    function updateSortIndicator(field, order) {
        $('#' + field + 'SortIndicator').text(order === 'asc' ? '↑' : '↓');
    }

    // Function to sort employees based on the selected field and order
    function sortEmployees() {
        const $table = $('#employee-table');
        const rows = $table.find('tbody tr').get();

        rows.sort(function (a, b) {
            const keyA = $(a).find('td:eq(' + columnIndexes[sortField] + ')').text();
            const keyB = $(b).find('td:eq(' + columnIndexes[sortField] + ')').text();
            return sortOrder === 'asc' ? keyA.localeCompare(keyB) : keyB.localeCompare(keyA);
        });

        // Reorder the table rows
        $.each(rows, function (index, row) {
            $table.find('tbody').append(row);
        });
    }

    // Initialize sorting variables
    const columnIndexes = {
        name: 0,
        email: 1,
        age: 2,
        date_of_hiring: 3
    };

    // Initially sort by the 'name' field in ascending order
    resetSortIndicators();
    updateSortIndicator(sortField, sortOrder);
    sortEmployees();
});