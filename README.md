# ObDC Simplex News

Tema WordPress modular e leve pensado para redações enxutas. Replica o protótipo visual da ObDC, priorizando performance, acessibilidade, SEO e um fluxo editorial baseado em destaques, feed contínuo e ranking de audiência.

## Requisitos

- WordPress 6.5 ou superior (REST API habilitada e permalinks amigáveis).
- PHP 8.0 ou superior.
- Extensões PHP padrões do WordPress (curl, dom, gd).
- Acesso para criar/editar menus, widgets e opções no Personalizar.

## Visão Geral

O tema organiza a home em cinco blocos: barra LIVE persistente, herói editorial (post fixo), destaques secundários, feed cronológico com botão "carregar mais" e a coluna "Mais lidas". Os componentes são implementados como `template-parts`, facilitando a reutilização em outras páginas do site. A base foi construída sobre o esqueleto do Underscores (`_s`), com ajustes para newsroom digital.

## Principais Recursos

- Layout responsive first com hero + cards e tipografia Inter/Merriweather servida via Google Fonts (`wp_resource_hints` e `font-display: swap`).
- Barra superior LIVE configurável pelo Customizer (status, texto, CNPJ e cidade da sede).
- Destaques da home alimentados pelos posts fixos (sticky). Fallback automático para posts recentes quando não houver conteúdo fixo.
- Feed infinito por paginação AJAX (`js/front-page.js`) usando o endpoint REST `obdc-simplex-news/v1/front-page-feed` e `IntersectionObserver` para auto-carregamento opcional.
- Página de autor com carregamento progressivo (`js/author-feed.js`) sobre o endpoint `obdc-simplex-news/v1/author-feed`.
- Sidebar "Mais lidas" baseada na meta key `post_views`, compatível com plugins como Post Views Counter ou Simple Post Views. Funções fallback em `inc/helpers.php` permitem incrementar a contagem manualmente.
- Slots de anúncio prontos em `template-parts/ads` (topo da home, no feed e slots adicionais via widgets).
- SEO e redes sociais: meta tags OG/Twitter (`inc/seo-meta.php`), canonical automático e dados estruturados Schema.org (`inc/structured-data.php`).
- Acessibilidade: semântica consistente, foco visível, aria labels e correções de skip link (`inc/template-functions.php`).
- Tradução preparada (`load_theme_textdomain`) e compatibilidade básica com Jetpack (arquivo stub em `inc/jetpack.php`).

## Instalação

1. Gere o pacote `.zip` (ou clone este repositório dentro de `wp-content/themes`).
2. No painel WordPress acesse `Aparência > Temas > Adicionar novo > Enviar tema` e envie o arquivo.
3. Ative o tema "ObDC Simplex News".
4. Vá para `Configurações > Links Permanentes` e confirme que os permalinks amigáveis estão habilitados (necessário para os endpoints REST usados pelo feed).

## Configuração Inicial

1. Abra `Aparência > Personalizar > Configurações do Tema` e ajuste ticker LIVE, CNPJ e cidade.
2. Em `Aparência > Menus`, crie os menus para as áreas registradas (`main`, `footer-news`, `footer-brazil`, `footer-site`, `footer-opinion`, `footer-sports`, `footer-entertainment`, `footer-social`).
3. Defina quais posts serão fixos (sticky) para ocupar o herói e os destaques secundários da home.
4. Instale um plugin de contagem de visualizações (ex.: Post Views Counter) ou utilize `obdc_simplex_news_increment_post_views()` em `single.php` para popular a lista "Mais lidas".
5. Substitua `screenshot.png` (atualmente vazio) por uma captura 1200x900 para exibição no painel de temas.
6. Adicione os arquivos `images/logo.png` e `images/og-default.jpg` ao tema para alimentar os metadados e o Schema. Personalize os tamanhos conforme a identidade visual da redação.

## Conteúdo Editorial

- **Hero e destaques**: controlados por `template-parts/home/hero.php` e `template-parts/home/highlights.php`. A função `obdc_simplex_news_get_front_page_featured_data()` mantém um cache leve em runtime.
- **Feed da home**: `front-page.php` usa `WP_Query` com exclusão dos IDs destacados e injeta o número máximo de páginas para o script de paginação.
- **Cards**: os cartões estão em `template-parts/content/card.php`. Ajustes visuais podem ser feitos em `style.css` nos blocos `.feed` e `.card`.
- **Mais lidas**: `template-parts/sidebar/most-read.php` espera a meta key `post_views`. Ajuste o `meta_key` se utilizar outro nome nos plugins instalados.
- **Top bar LIVE**: `template-parts/topbar.php` consome os valores do Customizer. Para integrar com uma API externa, injete a lógica de consulta antes do render.

## Scripts e Endpoints

- `js/front-page.js`: gerencia o botão "carregar mais", auto-load com `IntersectionObserver`, estados de acessibilidade e erros de rede.
- `js/author-feed.js`: replica a lógica de paginação para arquivos de autor, com fallback para interação manual.
- Endpoints REST expostos em `functions.php`:
  - `GET /wp-json/obdc-simplex-news/v1/front-page-feed?page={n}`
  - `GET /wp-json/obdc-simplex-news/v1/author-feed?author={ID}&page={n}`
  Ambos retornam HTML renderizado com template parts, pronto para injeção no DOM.

## Desenvolvimento

- Leia `LOCAL_DEV.md` para subir o ambiente Docker (`docker compose up -d`). O compose espera que o site seja acessível em `http://192.168.15.8:8080`; ajuste as variáveis `WP_HOME`/`WP_SITEURL` conforme sua rede.
- O tema não possui build step. CSS e JS são carregados diretamente dos arquivos presentes em `style.css` e `js/*.js`.
- Ao adicionar novas traduções, utilize `wp i18n make-pot` apontando para o diretório do tema e gere os arquivos em `languages/`.

## Estrutura de Pastas (resumo)

```
.
├── inc/                # Funções auxiliares, SEO, Customizer, schema
├── js/                 # Scripts de navegação, front page e autor
├── template-parts/     # Componentes reutilizáveis (ads, cards, sidebar, etc.)
├── author.php          # Template de arquivos de autor com feed assíncrono
├── front-page.php      # Home destacando sticky posts e feed infinito
├── functions.php       # Registro de suporte, menus, REST, enqueues
├── single.php          # Layout completo de artigo com blocos de apoio
├── style.css           # Metadados do tema e estilos globais
└── README.md           # Este arquivo
```

## Licença

GPL-2.0-or-later.

## Autor

Samuel Pantoja — https://www.obrasildecima.com.br