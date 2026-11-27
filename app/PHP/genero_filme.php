<?php
// carrinho.php - Versão mínima só para exibir os ingressos adicionados (substitua pela sua)
session_start();

$ingressos = isset($_SESSION['carrinho']['ingressos']) ? $_SESSION['carrinho']['ingressos'] : [];

if (isset($_GET['limpar'])) {
    unset($_SESSION['carrinho']['ingressos']);
    header('Location: carrinho.php');
    exit;
}

$total = 0;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>LUX - Carrinho</title>
    <style>
        body { font-family: Arial, sans-serif; background:#111; color:#eee; padding:24px; }
        a { color:#f5c518; }
        table { border-collapse:collapse; margin:16px 0; }
        th, td { border:1px solid #444; padding:8px 12px; text-align:left; }
    </style>
</head>
<body>
    <h1>Carrinho</h1>
    <?php if (count($ingressos) === 0): ?>
        <p>Seu carrinho de ingressos está vazio.</p>
    <?php else: ?>
        <table>
            <tr><th>Filme</th><th>Data</th><th>Hora</th><th>Sala</th><th>Assento</th><th>Tipo</th><th>Valor</th></tr>
            <?php foreach ($ingressos as $i): $total += $i['valor']; ?>
                <tr>
                    <td><?= htmlspecialchars($i['filme']) ?></td>
                    <td><?= date('d/m/Y', strtotime($i['dia'])) ?></td>
                    <td><?= substr($i['hora'], 0, 5) ?></td>
                    <td><?= (int) $i['sala'] ?></td>
                    <td><?= htmlspecialchars($i['assento']) ?></td>
                    <td><?= ucfirst($i['tipo']) ?></td>
                    <td>R$ <?= number_format($i['valor'], 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <p><strong>Total: R$ <?= number_format($total, 2, ',', '.') ?></strong></p>
        <p><a href="carrinho.php?limpar=1">Limpar carrinho</a></p>
    <?php endif; ?>
    <p><a href="sessoes.php">&larr; Continuar comprando</a></p>
</body>
</html>