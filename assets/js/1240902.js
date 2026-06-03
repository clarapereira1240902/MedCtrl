// ===============================
// DISTRIBUIÇÃO POR CRITICIDADE
// ===============================

const ctxCriticidade = document.getElementById('graficoCriticidade');

new Chart(ctxCriticidade, {
    type: 'doughnut',
    data: {
        labels: [
            'Baixa',
            'Média',
            'Alta',
            'Suporte de Vida'
        ],
        datasets: [{
            data: [34, 48, 31, 15],
            backgroundColor: [
                '#28a745',
                '#ffc107',
                '#fd7e14',
                '#dc3545'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,

        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    font: {
                        size: 14
                    }
                }
            }
        }
    }
});


// ===============================
// SUPORTE DE VIDA POR SERVIÇO
// ===============================

const ctxSuporteVida = document.getElementById('graficoSuporteVida');

new Chart(ctxSuporteVida, {
    type: 'bar',
    data: {
        labels: [
            'UCI',
            'Urgência',
            'Bloco Op.',
            'Cardiologia',
            'Neonat.'
        ],
        datasets: [{
            label: 'Equipamentos',
            data: [12, 8, 6, 4, 2],
            backgroundColor: '#006390',
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,

        plugins: {
            legend: {
                display: false
            }
        },

        scales: {

            x: {
                grid: {
                    display: false
                },

                ticks: {
                    maxRotation: 0,
                    minRotation: 0,
                    font: {
                        size: 13
                    }
                }
            },

            y: {
                beginAtZero: true,

                ticks: {
                    stepSize: 2
                }
            }
        }
    }
});