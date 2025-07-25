# Diretrizes Gerais do Projeto

## Cadastro de Usuários

Este sistema **não permite** cadastro público de usuários. Todas as contas devem ser criadas manualmente por administradores via seeders, comandos de console ou inserção direta no banco.

Implicações:

1. Nenhuma rota pública (`/register`, `POST /register`, etc.) deve existir.
2. Não devem existir views ou controllers expostos para registro.
3. Testes que cubram fluxo de registro público não devem ser adicionados.
4. Qualquer funcionalidade futura deve assumir que os usuários já estão cadastrados pelo administrador.

> Consulte este documento antes de introduzir novas features que lidem com autenticação ou gestão de usuários. 