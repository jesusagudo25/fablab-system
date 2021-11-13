fetch('./functions.php',{
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
                <button value="${data}" type="button" name="actualizar" class="flex items-center justify-between px-2 py-2 text-base font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray" onclick="actualizar(this,${row.lastElementChild.textContent})"><i class="far fa-calendar-check"></i></button>
`;
        }

        function renderInput(data, cell, row) {
            return `
                <input type="time" id="visit${row.children[3].textContent}area${row.children[4].textContent}" name="departure_time_area" class="text-sm p-1.5 m-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">`;
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
                { select: 2, render: renderInput },
                { select: 3, render: renderButton },
                { select: 4, hidden: true },

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

function actualizar(e,h) {

    const hora = document.querySelector('#visit'+e.value+'area'+h);

    fetch('./functions.php',{
      method: "POST",
        mode: "same-origin",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({datos: {
                solicitud: "u",
                visit_id: e.value,
                area_id: h,
                departure_time: hora.value
            }})
    })
        .then(data =>{
            location.reload();
        });
}