document.addEventListener('alpine:init', () => {
    Alpine.data('onboardingPopup', (recemCriada) => ({
        aberto: false,
        abaAtiva: 'carregamento',
        carregamentoConcluido: false,
        passoAtual: 0,
        slideAtual: 0,

        passos: [
            { label: 'empresa', nivel: 0 },
            { label: 'clientes', nivel: 1 },
            { label: 'contatos', nivel: 2 },
            { label: 'modelos de mensagem', nivel: 1 },
            { label: 'configuração do parser', nivel: 1 },
        ],

        slides: [
            {
                titulo: 'Bem-vindo à sua empresa de teste',
                texto: 'Criamos uma empresa fictícia com clientes, contatos e boletos já carregados, para você explorar o sistema sem precisar configurar nada.',
            },
            {
                titulo: 'Veja seus clientes',
                texto: 'Em "Clientes" você encontra todos os clientes cadastrados, seus grupos e canais de envio habilitados.',
            },
            {
                titulo: 'Gere um novo envio',
                texto: 'Em "Envios → Novo envio" você pode simular o processamento de um lote de boletos em PDF, do upload até o envio por e-mail.',
            },
            {
                titulo: 'Acompanhe o status',
                texto: 'Cada boleto mostra se foi enviado e se está pago, com os detalhes de cada cobrança disponíveis a um clique.',
            },
            {
                titulo: 'Ajuste o reconhecimento de boletos',
                texto: 'Em "Configuração do parser" você pode alterar os padrões (regex) usados para identificar cliente e código de barras em cada PDF.',
            },
        ],

        init() {
            this.carregamentoConcluido = !recemCriada;

            if (!recemCriada) {
                this.passoAtual = this.passos.length;
                this.abaAtiva = 'guia';
                return;
            }

            this.abrir();
        },

        abrir() {
            this.aberto = true;

            if (this.carregamentoConcluido) {
                this.abaAtiva = 'guia';
            } else {
                this.abaAtiva = 'carregamento';
                this.rodarAnimacaoCarregamento();
            }
        },

        fechar() {
            this.aberto = false;
        },

        rodarAnimacaoCarregamento() {
            this.passoAtual = 0;

            const avancar = () => {
                if (this.passoAtual < this.passos.length) {
                    this.passoAtual++;
                    setTimeout(avancar, 550);
                } else {
                    this.carregamentoConcluido = true;
                }
            };

            setTimeout(avancar, 400);
        },

        irParaGuia() {
            if (this.carregamentoConcluido) {
                this.abaAtiva = 'guia';
            }
        },

        proximoSlide() {
            if (this.slideAtual < this.slides.length - 1) {
                this.slideAtual++;
            }
        },

        slideAnterior() {
            if (this.slideAtual > 0) {
                this.slideAtual--;
            }
        },

        irParaSlide(i) {
            this.slideAtual = i;
        },
    }));
});
