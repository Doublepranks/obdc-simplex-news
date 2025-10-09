# Desenvolvimento local (Docker)

Este tema é um tema WordPress. Para rodá-lo localmente sem instalar Apache/PHP/MySQL manualmente, use Docker Desktop.

## Pré‑requisitos

- Docker Desktop instalado e em execução

## Subir o ambiente

1. No diretório do tema, execute:
   
   ```bash
   docker compose up -d
   ```

2. Acesse `http://localhost:8080` e conclua a instalação do WordPress.

3. No painel do WordPress, vá em `Aparência > Temas` e ative o tema "ObDC-simplex-news".

## Estrutura

- O WordPress roda no container `wordpress` e o MySQL no container `db`.
- Os dados do WordPress e do banco ficam em volumes nomeados (`wp_data`, `db_data`).
- O diretório deste tema é montado em `/var/www/html/wp-content/themes/obdc-simplex-news`.

## Encerrar

```bash
docker compose down
```
## Configura��o de acesso via rede local

O `docker-compose.yml` j� define `WP_HOME` e `WP_SITEURL` para `http://192.168.15.8:8080`.
Assim, qualquer dispositivo na mesma rede consegue acessar o WordPress em `http://192.168.15.8:8080`.
Se alterar o IP da m�quina, atualize esses valores no compose e reinicie os containers com `docker compose up -d`.

