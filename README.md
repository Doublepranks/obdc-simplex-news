# ObDC-simplex-news

Um tema WordPress modular e leve para um newsroom enxuto, baseado no protótipo ObDC.

## Características Principais

- **Design Moderno**: Baseado no protótipo visual ObDC com design tokens CSS e tipografia Inter + Merriweather.
- **Responsividade Total**: Funciona perfeitamente em dispositivos móveis, tablets e desktops.
- **Acessibilidade (A11y)**: Estrutura semântica, atributos ARIA, contraste de cores e foco visível.
- **SEO Otimizado**: Schema.org (NewsArticle), Open Graph, Twitter Cards, sitemap.xml e meta tags dinâmicas.
- **Performance**: Uso de `preconnect`, lazy loading de imagens, Critical CSS e otimização de fontes.
- **Monetização Pronta**: Espaços definidos para anúncios (`top_home`, `feed_inline`) e integração com AdSense/Ad Manager.
- **Componentes Reutilizáveis**: Todos os elementos do protótipo são implementados como template parts.
- **Customizer Integrado**: Configuração fácil do ticker LIVE, CNPJ, cidade e texto do ticker.
- **"Mais Lidas"**: Sistema de ranking de visualizações compatível com plugins populares.

## Instalação

1. Baixe o arquivo ZIP do tema ou clone este repositório.
2. No painel administrativo do WordPress, vá para "Aparência > Temas > Adicionar novo > Fazer upload de tema".
3. Faça upload do arquivo `obdc-simplex-news.zip`.
4. Ative o tema.

## Configuração

Após ativar o tema:

1. Vá para "Aparência > Personalizar".
2. Configure as opções do tema na seção "Configurações do Tema":
   - Status do Ticker LIVE (Ativado/Desativado)
   - Texto do Ticker LIVE
   - CNPJ da Empresa
   - Cidade da Sede
3. Configure os menus nas áreas "Categorias", "Rodapé: Seções", etc.
4. Adicione widgets ao sidebar "Top Home" para exibir anúncios.

## Customização Avançada

- Para personalizar a lógica de "Mais Lidas", instale um plugin de contagem de visualizações como "Simple Post Views" ou "Post Views Counter". *O tema espera que a métrica seja armazenada na meta key `post_views`.*
- Para adicionar um paywall, crie uma página "Assine" e use um plugin de assinaturas.
- Para integrar um canal ao vivo, modifique o `template-parts/topbar.php` para chamar uma API do YouTube/Twitch.

## Licença

GPL-2.0-or-later

## Autor

Samuel Pantoja — https://www.obrasildecima.com.br