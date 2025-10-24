# Desenvolvimento local (Docker)

Este tema e um tema WordPress. Para roda-lo localmente sem instalar Apache/PHP/MySQL manualmente, use Docker Desktop.

## Pre-requisitos

- Docker Desktop instalado e em execucao

## Subir o ambiente

1. Copie `.env.example` para `.env` e ajuste as variaveis se precisar mudar porta, URL base ou credenciais (`WEB_PORT`, `WP_HOME`, `WP_SITEURL`, `MYSQL_*`, `TIMEZONE`).
2. No diretorio do tema, execute:
   ```bash
   docker compose up -d
   ```
3. Acesse `http://localhost:8080` (ou a URL configurada) e conclua a instalacao do WordPress.
4. No painel do WordPress, va em `Aparencia > Temas` e ative o tema "ObDC-simplex-news".

## Estrutura

- O WordPress roda no container `wordpress` e o MySQL no container `db`.
- Os dados do WordPress e do banco ficam em volumes nomeados (`wp_data`, `db_data`).
- O diretorio deste tema e montado em `/var/www/html/wp-content/themes/obdc-simplex-news`.

## Encerrar

```bash
docker compose down
```

## Configuracao de acesso via rede local

Por padrao, `.env` define `WP_HOME` e `WP_SITEURL` como `http://192.168.15.8:8080`.
Assim, qualquer dispositivo na mesma rede consegue acessar o WordPress em `http://192.168.15.8:8080`.
Se alterar o IP ou a porta da maquina, atualize esses valores em `.env` e reinicie os containers com `docker compose up -d`.

## Replicando em outra maquina

1. Copie este repositorio para a nova maquina.
2. Copie `.env.example` para `.env` e ajuste `WP_HOME`, `WP_SITEURL` e `WEB_PORT` para o endereco da nova rede.
3. Execute `docker compose up -d`.
4. Restaure backups do WordPress se necessario (base de dados e uploads ficam nos volumes `wp_data` e `db_data`).
