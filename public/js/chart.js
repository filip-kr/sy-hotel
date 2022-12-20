// Chart
$(document).ready(function () {
    $.ajax({
        url: $(location).attr('href') + '/reservationdata',
        method: 'POST',
        dataType: 'json',
        success: function (data) {
            const chart = $('#chart');
            const reservationsPerMonth = new Chart(chart, {
                type: 'bar',
                data: {
                    labels:
                            ['Siječanj', 'Veljača', 'Ožujak',
                            'Travanj', 'Svibanj', 'Lipanj',
                            'Srpanj', 'Kolovoz', 'Rujan',
                            'Listopad', 'Studeni', 'Prosinac'],
                    datasets: [{
                        label: 'Broj rezervacija',
                        data:
                            [data[01], data[02], data[03],
                            data[04], data[05], data[06],
                            data[07], data[08], data[09],
                            data[10], data[11], data[12]],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    maintainAspectRatio: false
                }
            });
        }
    });
});