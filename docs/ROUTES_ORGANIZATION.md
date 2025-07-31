# Organização de Rotas por Domínio

## Estrutura Atual

As rotas foram reorganizadas e distribuídas entre os domínios para melhor organização e manutenibilidade.

### Arquivo Principal: `routes/web.php`

Contém apenas:
- Rota home (`/`)
- Rota de login (`/login`)  
- Rota do dashboard (`/dashboard`)
- Includes dos arquivos de rotas de cada domínio

### Rotas por Domínio

#### 1. Auth Domain
**Arquivo:** `app/Domains/Auth/Routes/web.php`
- `/profile` (GET, PATCH, DELETE)
- Inclui `routes/auth.php` (login, register, etc.)

#### 2. Public Domain  
**Arquivo:** `app/Domains/Public/Routes/web.php`
- `/project/{projectHash}` - Visualização pública de projeto
- `/update/{updateHash}` - Visualização pública de update
- `/customer/{customerHash}/updates` - Updates de cliente
- `/customer/{customerHash}/project/{projectHash}` - Updates específicos
- `/iframe/{customerHash}/{projectHash}` - Visualização em iframe

#### 3. Customer Domain
**Arquivo:** `app/Domains/Customer/Routes/web.php`
- `/customers` (GET, POST)
- `/customers/create` (GET)
- `/customers/{id}` (GET, PUT, DELETE)
- `/customers/{id}/edit` (GET)

#### 4. Project Domain
**Arquivo:** `app/Domains/Project/Routes/web.php`
- `/projects` (GET, POST)
- `/projects/create` (GET)
- `/projects/{id}` (GET, PUT, DELETE)
- `/projects/{id}/edit` (GET)

#### 5. Update Domain
**Arquivo:** `app/Domains/Update/Routes/web.php`
- `/updates` (GET, POST)
- `/updates/create` (GET)
- `/updates/{id}` (GET, PUT, DELETE)
- `/updates/{id}/edit` (GET)

#### 6. Group Domain
**Arquivo:** `app/Domains/Group/Routes/web.php`
- `/groups` (GET, POST)
- `/groups/create` (GET)
- `/groups/{id}` (GET, PUT, DELETE)
- `/groups/{id}/edit` (GET)

#### 7. User Domain
**Arquivo:** `app/Domains/User/Routes/web.php`
- `/users` (GET, POST) - Admin only
- `/users/create` (GET) - Admin only
- `/users/{user}` (GET, PUT, DELETE) - Admin only
- `/users/{user}/edit` (GET) - Admin only

## Middlewares

### Rotas Protegidas
- `auth` - Usuário autenticado
- `verified` - Email verificado
- `active` - Usuário ativo

### Rotas Admin
- `auth` + `verified` + `active` + `admin`

### Rotas Públicas
- Sem middleware (acesso livre)

## Vantagens da Nova Estrutura

1. **Organização**: Cada domínio gerencia suas próprias rotas
2. **Manutenibilidade**: Fácil localizar e modificar rotas específicas
3. **Escalabilidade**: Novos domínios podem ser adicionados facilmente
4. **Separação de Responsabilidades**: Cada arquivo tem um propósito claro
5. **Reutilização**: Rotas podem ser facilmente movidas ou duplicadas

## Como Adicionar Novas Rotas

1. Identifique o domínio apropriado
2. Edite o arquivo `Routes/web.php` do domínio
3. Use os middlewares apropriados
4. As rotas serão automaticamente carregadas

## Verificação

Para verificar se todas as rotas estão carregadas:

```bash
php artisan route:list
``` 