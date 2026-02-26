<?php
header('Content-Type: application/json; charset=utf-8');

$baseDir = realpath(__DIR__ . '/documentos');

function limparTexto($str) {
    return iconv('UTF-8', 'ASCII//TRANSLIT', mb_strtolower(trim($str), 'UTF-8'));
}

function formatarNomeBusca($nome) {
    return mb_strtoupper(str_replace('-', ' ', preg_replace('/^[0-9]+\./', '', $nome)), 'UTF-8');
}

$q = isset($_GET['q']) ? limparTexto($_GET['q']) : '';
$result = [];

if ($q && strlen($q) >= 2 && $baseDir && is_dir($baseDir)) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($baseDir, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $file) {
        if (!$file->isFile()) continue;

        $nomeArquivo = $file->getFilename();
        if (substr($nomeArquivo, 0, 1) === '.') continue;
        
        if (strpos(limparTexto($nomeArquivo), $q) !== false) {
            $relPath = str_replace('\\', '/', substr($file->getPathname(), strlen($baseDir) + 1)); 
            
            $partes = explode('/', $relPath);
            array_pop($partes);
            
            $hierarquia = array_map('formatarNomeBusca', array_slice($partes, -2));
            $caminhoLegivel = !empty($hierarquia) ? implode(' > ', $hierarquia) : 'RAIZ';

            $urlFinal = 'documentos/' . implode('/', array_map('rawurlencode', explode('/', $relPath)));

            $result[] = [
                'nome'    => $nomeArquivo,
                'caminho' => $caminhoLegivel,
                'url'     => $urlFinal,
                'tamanho' => round($file->getSize() / 1024, 1) . ' KB'
            ];
        }
    }
}

usort($result, fn($a, $b) => strnatcasecmp($a['nome'], $b['nome']));
echo json_encode($result, JSON_UNESCAPED_UNICODE);