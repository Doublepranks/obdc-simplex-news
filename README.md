# ObDC Simplex News

Tema WordPress modular e leve pensado para redações enxutas. Replica o protótipo visual da ObDC, priorizando performance, acessibilidade, SEO e um fluxo editorial baseado em destaques, feed contínuo e ranking de audiência.

## Requisitos

- WordPress 6.5 ou superior (REST habilitada e permalinks amigáveis).
- PHP 8.0 ou superior.
- Extensões PHP padrão do WordPress (curl, dom, gd).
- Permissões para criar/editar menus, widgets e opções no Customizer.

## Visão Geral

A home é organizada em cinco blocos: barra LIVE persistente, herói editorial (post fixo), destaques secundários, feed cronológico com “carregar mais” e coluna “Mais lidas”. Os componentes vivem em `template-parts`, permitindo reaproveitar blocos em outras páginas. A base deriva do Underscores (`_s`) com ajustes para uma redação digital.

## Principais Recursos

- Layout responsive-first com hero + cards, tipografia Inter/Merriweather via Google Fonts (`wp_resource_hints` e `font-display: swap`).
- Barra superior LIVE configurável no Customizer (status, texto, CNPJ e cidade).
- Painel editorial alimentado por posts sticky, com fallback automático para posts recentes.
- Feed infinito usando REST (`js/front-page.js`) com `IntersectionObserver` e fallback manual.
- Página de autor com carregamento progressivo (`js/author-feed.js`) sobre o endpoint dedicado.
- Resultados de busca com paginação automática via REST, reutilizando o mesmo script da home.
- Sidebar “Mais lidas” baseada na meta `post_views`, compatível com Post Views Counter e fallback manual em `inc/helpers.php`.
- Slots de anúncio prontos em `template-parts/ads` (topo, feed e espaços extras via widgets).
- SEO e redes sociais: meta tags OG/Twitter (`inc/seo-meta.php`), canonical automático e schema (`inc/structured-data.php`).
- Acessibilidade: semântica consistente, foco visível, aria labels e correções de skip link (`inc/template-functions.php`).
- Tradução preparada (`load_theme_textdomain`) e integração básica com Jetpack (`inc/jetpack.php`).

## Instalação

1. Gere o pacote `.zip` ou clone em `wp-content/themes`.
2. No painel WordPress acesse `Aparência > Temas > Adicionar novo > Enviar tema`.
3. Ative o tema “ObDC Simplex News”.
4. Confirme em `Configurações > Links Permanentes` que os permalinks amigáveis estão habilitados (necessário para os endpoints REST).

## Configuração Inicial

1. Abra `Aparência > Personalizar > Configurações do Tema` e ajuste ticker LIVE, CNPJ e cidade.
2. Em `Aparência > Menus`, crie os menus registrados (`main`, `footer-news`, `footer-brazil`, `footer-site`, `footer-opinion`, `footer-sports`, `footer-entertainment`, `footer-social`).
3. Defina os posts sticky que alimentarão o herói e os destaques.
4. Instale um contador de visualizações (ex.: Post Views Counter) ou utilize `obdc_simplex_news_increment_post_views()` em `single.php` para suprir a lista “Mais lidas”.
5. Substitua `screenshot.png` por uma imagem 1200x900 para exibição no painel de temas.
6. Adicione `images/logo.png` e `images/og-default.jpg` para alimentar os metadados e esquema. Ajuste conforme a identidade visual.

## Conteúdo Editorial

- **Hero e destaques**: `template-parts/home/hero.php` e `template-parts/home/highlights.php` consomem `obdc_simplex_news_get_front_page_featured_data()`, que cacheia sticky posts e garante exclusão no feed.
- **Feed da home**: `front-page.php` monta o loop excluindo IDs destacados e injeta dados de paginação para o JS.
- **Busca**: `search.php` replica a estrutura do feed infinito reutilizando `js/front-page.js` via endpoint REST dedicado.
- **Cards**: `template-parts/content/card.php` define o layout padrão do feed.
- **Mais lidas**: `template-parts/sidebar/most-read.php` lê a meta `post_views`; personalize `meta_key` se necessário.
- **Top bar LIVE**: `template-parts/topbar.php` usa valores do Customizer, com espaço para integrar fontes externas.
- **Autores em destaque**: `template-parts/front-page/authors-carousel.php` exibe dados de `obdc_simplex_news_get_featured_authors()`. O título da seção permanece “Equipe editorial”, mas pode ser alterado via filtro `obdc_simplex_news_authors_heading`.

## Scripts e Endpoints

- `js/front-page.js`: controla o botão “carregar mais”, estados de loading, `IntersectionObserver` e fallback manual. É reutilizado na home e na busca.
- `js/author-feed.js`: replica a lógica para páginas de autor.
- `js/navigation.js`, `js/footer-accordion.js`, `js/share.js` e `js/authors-carousel.js`: responsáveis por navegação, acordeão de rodapé, compartilhamento e carrossel.
- Endpoints REST definidos em `functions.php`:
  - `GET /wp-json/obdc-simplex-news/v1/front-page-feed?page={n}`
  - `GET /wp-json/obdc-simplex-news/v1/author-feed?author={ID}&page={n}`
  - `GET /wp-json/obdc-simplex-news/v1/search-feed?search={query}&page={n}`
  Todos retornam HTML renderizado via template parts, pronto para injeção no DOM.
- Filtros úteis: `obdc_simplex_news_front_page_autoload_limit`, `obdc_simplex_news_author_autoload_limit` e `obdc_simplex_news_search_autoload_limit` controlam o número de páginas carregadas automaticamente.

## Desenvolvimento

- Consulte `LOCAL_DEV.md` para subir o ambiente Docker (`docker compose up -d`). O compose usa `http://192.168.15.8:8080` por padrão; ajuste `WP_HOME`/`WP_SITEURL` se necessário.
- Não há build step: CSS e JS são servidos diretamente de `style.css` e `js/*.js`.
- Para novas traduções, utilize `wp i18n make-pot` apontando para o diretório do tema e gere arquivos em `languages/`.

## Estrutura de Pastas (resumo)

```
.
|-- inc/                # Funções auxiliares, SEO, Customizer, schema
|-- js/                 # Scripts: navegação, feed, autores, share
|-- template-parts/     # Componentes reutilizáveis (ads, cards, sidebar etc.)
|-- author.php          # Template de arquivo de autor com feed assíncrono
|-- front-page.php      # Home com sticky posts e feed infinito
|-- functions.php       # Suportes, menus, REST e enqueues
|-- search.php          # Resultados com feed infinito via REST
|-- single.php          # Layout completo de artigo
|-- style.css           # Metadados do tema e estilos globais
`-- README.md
```

## Licença

GPL-2.0-or-later.

## Autor

Samuel Pantoja — https://www.obrasildecima.com.br
