Executar o fluxo de release de forma **à prova de erro** (nunca gerar tag/release incompleto).

Contrato obrigatório (falhou qualquer item = NÃO fazer tag/push; corrigir e repetir):

1) Validar `CHANGELOG.md`
   - A seção `## [Unreleased]` deve existir e conter **exatamente UM** bloco no template obrigatório (`.cursor/commands/changelog.md`).
   - Extrair do bloco: **Versão atual** e **Versão sugerida após a mudança**.
   - A **Versão atual** deve bater com o cabeçalho `Version:` do arquivo principal `wp-affiliate-redirector.php`.

2) Garantir que o release terá conteúdo completo
   - Rodar `git status` e `git diff` para mapear TODOS os arquivos alterados.
   - Garantir que **todo arquivo alterado** e **todo arquivo novo** relacionado à mudança está versionado e será incluído no commit (ex.: `assets/`, `inc/`, `templates/`).
   - Proibido seguir se houver arquivos relevantes fora do git (untracked) que deveriam ir para o release.
   - Proibido incluir arquivos de segredo (ex.: `.env`, credenciais).

3) Garantir que a tag não existe (evita release “congelado”)
   - Verificar se a tag `v{versão_sugerida}` já existe local/remoto.
   - Se já existir: NÃO criar tag/release. Incrementar versão (SemVer) e atualizar o bloco `[Unreleased]` antes de continuar.

4) Aplicar versão sugerida no plugin
   - Atualizar o `Version:` em `wp-affiliate-redirector.php` para a versão sugerida.

5) Finalizar o changelog corretamente
   - Converter `## [Unreleased]` em `## {versão_sugerida} - YYYY-MM-DD`.
   - Inserir `#### Protocol: <hash_do_commit_anterior_ao_release>` dentro da seção versionada.
   - Recriar `## [Unreleased]` com o bloco template vazio para o próximo ciclo.

6) Commit / push (ordem obrigatória)
   - `git add` de todos os arquivos relevantes (incluindo novos).
   - `git commit` com mensagem clara de release (ex.: `chore(release): vX.Y.Z`).
   - `git push` do commit para `develop`.
   - IMPORTANTE: não criar tag local se o repositório usa GitHub Actions para tag/release automático (evita tag existente e ZIP “congelado”).

7) Validação pós-push (para Git Updater refletir)
   - Confirmar que `assets/`, `inc/` e `templates/` tiveram commits incluídos no range do release.
   - Confirmar que o GitHub Actions criou a tag `vX.Y.Z` e o ZIP do release a partir do commit pushado.

Não explicar. Apenas executar este contrato e abortar com motivo quando necessário.