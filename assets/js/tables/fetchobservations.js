let table;

    fetch('./functions.php',{
        method: "POST",
        mode: "same-origin",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({datos: {
                solicitud: "o",
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
                <div class="flex items-center space-x-4">
                    <button value="${data}" type="button" class="flex items-center justify-between px-2 py-2 text-base font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray" onclick="editar(this)"><i class="fas fa-edit"></i></i></button>
                    <button value="${data}" type="button" name="borrar" class="flex items-center justify-between px-2 py-2 text-base font-medium leading-5 text-blue-500 rounded-lg focus:outline-none focus:shadow-outline-gray" onclick="borrar(this)"><i class="fas fa-trash-alt"></i></button>
                </div>`;
            }

            table = new simpleDatatables.DataTable(".table", {
                data: {
                    headings: Object.keys(data[0]),
                    data: data.map(item => Object.values(item))
                },
                fixedHeight: true,
                scrollY: true,
                scrollX: true,
                columns: [
                    {select: 3, render: renderButton},
                ]
            });


            const selector = document.querySelector('.dataTable-selector'),
                dropdown = document.querySelector('.dataTable-dropdown'),
                input = document.querySelector('.dataTable-input'),
                trtable = document.querySelector('.dataTable-table tr'),
            bodytable = document.querySelector('.dataTable-table tbody'),
            tableContainer = document.querySelector('.dataTable-container table');


            selector.classList.add('text-sm','w-2/6' ,'p-4' ,'m-1', 'rounded-md', 'border-gray-300', 'shadow-sm' ,'focus:border-blue-300', 'focus:ring', 'focus:ring-blue-200' ,'focus:ring-opacity-50');
            dropdown.classList.add('w-1/4');
            input.classList.add("mt-1", "text-sm" ,"w-full" ,"rounded-md", "border-gray-300", "shadow-sm" ,"focus:border-blue-300" ,"focus:ring","focus:ring-blue-200","focus:ring-opacity-50")
            trtable.classList.add('text-xs','font-semibold' ,'tracking-wide' ,'text-left', 'uppercase' ,'border-b')
            bodytable.classList.add('bg-gray-100', 'divide-y');
            tableContainer.classList.add('h-full');

        });



const newObs = document.querySelector('#observacion'),
    closeModal = document.querySelectorAll('.close'),
    modal = document.querySelector('#modal'),
    guardar = document.querySelector('button[name="guardar"]'),
    description = document.querySelector('textarea[name="descripcion"]'),
    fecha= document.querySelector('input[name="fecha"]');

newObs.addEventListener('click', evt => {
    modal.classList.toggle('hidden');

    guardar.addEventListener('click', crear);

    closeModal.forEach( e =>{
        e.addEventListener('click', cerrarCrear);
    });

    function cerrarCrear(e){
        modal.classList.toggle('hidden');
        guardar.removeEventListener('click', crear);
        description.value = '';
        fecha.value = '';
        closeModal.forEach( e =>{
            e.removeEventListener('click', cerrarCrear);
        });
    }

    function crear(e) {
        modal.classList.toggle('hidden');
        guardar.removeEventListener('click', crear);
        closeModal.forEach( e =>{
            e.removeEventListener('click', cerrarCrear);
        });
        fetch('./functions.php',{
            method: "POST",
            mode: "same-origin",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({datos: {
                    solicitud: "c",
                    description: description.value,
                    date: fecha.value,
                }})
        }).then(data =>{
            location.reload();
        });
    }

});

function editar(e){
    
    fetch('./functions.php',{
        method: "POST",
        mode: "same-origin",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({datos: {
                solicitud: "obs_id",
                id: e.value,
            }})
    })
    .then(response => response.json())
    .then(data =>{
        
        description.value = data['description'];
        fecha.value = data['date'];

        modal.classList.toggle('hidden');

        guardar.addEventListener('click', actualizar);

        closeModal.forEach( e =>{
            e.addEventListener('click', cerrarActualizar);
        });

        function cerrarActualizar(e){
            modal.classList.toggle('hidden');
            guardar.removeEventListener('click', actualizar);
            description.value = '';
            fecha.value = '';
            closeModal.forEach( e =>{
                e.removeEventListener('click', cerrarActualizar);
            });
        }

        function actualizar(evt) {
            modal.classList.toggle('hidden');

            fetch('./functions.php',{
                method: "POST",
                mode: "same-origin",
                credentials: "same-origin",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({datos: {
                        solicitud: "u",
                        description: description.value,
                        date: fecha.value,
                        id: e.value
                    }})
            })
            .then(data =>{
                location.reload();
            });
        }

    });
}

function borrar(e) {
    fetch('./functions.php',{
        method: "POST",
        mode: "same-origin",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({datos: {
                solicitud: "d",
                id: e.value
            }})
    })
    .then(data =>{
      location.reload();
    });

}
