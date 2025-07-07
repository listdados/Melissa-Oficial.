<?php
// Configuração do token do Asaas
$token = 'aact_hmlg_000MzkwODA2MWY2OGM3MWRlMDU2NWM3MzJlNzZmNGZhZGY6OjM1MzE5MjE3LWQ3YjEtNGNhYy04MWFiLTQ1ZDM3YjU5MGZmMzo6JGFhY2hfNjE0YzM5ZDgtYTBkYi00NjIzLTk3NmUtZWQzZmIyZTc1NTU0';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $cliente = [
    'name' => $_POST['nome'],
    'email' => $_POST['email'],
    'cpfCnpj' => $_POST['cpf'],
    'phone' => $_POST['telefone']
  ];

  // Criação do cliente
  $ch = curl_init('https://sandbox.asaas.com/api/v3/customers');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "access_token: $token"
  ]);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($cliente));
  $respostaCliente = curl_exec($ch);
  curl_close($ch);

  $clienteData = json_decode($respostaCliente);
  $clienteId = $clienteData->id ?? null;

  if ($clienteId) {
    // Criação da cobrança
    $pagamento = [
      'customer' => $clienteId,
      'billingType' => 'PIX',
      'value' => 99.90,
      'description' => 'Melissa Papete Bobo - Verde/Dourado'
    ];

    $ch = curl_init('https://sandbox.asaas.com/api/v3/payments');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      "Content-Type: application/json",
      "access_token: $token"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($pagamento));
    $respostaPagamento = curl_exec($ch);
    curl_close($ch);

    $pagamentoData = json_decode($respostaPagamento);
    if (!empty($pagamentoData->invoiceUrl)) {
      header("Location: {$pagamentoData->invoiceUrl}");
      exit;
    }
  }

  echo '<p>Erro ao processar pagamento. Verifique os dados.</p>';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Melissa Papete Bobo - Verde/Dourado</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
      background: #fff;
      color: #000;
    }
    .container {
      max-width: 700px;
      margin: 0 auto;
      padding: 20px;
    }
    .product img {
      width: 100%;
      margin-bottom: 20px;
    }
    form {
      margin-top: 30px;
    }
    form input {
      width: 100%;
      padding: 12px;
      margin-bottom: 12px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }
    form button {
      width: 100%;
      padding: 14px;
      background-color: #e91e63;
      color: white;
      border: none;
      font-size: 18px;
      font-weight: bold;
      border-radius: 5px;
      cursor: pointer;
    }
    form button:hover {
      background-color: #c2185b;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="product">
      <img src="https://www.melissa.com.br/dw/image/v2/BDXC_PRD/on/demandware.static/-/Sites-masterCatalog_Melissa/default/dw4744dd8f/large/35996-Melissa-Papete-Bobo-Verde-Dourado-3.png" alt="Melissa Papete Bobo - Imagem 1">
      <img src="https://www.melissa.com.br/dw/image/v2/BDXC_PRD/on/demandware.static/-/Sites-masterCatalog_Melissa/default/dw8f0f81c4/large/35996-Melissa-Papete-Bobo-Verde-Dourado-2.png" alt="Melissa Papete Bobo - Imagem 2">
      <img src="https://www.melissa.com.br/dw/image/v2/BDXC_PRD/on/demandware.static/-/Sites-masterCatalog_Melissa/default/dwb73f5f6f/large/35996-Melissa-Papete-Bobo-Verde-Dourado-1.png" alt="Melissa Papete Bobo - Imagem 3">
    </div>

    <form method="POST">
      <input type="text" name="nome" placeholder="Nome completo" required>
      <input type="email" name="email" placeholder="E-mail" required>
      <input type="text" name="cpf" placeholder="CPF" required>
      <input type="text" name="telefone" placeholder="Telefone" required>
      <button type="submit">Pagar R$ 99,90 via PIX</button>
    </form>
  </div>
</body>
</html>
