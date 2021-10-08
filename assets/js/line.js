new Chart(document.getElementById("line"), {
    "type": "bar",
    "data": {
        "labels": ["January", "February", "March", "April"],
        "datasets": [{
            "label": "Page Impressions",
            "data": [10, 20, 30, 40],
            "borderColor": "rgb(255, 99, 132)",
            "backgroundColor": "rgba(59, 130, 246, 0.2)"
        }, {
            "label": "Adsense Clicks",
            "data": [5, 15, 10, 30],
            "type": "line",
            "fill": false,
            "borderColor": "rgb(255, 99, 132)"
        }]
    },
    "options": {
        "scales": {
            "yAxes": [{
                "ticks": {
                    "beginAtZero": true
                }
            }]
        }
    }
});