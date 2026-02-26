function toggleBox(btn) {
    const box = btn.closest('.box');
    if (box) box.classList.toggle('open');
}

function toggleBusca() {
    const box = document.getElementById('boxBusca');
    const input = document.getElementById('campoBusca');
    box.classList.toggle('active');
    box.classList.contains('active') ? input.focus() : input.value = '';
}

function buscarArquivos() {
    const termo = document.getElementById('campoBusca').value.trim();
    if (termo.length < 2) return;

    document.getElementById('conteudoPrincipal').style.display = 'none';
    document.getElementById('resultadoBusca').style.display = 'block';
    const lista = document.getElementById('listaBusca');
    lista.innerHTML = 'Pesquisando...';

    fetch(`api_busca.php?q=${encodeURIComponent(termo)}`)
        .then(res => res.json())
        .then(data => {
            if (!data.length) {
                lista.innerHTML = 'Nenhum arquivo encontrado.';
                return;
            }
            lista.innerHTML = data.map(item => `
                <div class="arquivo" style="padding:15px; background:#fff; border:1px solid #ccc; margin-bottom:10px; border-radius:6px;">
                    <strong>${item.nome}</strong> <br> <small>${item.caminho}</small> <br>
                    <a href="#" onclick="abrirPopup('${item.url}', '${item.nome}'); return false;" style="color:blue;">Visualizar</a> | 
                    <a href="${item.url}" download style="color:green;">Baixar</a>
                </div>
            `).join('');
        });
}

function fecharBusca() {
    document.getElementById('resultadoBusca').style.display = 'none';
    document.getElementById('conteudoPrincipal').style.display = '';
    document.getElementById('campoBusca').value = '';
}

function abrirPopup(url, titulo) {
    document.getElementById('iframePdf').src = url;
    document.getElementById('tituloModal').innerText = titulo;
    document.getElementById('modalPdf').classList.add('ativo');
    document.getElementById('modalPdf').style.display = 'flex'; 
}

function fecharPopup() {
    document.getElementById('modalPdf').classList.remove('ativo');
    document.getElementById('modalPdf').style.display = 'none';
    setTimeout(() => document.getElementById('iframePdf').src = '', 300);
}

function filtrarListaLocal() {
    const termo = document.getElementById('filtroLocal').value.toLowerCase();
    document.querySelectorAll('.arquivo').forEach(item => {
        item.style.display = item.querySelector('strong').innerText.toLowerCase().includes(termo) ? 'flex' : 'none';
    });
    document.querySelectorAll('.btn-pasta').forEach(item => {
        item.style.display = item.innerText.toLowerCase().includes(termo) ? 'inline-block' : 'none';
    });
}