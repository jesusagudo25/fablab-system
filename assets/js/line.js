new Chart(document.getElementById("line"), {
    "type": "bar",
    "data": {
        "labels": ["Enero", "Febrero", "Marzo", "Abril"],
        "datasets": [{
            "label": "Mes",
            "data": [10, 20, 30, 40],
            "borderColor": "rgb(255,132,154)",
            "backgroundColor": "rgba(59, 130, 246, 0.2)"
        }, {
            "label": "Ganancia",
            "data": [5, 15, 10, 30],
            "type": "line",
            "fill": false,
            "borderColor": "rgb(255,132,154)"
        }]
    },
    "options": {
        "responsive": true,
        "scales": {
            "yAxes": [{
                "ticks": {
                    "beginAtZero": true
                }
            }]
        }
    }
});