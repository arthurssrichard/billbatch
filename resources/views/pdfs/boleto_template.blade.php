{{-- resources/views/pdfs/boleto.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<style>

@page {
    margin: 20px 30px;
}
body {
    font-family: Helvetica, Arial, sans-serif;
    font-size: 11px;
    color: #1a1a1a;
}
.boleto {
    border: 1px solid #333;
    padding: 12px;
    page-break-after: always;
}
.boleto:last-child {
    page-break-after: avoid;
}
.header {
    border-bottom: 2px solid #333;
    padding-bottom: 8px;
    margin-bottom: 10px;
}
.header table {
    width: 100%;
    border-collapse: collapse;
}
.header .banco-nome {
    font-size: 16px;
    font-weight: bold;
}
.header .linha-digitavel {
    font-size: 13px;
    font-weight: bold;
    text-align: right;
}
table.dados {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
}
table.dados td {
    border: 1px solid #999;
    padding: 5px 8px;
    vertical-align: top;
}
table.dados .label {
    font-size: 8px;
    color: #555;
    display: block;
}
table.dados .valor {
    font-size: 12px;
    font-weight: bold;
}
.col-cliente {
    width: 60%;
}
.col-vencimento {
    width: 20%;
}
.col-valor {
    width: 20%;
}
.barcode-area {
    margin-top: 15px;
    text-align: left;
}
.barcode-area img {
    height: 50px;
}
.aviso {
    margin-top: 10px;
    font-size: 8px;
    color: #888;
    font-style: italic;
}

</style>
<body>
    @foreach ($boletos as $item)
        <div class="boleto">
            <div class="header">
                <table>
                    <tr>
                        <td class="banco-nome">Banco Modelo S.A.</td>
                        <td class="linha-digitavel">{{ $item['codigo_barras'] }}</td>
                    </tr>
                </table>
            </div>

            <table class="dados">
                <tr>
                    <td class="col-cliente">
                        <span class="label">PAGADOR</span>
                        <span class="valor">{{ $item['nome_cliente'] }}</span>
                    </td>
                    <td class="col-vencimento">
                        <span class="label">VENCIMENTO</span>
                        <span class="valor">{{ $item['vencimento']->format('d/m/Y') }}</span>
                    </td>
                    <td class="col-valor">
                        <span class="label">VALOR (R$)</span>
                        <span class="valor">{{ number_format($item['valor'], 2, ',', '.') }}</span>
                    </td>
                </tr>
            </table>

            <div class="barcode-area">
                <img src="{{ $item['barcode_base64'] }}">
            </div>

            <div class="aviso">
                Documento fictício gerado para fins de demonstração — BillBatch.
            </div>
        </div>
    @endforeach
</body>
</html>