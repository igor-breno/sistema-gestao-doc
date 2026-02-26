<?php
$baseDir = __DIR__ . '/documentos';
$path = isset($_GET['path']) ? trim(str_replace(['..', '//'], '', $_GET['path']), '/') : '';
$caminhoCompleto = $baseDir . '/' . $path;
$boxParaAbrir = isset($_GET['open']) ? $_GET['open'] : '';

if (!$path || !is_dir($caminhoCompleto)) { header("Location: index.php"); exit; }

function formatarNome($nome) {
    return mb_strtoupper(str_replace('-', ' ', preg_replace('/^[0-9]+\./', '', $nome)), 'UTF-8');
}

function gerarBreadcrumb($path) {
    $parts = explode('/', $path);
    $html = '<div class="breadcrumb"><a href="index.php">INÍCIO</a>';
    $acumulado = '';
    foreach ($parts as $i => $part) {
        $acumulado .= ($acumulado ? '/' : '') . $part;
        $html .= '<span class="sep">/</span>';
        if ($i == count($parts) - 1) {
            $html .= '<span class="atual">' . formatarNome($part) . '</span>';
        } else {
            $html .= '<a href="navegar/'. rawurlencode($acumulado) .'">' . formatarNome($part) . '</a>';
        }
    }
    return $html . '</div>';
}

$partsPath = explode('/', $path);
$linkVoltar = count($partsPath) <= 1 ? 'index.php' : 'navegar/' . rawurlencode(implode('/', array_slice($partsPath, 0, -1)));

$conteudo = [];
foreach (scandir($caminhoCompleto) as $item) {
    if ($item === '.' || $item === '..') continue;
    $fullPathItem = $caminhoCompleto . '/' . $item;
    
    if (is_dir($fullPathItem)) {
        $subs = array_diff(scandir($fullPathItem), ['.', '..']);
        $listaSubs = array_filter($subs, fn($s) => is_dir($fullPathItem.'/'.$s));
        sort($listaSubs);
        $conteudo[] = [
            'nome' => $item,
            'path_relativo' => $path . '/' . $item,
            'tem_sub_pastas' => count($listaSubs) > 0,
            'subs' => $listaSubs
        ];
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= formatarNome(basename($path)) ?> - Sistema</title>
<link rel="stylesheet" href="assets/css/estilo.css">
<script src="assets/js/script.js" defer></script>
</head>
<body>
<header class="topo">
    <img src="assets/img/imagem.png" class="brasao" alt="Logo">
    <h1>SISTEMA DE GESTÃO DE DOCUMENTOS</h1>
</header>
<nav class="menu-principal"><div class="container-menu"><a href="index.php">INÍCIO</a></div></nav>
<div class="control-bar">
  <div class="busca-area" id="boxBusca">
      <input type="text" id="campoBusca" placeholder="Pesquisar..." onkeydown="if(event.key==='Enter'){buscarArquivos()}">
      <button class="btn-lupa" onclick="buscarArquivos()">Buscar</button>
  </div>
  <?= gerarBreadcrumb($path) ?>
</div>
<div id="resultadoBusca" style="display:none; max-width:1000px; margin:0 auto;">
    <button onclick="fecharBusca()" class="btn-voltar">VOLTAR</button>
    <div id="listaBusca" class="lista-resultados"></div>
</div>
<main id="conteudoPrincipal" class="container-vertical">
    <a href="<?= $linkVoltar ?>" class="btn-voltar">VOLTAR</a>
    <?php foreach ($conteudo as $c): ?>
        <?php if ($c['tem_sub_pastas']): ?>
            <div class="box <?= ($c['nome'] === $boxParaAbrir) ? 'open' : '' ?>">
                <button class="box-header" onclick="toggleBox(this)">
                    <div class="box-title"><h2><?= formatarNome($c['nome']) ?></h2></div>
                    <span class="arrow">▾</span>
                </button>
                <div class="box-content">
                    <div class="lista-botoes">
                        <?php foreach($c['subs'] as $sub): ?>
                            <a href="ver/<?= rawurlencode($c['path_relativo'] . '/' . $sub) ?>" class="btn-card"><?= formatarNome($sub) ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="box">
                <a href="ver/<?= rawurlencode($c['path_relativo']) ?>" class="box-header" style="text-decoration:none; color:inherit;">
                    <div class="box-title"><h2><?= formatarNome($c['nome']) ?></h2></div><span class="arrow" style="transform: rotate(-90deg);">▼</span>
                </a>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</main>
<div id="modalPdf" class="modal-overlay">
  <div class="modal-box">
    <div class="modal-header">
      <span id="tituloModal" style="font-weight:bold;">Visualização</span>
      <button class="btn-fechar-modal" onclick="fecharPopup()">×</button>
    </div>
    <iframe id="iframePdf" src=""></iframe>
  </div>
</div>
<footer>Desenvolvido por Igor Paulino - Projeto de Portfólio</footer>
</body>
</html>