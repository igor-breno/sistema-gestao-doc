<?php
$baseDir = __DIR__ . '/documentos';
$fullPath = isset($_GET['fullPath']) ? trim(str_replace(['..', '//'], '', $_GET['fullPath']), '/') : '';
$dirCompleto = $baseDir . '/' . $fullPath;

if (!$fullPath || !is_dir($dirCompleto)) { header("Location: index.php"); exit; }

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

$arquivos = []; $pastas = [];
foreach(scandir($dirCompleto) as $f) {
    if($f === '.' || $f === '..') continue;
    if(is_dir($dirCompleto . '/' . $f)) $pastas[] = $f;
    elseif(is_file($dirCompleto . '/' . $f) && substr($f, 0, 1) !== '.') $arquivos[] = $f;
}
natcasesort($pastas); natcasesort($arquivos);

$partsVoltar = explode('/', $fullPath);
$isSubpasta = isset($_GET['sub']) ? $_GET['sub'] : '';

if ($isSubpasta === '1') {
    array_pop($partsVoltar); 
    $linkVoltar = 'ver/' . rawurlencode(implode('/', $partsVoltar));
} else {
    $qtd = count($partsVoltar);
    if ($qtd >= 2) {
        array_pop($partsVoltar);
        $gaveta = array_pop($partsVoltar);
        $linkVoltar = 'navegar/' . rawurlencode(implode('/', $partsVoltar)) . '&open=' . rawurlencode($gaveta);
    } else {
        $linkVoltar = 'index.php';
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= formatarNome(basename($fullPath)) ?> - Sistema</title>
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
  <?= gerarBreadcrumb($fullPath) ?>
</div>
<div id="resultadoBusca" style="display:none; max-width:1000px; margin:0 auto;">
    <button onclick="fecharBusca()" class="btn-voltar">VOLTAR</button>
    <div id="listaBusca" class="lista-resultados"></div>
</div>
<main id="conteudoPrincipal" class="container-vertical">
    <a href="<?= $linkVoltar ?>" class="btn-voltar">VOLTAR</a>
    <?php if (!empty($arquivos) || !empty($pastas)): ?>
        <input type="text" id="filtroLocal" placeholder="Filtrar nesta pasta..." onkeyup="filtrarListaLocal()" style="margin-bottom: 20px; padding: 10px; width: 100%; max-width: 400px; border: 1px solid #ccc;">
    <?php endif; ?>
    
    <div class="lista-resultados" id="listaLocal">
        <?php foreach ($pastas as $p): ?>
            <a href="ver/<?= rawurlencode($fullPath . '/' . $p) ?>?sub=1" class="btn-card btn-pasta" style="display:inline-block; margin-right:10px; margin-bottom:15px; font-weight:bold;">📁 <?= formatarNome($p) ?></a>
        <?php endforeach; ?>

        <?php foreach ($arquivos as $f): 
            $urlDownload = "documentos/" . $fullPath . "/" . rawurlencode($f);
        ?>
            <div class="arquivo" style="display:flex; justify-content:space-between; align-items:center; padding:15px; background:#fff; border:1px solid #ccc; margin-bottom:10px;">
                <div style="display:flex; align-items:center; gap:15px; flex:1;">
                    <img src="assets/img/imagem.png" alt="PDF" style="width:30px; height:30px;">
                    <div>
                        <strong><?= htmlspecialchars($f) ?></strong>
                    </div>
                </div>
                <div style="display:flex; gap:10px;">
                    <a href="#" onclick="abrirPopup('<?= $urlDownload ?>', '<?= htmlspecialchars($f) ?>'); return false;" title="Visualizar">
                        <img src="assets/img/imagem.png" alt="Ver" style="width:24px; height:24px;">
                    </a>
                    <a href="<?= $urlDownload ?>" download title="Baixar">
                        <img src="assets/img/imagem.png" alt="Baixar" style="width:24px; height:24px;">
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
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