
new Chart(document.getElementById("doughnut"), {
    "type": "doughnut",
    "data": {
        "labels": ["Electrónica", "Láser", "Diseño en computadora"],
        "datasets": [{
            "label": "Issues",
            "data": [300, 50, 100],
            "backgroundColor": ["rgb(255,112,140)", "rgb(49,186,255)", "rgba(25,185,143,0.7)"]
        }]
    },
    "options": {
        "responsive": true
    }
});
