const HOST = "http://localhost/3it-test-ab/www/";

function importFetch(typ) {
    let jsonPayload = {
        'typ': typ,
    }

    fetch(HOST + '/imports/import', {
        method: 'POST',
        body: JSON.stringify(jsonPayload)
    })
        .then((response) => response.json())
        .then((data) => getStatus(data, 'Záznamy byly v pořádku importovány'))

}


function createTable() {
    let jsonPayload = {
        'typ': 'createTable',
    }

    fetch(HOST + '/migration/create', {
        method: 'POST',
        body: JSON.stringify(jsonPayload)
    })
        .then((response) => response.json())
        .then((data) => getStatus(data, 'DB tabulka byla v pořádku vytvořena'))
}


function getStatus(data, messageText) {
    let message = getStatusMessage(data.status, messageText)
    document.getElementById('statusModalText').innerHTML = message
}

function getStatusMessage(status, messageText) {
    let message = messageText ?? 'Neznáma akce'

    if (status === 'FAIL') {
        message = 'Operace se nezdařila, opakujte akci'
    }

    return message;
}

$('#statusModal').on('hidden.bs.modal', function () {
    document.getElementById('statusModalText').innerHTML = '<div class="loader"></div>'
});

function showData() {
    let jsonPayload = {
        'typ': 'showTable',
    }

    fetch(HOST + '/tableData', {
        method: 'POST',
        body: JSON.stringify(jsonPayload)
    })
        .then((response) => response.json())
        .then((data) => showTable(data))

}


function showTable(data) {

    let rows = [];

    if (data.tableData.length > 0) {

        for (var a = 0; a < data.tableData.length; a++) {
            var itemData = data.tableData[a];

            let item = createTableRow(itemData)

            rows = rows + item;
        }

        let tableHead = ' <tr>\n' +
            '            <th>ID</th>\n' +
            '            <th>Jméno</th>\n' +
            '            <th>Příjmení</th>\n' +
            '            <th>Datum</th>\n' +
            '        </tr>'


        document.getElementById('tableData').innerHTML = tableHead + rows;

    }

    else {
        document.getElementById('tableData').innerHTML = 'Nejsou data k zobrazení'
    }

    $(function() {

        /* Get all rows from your 'table' but not the first one
         * that includes headers. */
        var rows = $('tr').not(':first');

        /* Create 'click' event handler for rows */
        rows.on('click', function(e) {

            /* Get current row */
            var row = $(this);

            /* Check if 'Ctrl', 'cmd' or 'Shift' keyboard key was pressed
             * 'Ctrl' => is represented by 'e.ctrlKey' or 'e.metaKey'
             * 'Shift' => is represented by 'e.shiftKey' */
            if ((e.ctrlKey || e.metaKey) || e.shiftKey) {
                /* If pressed highlight the other row that was clicked */
                row.addClass('highlight');
            } else {
                /* Otherwise just highlight one row and clean others */
                rows.removeClass('highlight');
                row.addClass('highlight');
            }

        });

        /* This 'event' is used just to avoid that the table text
         * gets selected (just for styling).
         * For example, when pressing 'Shift' keyboard key and clicking
         * (without this 'event') the text of the 'table' will be selected.
         * You can remove it if you want, I just tested this in
         * Chrome v30.0.1599.69 */
        $(document).bind('selectstart dragstart', function(e) {
            e.preventDefault(); return false;
        });

    });
}


function createTableRow(itemData) {
    let row = '<tr>' +
    '<td>' + itemData.id + '</td>'+
    '<td>' + itemData.name + '</td>'+
    '<td>' + itemData.surname + '</td>'+
    '<td>' + itemData.date + '</td>'+
    '</tr>'


    return row;
}