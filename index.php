<?php
$baseDir = __DIR__ . '/documentos';
if (!is_dir($baseDir)) {
    mkdir($baseDir, 0777, true); // Cria a pasta se não existir no GitHub
}

function formatarNome($nome) {
    $nome = preg_replace('/^[0-9]+\./', '', $nome); 
    return mb_strtoupper(str_replace('-', ' ', $nome), 'UTF-8');
}

$pastas = [];
foreach (scandir($baseDir) as $item) {
    if ($item === '.' || $item === '..') continue;
    if (is_dir($baseDir . '/' . $item)) {
        $pastas[] = $item;
    }
}
natcasesort($pastas);
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Início - Sistema</title>
<link rel="stylesheet" href="assets/css/estilo.css">
<script src="assets/js/script.js" defer></script>
</head>
<body>

<header class="topo">
    <img src="assets/img/imagem.png" class="brasao" alt="Logo">
    <h1>SISTEMA DE GESTÃO DE DOCUMENTOS</h1>
</header>

<nav class="menu-principal">
  <div class="container-menu">
    <a href="index.php">INÍCIO</a>
  </div>
</nav>

<div class="control-bar">
  <div class="busca-area" id="boxBusca">
      <button class="btn-lupa" onclick="toggleBusca()">Buscar</button>
      <input type="text" id="campoBusca" placeholder="Pesquisar documento..." onkeydown="if(event.key==='Enter'){buscarArquivos()}">
  </div>
  <div class="breadcrumb"><span class="atual">INÍCIO</span></div>
</div>

<div id="resultadoBusca" style="display:none; max-width:1280px; margin:0 auto;">
    <button onclick="fecharBusca()" class="btn-voltar">VOLTAR</button>
    <div id="listaBusca" class="lista-resultados"></div>
</div>

<main id="conteudoPrincipal" class="container-vertical">
    <?php if (empty($pastas)): ?>
        <div class="msg-vazio">Nenhum diretório encontrado.</div>
    <?php else: ?>
        <?php foreach ($pastas as $p): ?>
            <div class="box">
                <a href="navegar/<?= rawurlencode($p) ?>" class="box-header" style="text-decoration:none;">
                    <div class="box-title">
                        <h2><?= formatarNome($p) ?></h2>
                    </div>
                    <span class="arrow" style="transform: rotate(-90deg);">▼</span>
                </a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</main>

<div id="modalPdf" class="modal-overlay">
  <div class="modal-box">
    <div class="modal-header">
      <span id="tituloModal" class="modal-titulo">Visualização</span>
      <button class="btn-fechar-modal" onclick="fecharPopup()">×</button>
    </div>
    <iframe id="iframePdf" src=""></iframe>
  </div>
</div>

<footer>
    Desenvolvido por Igor Paulino - Projeto de Portfólio 
</footer>
</body>
</html>