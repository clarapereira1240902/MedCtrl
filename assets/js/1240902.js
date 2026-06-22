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

function limparCanvasSemDados(canvas, mensagem) {
    const contexto = canvas.getContext('2d');

    contexto.clearRect(0, 0, canvas.width, canvas.height);
    contexto.font = '16px Arial';
    contexto.textAlign = 'center';
    contexto.textBaseline = 'middle';
    contexto.fillStyle = '#6c757d';
    contexto.fillText(mensagem, canvas.width / 2, canvas.height / 2);
}

// ===============================
// DISTRIBUIÇÃO POR CRITICIDADE
// ===============================

const ctxCriticidade = document.getElementById('graficoCriticidade');

if (ctxCriticidade) {
    if (temDados(dadosCriticidade.valores)) {
        new Chart(ctxCriticidade, {
            type: 'doughnut',
            data: {
                labels: dadosCriticidade.labels,
                datasets: [{
                    data: dadosCriticidade.valores,
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
    } else {
        limparCanvasSemDados(ctxCriticidade, 'Sem dados de criticidade');
    }
}

// ===============================
// SUPORTE DE VIDA POR SERVIÇO
// ===============================

const ctxSuporteVida = document.getElementById('graficoSuporteVida');

if (ctxSuporteVida) {
    if (temDados(dadosSuporteVida.valores)) {
        new Chart(ctxSuporteVida, {
            type: 'bar',
            data: {
                labels: dadosSuporteVida.labels,
                datasets: [{
                    label: 'Equipamentos',
                    data: dadosSuporteVida.valores,
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
    } else {
        limparCanvasSemDados(ctxSuporteVida, 'Sem equipamentos de suporte de vida');
    }
}