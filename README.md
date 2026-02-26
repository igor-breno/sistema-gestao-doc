# Sistema de Gestão de Documentos

Sou estudante de Análise e Desenvolvimento de Sistemas e criei esse projeto pra resolver um problema real que a gente tinha no meu antigo trabalho: uma bagunça de normativos e PDFs espalhados em dezenas de pastas de rede que ninguém achava.

Como estou na transição para o mercado de TI, aproveitei essa demanda pra fazer o meu próprio "laboratório" de estudos. O objetivo era criar um sistema de leitura de arquivos rápido e que não dependesse de banco de dados.

# Como eu fiz?
* **PHP 8** (Lógica de backend, leitura de arquivos e criação de API)
* **JavaScript puro / Vanilla JS** (Manipulação da tela, buscas e modais)
* **HTML5 e CSS3** (Layout simples e flexbox)
* **Apache (.htaccess)** (Pra deixar as URLs bonitinhas e amigáveis)

#  O que o sistema faz?
* **Zero Banco de Dados:** Ele lê a estrutura de pastas do servidor em tempo real (usando `scandir` do PHP). Se criar uma pasta nova no Windows/Linux, ela aparece no sistema na hora.
* **Busca Recursiva:** Fiz uma API própria que entra em todas as subpastas pra localizar o nome do arquivo que o usuário digitou e já traz o caminho certinho de onde ele tá.
* **Navegação Inteligente:** Se a pasta for a principal, ele mostra como um card. Se for uma pasta mais funda, ele abre uma "gaveta" (accordion) na mesma tela, essa também foi uma solicitação do cliente.
* **Filtro em Tela:** Um script JS que filtra os PDFs na tela na mesma hora que a pessoa digita.

# O que eu aprendi com isso
Foi um baita desafio prático. Algumas coisas que quebrei a cabeça pra fazer funcionar, tive apoio de IA para tentar montar as api de busca.
* Entender como usar o `RecursiveIteratorIterator` do PHP pra busca profunda.
* Segurança básica pra não deixar o usuário acessar arquivos fora da pasta raiz (usando `realpath`).
* Ordenar arquivos do jeito humano com `strnatcasecmp` (ex: pro Documento 2 vir antes do Documento 10).


Ainda tô refatorando muita coisa e aprendendo boas práticas, mas curti bastante o resultado.

# Como testar aí na sua máquina>>>

1. Baixa o projeto e joga na pasta do seu servidor local (tipo o `htdocs` do XAMPP).
2. Confere se o seu Apache tá com o `mod_rewrite` ativado (pra URL amigável funcionar).
3. Cria uma pasta chamada `documentos` na raiz do projeto.
4. Joga umas pastas e PDFs lá dentro pra testar. (Dica: coloca os nomes das pastas com hífen no lugar do espaço, tipo `recursos-humanos`, o sistema já arruma o nome na tela sozinho).

---
Estou em busca da minha primeira oportunidade como Dev Junior/Estagiário. Se tiver dicas, feedbacks ou quiser me aconselhar, me chama lá no [LinkedIn](https://www.linkedin.com/in/igor-paulino/)!