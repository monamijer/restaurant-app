$(document).ready(function () {
    // Graphique CA des 7 derniers jours
    const ctxCA = document.getElementById('chartCA').getContext('2d');
    new Chart(ctxCA, {
        type: 'line',
        data: {
            labels: dataCA.map(d => new Date(d.jour).toLocaleDateString('fr-FR', { weekday: 'short', day: 'numeric' })),
            datasets: [{
                label: 'CA (BIF)',
                data: dataCA.map(d => d.ca),
                borderColor: '#b8894f',
                backgroundColor: 'rgba(184, 137, 79, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });

    // Graphique répartition par heure
    const ctxHeures = document.getElementById('chartHeures').getContext('2d');
    new Chart(ctxHeures, {
        type: 'bar',
        data: {
            labels: dataHeures.map(d => d.heure + 'h'),
            datasets: [{
                label: 'Commandes',
                data: dataHeures.map(d => d.total),
                backgroundColor: '#b8894f'
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });

    // Rafraîchissement auto des stats principales toutes les 60 secondes
    setInterval(function () {
        $.get('/admin/stats/refresh', function (data) {
            $('#stat-ca-jour').text(Number(data.ca_jour).toLocaleString('fr-FR') + ' BIF');
            $('#stat-nb-commandes').text(data.nb_commandes_jour);
            $('#stat-reservations').text(data.reservations_jour);
        }, 'json');
    }, 60000);
});