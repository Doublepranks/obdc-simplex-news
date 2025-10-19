# AGENTS.md

Regras para qualquer agente automatizado trabalhando neste repositório.

## 1. Preservar o estado atual

- Antes de modificar arquivos, registre um ponto de retorno usando **um** dos métodos:
  - Executar `python scripts/create_snapshot.py -l "<descrição>"` para gerar um snapshot local (armazenado em `.snapshots/` com retenção automática dos 5 arquivos mais recentes); ou
  - Copiar os arquivos relevantes para um diretório/arquivo externo ao projeto.
- Sempre que for iniciar uma tarefa potencialmente destrutiva (ex.: `git reset`, `git stash drop`, reinstalações em massa), **obrigatoriamente** rode o snapshot: `python scripts/create_snapshot.py -l "<contexto-da-tarefa>"`.
- Se nenhuma das opções acima for possível (por exemplo, ambiente somente leitura), interrompa e informe o usuário antes de prosseguir.

## 2. Verificações iniciais

- Rode `git status -sb` para entender o estado atual e respeite quaisquer instruções adicionais em `README.md`, `CONTRIBUTING.md` ou outros documentos do projeto.
- Confirme se dependências ou scripts de build/teste exigidos estão disponíveis antes de editar.

## 3. Durante as edições

- Siga convenções de estilo existentes (linters, formatação, etc.).
- Documente mudanças que afetem outros agentes — se uma nova regra surgir, atualize este arquivo.

## 4. Antes de finalizar a tarefa

- Execute os testes relevantes ou, se não houver, faça uma verificação manual rápida para evitar regressões.
- Informe o que foi alterado e se o backup/commit temporário pode ser descartado.

## 5. Em caso de dúvida

- Pergunte ao usuário antes de tomar ações irreversíveis ou fora das diretrizes acima.
