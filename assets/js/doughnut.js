
new Chart(document.getElementById("doughnut"), {
    "type": "doughnut",
    "data": {
        "labels": ["P1", "P2", "P3"],
        "datasets": [{
            "label": "Issues",
            "data": [300, 50, 100],
            "backgroundColor": ["rgb(255,132,154)", "rgb(28,162,234)", "rgba(52,185,150,0.7)"]
        }]
    },
    "options": {
        "responsive": true
    }
});
