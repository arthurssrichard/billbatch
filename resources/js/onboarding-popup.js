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
                texto: 'Criamos uma empresa fictícia com clientes e contatos já cadastrados para que você possa experimentar o fluxo completo de cobrança sem precisar configurar nada.'
            },
            {
                titulo: 'O que são os grupos?',
                texto: 'Os clientes são organizados em grupos de cobrança. Cada grupo representa uma combinação de regras usada para identificar um conjunto de boletos. Neste exemplo, "150_10" significa boletos de R$ 150 com vencimento no dia 10.'
            },
            {
                titulo: 'Simule um lote de boletos',
                texto: 'Acesse "Envios → Novo envio" e clique em "Simular envio de boletos". O sistema gera um PDF fictício por grupo, reunindo o boleto de cada cliente daquele grupo em um único arquivo — como se fosse o lote que o banco te manda todo mês.'
            },
            {
                titulo: 'Cada boleto encontra seu cliente',
                texto: 'Ao clicar em "Processar", cada PDF é dividido página por página, o texto é extraído e o sistema identifica automaticamente o cliente correspondente — sem precisar abrir ou nomear os arquivos manualmente.'
            },
            {
                titulo: 'Revise e confirme o envio',
                texto: 'Antes de qualquer e-mail sair, você vê a lista de boletos processados e para quais contatos cada um será enviado. Ao confirmar, cada boleto gera uma cobrança e entra na fila de envio.'
            },
            {
                titulo: 'Os envios acontecem aos poucos',
                texto: 'Os e-mails não saem todos de uma vez: o intervalo entre cada envio é calculado a partir do limite por hora da empresa, evitando estourar a cota do provedor ou ser marcado como spam.'
            },
            {
                titulo: 'Acompanhe tudo em tempo real',
                texto: 'Volte para "Envios" e veja o status de cada boleto mudando sozinho — pendente, enviando, enviado — enquanto a fila processa em segundo plano.'
            },
            {
                titulo: 'De dias de trabalho para minutos',
                texto: 'Separar boletos, identificar clientes e enviar um por um manualmente levava dias (case real). Aqui, o mesmo processo roda em minutos — o trabalho humano fica em iniciar, revisar e acompanhar.'
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
