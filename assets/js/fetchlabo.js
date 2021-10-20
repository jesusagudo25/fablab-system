fetch('./api.php',{
    method: "POST",
    mode: "same-origin",
    credentials: "same-origin",
    headers: {
        "Content-Type": "application/json"
    },
    body: JSON.stringify({datos: {
            solicitud: "l",
        }})
})
    .then(response => response.json())
    .then(data => {
        if (!data.length) {
            return
        }

        // Price column cell manipulation
        function renderButton(data, cell, row) {
            return `
                <button value="${data}" type="submit" name="borrar" class="flex items-center justify-between px-2 py-2 text-base font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray"><i class="far fa-calendar-check"></button>`;
        }

        function renderInput(data, cell, row) {
            return `
                <input type="time" id="departure_time_area" name="departure_time_area" class="text-sm p-1.5 m-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">`;
        }

        let table = new simpleDatatables.DataTable(".table", {
            data: {
                headings: Object.keys(data[0]),
                data: data.map(item => Object.values(item))
            },
            fixedHeight: true,
            scrollY: true,
            scrollX: true,
            columns: [
                { select: 1, render: renderInput },
                { select: 2, render: renderButton },
                { select: 3, hidden: true },

            ]
        });

        const selector = document.querySelector('.dataTable-selector'),
            dropdown = document.querySelector('.dataTable-dropdown'),
            input = document.querySelector('.dataTable-input'),
            trtable = document.querySelector('.dataTable-table tr');
        bodytable = document.querySelector('.dataTable-table tbody');
        tableContainer = document.querySelector('.dataTable-container table');

        selector.classList.add('text-sm','w-2/6' ,'p-4' ,'m-1', 'rounded-md', 'border-gray-300', 'shadow-sm' ,'focus:border-blue-300', 'focus:ring', 'focus:ring-blue-200' ,'focus:ring-opacity-50');
        dropdown.classList.add('w-1/4');
        input.classList.add("mt-1", "text-sm" ,"w-full" ,"rounded-md", "border-gray-300", "shadow-sm" ,"focus:border-blue-300" ,"focus:ring","focus:ring-blue-200","focus:ring-opacity-50")
        trtable.classList.add('text-xs','font-semibold' ,'tracking-wide' ,'text-left', 'uppercase' ,'border-b')
        bodytable.classList.add('bg-gray-100', 'divide-y');
        tableContainer.classList.add('h-full');
    });