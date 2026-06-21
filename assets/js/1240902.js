// ===============================
// DADOS DA DASHBOARD
// ===============================

const dadosDashboard = window.dashboardDados || {};

const dadosCriticidade = dadosDashboard.criticidade || {
    labels: [],
    valores: []
};

const dadosSuporteVida = dadosDashboard.suporteVida || {
    labels: [],
    valores: []
};

function temDados(valores) {
    return Array.isArray(valores) && valores.some(valor => Number(valor) > 0);
}

// ===============================
// DISTRIBUIÇÃO POR CRITICIDADE
// ===============================

const ctxCriticidade = document.getElementById('graficoCriticidade');

if (ctxCriticidade) {
    new Chart(ctxCriticidade, {
        type: 'doughnut',
        data: {
            labels: dadosCriticidade.labels.length > 0 ? dadosCriticidade.labels : ['Sem dados'],
            datasets: [{
                data: temDados(dadosCriticidade.valores) ? dadosCriticidade.valores : [0],
                backgroundColor: [
                    '#28a745',
                    '#ffc107',
                    '#fd7e14',
                    '#dc3545',
                    '#0dcaf0',
                    '#6c757d'
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
}

// ===============================
// SUPORTE DE VIDA POR SERVIÇO
// ===============================

const ctxSuporteVida = document.getElementById('graficoSuporteVida');

if (ctxSuporteVida) {
    new Chart(ctxSuporteVida, {
        type: 'bar',
        data: {
            labels: dadosSuporteVida.labels.length > 0 ? dadosSuporteVida.labels : ['Sem dados'],
            datasets: [{
                label: 'Equipamentos',
                data: temDados(dadosSuporteVida.valores) ? dadosSuporteVida.valores : [0],
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
                        precision: 0
                    }
                }
            }
        }
    });
}