# Revista Severina Module

Módulo da Revista Severina para integração ao portal Brasil de Cima.

## Estrutura

- CPT: revista
- ACF: revista-severina-fields.json
- Templates:
  - Home
  - Arquivo de edições
  - Página individual
- PDF Reader via PDF.js
- Tracking preparado para Google Analytics

## Dependências

- Advanced Custom Fields (ACF)
- CPT UI (opcional)

## Instalação

1. Importar o JSON do ACF
2. Copiar a pasta severina-module
3. Incluir severina-config.php
4. Incluir functions-snippet.php
5. Criar as rotas/templates conforme documentação
## Fotos de autores e imagem padrão

Os retratos de autores **não são versionados** (ver `.gitignore` na raiz do tema):
pertencem à biblioteca de mídia, não ao tema, e somavam ~4,7 MB de binário que
ficaria permanente no histórico do git.

Para que um clone limpo não mostre imagens quebradas, os templates resolvem as
fotos por `severina_foto_autor( $arquivo )`, definida em
`integration/severina-config.php`:

- se `assets/images/$arquivo` existe, devolve a URL da foto real;
- caso contrário, devolve `assets/images/autor-padrao.svg`.

A função também escapa a URL — os nomes de arquivo têm espaços — e recusa
caminhos com `..`.

Para adicionar a foto de um autor novo, basta colocar o arquivo em
`assets/images/` no servidor: nenhuma alteração de código é necessária.
A imagem padrão é um SVG de 833 bytes na proporção do card (250×340), com a
silhueta na metade superior porque o card escurece os 45% de baixo, onde entra
o nome.
