================================================================================
CONTRATO DE COMANDO (CURSOR) — PROMOVER VERSÃO ESTÁVEL PARA MAIN + TAG SEMVER
================================================================================

Objetivo
- Sempre que uma versão estiver “estável” na branch `develop`, promover essa
  mesma versão para a branch `main` (branch de segurança/produção) e criar
  um tag SemVer (ex.: `v1.1.1`) apontando para o mesmo commit da versão técnica
  (ex.: `v3.2.66`).

Contexto
- Versionamento técnico (plugin / header WordPress): `3.2.xx` (ex.: 3.2.66)
- Versionamento de publicação (SemVer no Git): `1.1.x` (ex.: 1.1.1)
- Branch strategy:
  - `develop`: desenvolvimento contínuo
  - `main`: estável / segurança / produção
- Baselines: SOMENTE via tags (sem criar branches extras)

--------------------------------------------------------------------------------
COMANDO PROPOSTO (NOME)
--------------------------------------------------------------------------------
/promover_main_semver <versao_tecnica> <versao_publicacao>

Exemplo:
/promover_main_semver 3.2.66 1.1.1

--------------------------------------------------------------------------------
ENTRADAS (PARÂMETROS)
--------------------------------------------------------------------------------
1) versao_tecnica: string (ex.: 3.2.66)
2) versao_publicacao: string (ex.: 1.1.1)

Convenções:
- Tag técnica:  v<versao_tecnica>      (ex.: v3.2.66)
- Tag SemVer:   v<versao_publicacao>  (ex.: v1.1.1)

--------------------------------------------------------------------------------
PRÉ-CONDICÕES (OBRIGATÓRIAS)
--------------------------------------------------------------------------------
- Working tree limpo em `develop` (sem arquivos modificados/untracked).
- A versão técnica já deve estar “fechada” no `develop`:
  - `checkout-tabs-wp-ml.php` com `Version: <versao_tecnica>`
  - `CHANGELOG.md` com release `## [v<versao_tecnica>] - YYYY-MM-DD`
  - Tag `v<versao_tecnica>` criada e apontando para o commit estável.
- NÃO usar force push, NÃO apagar tags antigas.
- Se `v<versao_publicacao>` já existir (local ou remoto): ABORTAR o processo.

--------------------------------------------------------------------------------
PASSO A PASSO (EXECUÇÃO LOCAL — WINDOWS/POWERSHELL)
--------------------------------------------------------------------------------
Observação: PowerShell NÃO suporta `&&` como no bash; rode comandos separados.

1) Atualizar repositório (branches + tags)
   - git fetch --all --tags

2) Validar o estado do `develop`
   - git checkout develop
   - git pull
   - git status -sb
   - git tag -l "v<versao_tecnica>"
   - git show "v<versao_tecnica>" --no-patch --oneline

   Regras:
   - Se a tag técnica `v<versao_tecnica>` não existir: ABORTAR (primeiro rode o
     fluxo de release técnico no develop).

3) Validar que `v<versao_publicacao>` não existe (local e remoto)
   - git tag -l "v<versao_publicacao>"
   - git ls-remote --tags origin "v<versao_publicacao>"

   Regras:
   - Se existir: ABORTAR (não clobber tags).

4) Promover para `main`
   - git checkout main
   - git pull

   Tentativa A (fast-forward):
   - git merge --ff-only develop
     - Se funcionar: OK.

   Tentativa B (merge normal, sem squash):
   - git merge --no-ff develop -m "Merge develop into main (stable v<versao_tecnica>)"

   Se houver conflito:
   - Resolver conflitos mantendo como base o conteúdo do `develop` (porque é o
     estado estável que queremos publicar). Depois:
     - git add <arquivos resolvidos>
     - git commit  (finaliza o merge)

5) Publicar `main`
   - git push origin main

6) Criar e publicar tag SemVer (apontando para o MESMO commit da tag técnica)
   - git rev-parse "v<versao_tecnica>"  (pegar o hash alvo)
   - git tag "v<versao_publicacao>" "v<versao_tecnica>"
   - git push origin "v<versao_publicacao>"

7) Verificações finais
   - git log -1 --oneline main
   - git show "v<versao_publicacao>" --no-patch --oneline
   - git show "v<versao_tecnica>" --no-patch --oneline
   - Garantir que `v<versao_publicacao>` e `v<versao_tecnica>` apontam para o
     mesmo commit.

--------------------------------------------------------------------------------
PADRÃO DE MENSAGENS (SUGESTÕES)
--------------------------------------------------------------------------------
- Merge commit (main):
  "Merge develop into main (stable v<versao_tecnica>)"

- Tag SemVer:
  `v<versao_publicacao>` deve sempre referenciar a tag técnica correspondente.

--------------------------------------------------------------------------------
NOTAS IMPORTANTES / BOAS PRÁTICAS
--------------------------------------------------------------------------------
- Não misturar desenvolvimento direto em `main`. O “contrato” assume que o
  desenvolvimento acontece em `develop` e depois é promovido.
- As tags históricas `v3.2.x` devem ser mantidas.
- Se no futuro você quiser alinhar o `Version:` do plugin com o SemVer
  (`1.1.x`), faça isso em um release planejado, porque altera cache de assets
  e comportamento do Git Updater.

================================================================================
