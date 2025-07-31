# Organização de Rotas por Domínio

## Estrutura Atual

As rotas foram **completamente reorganizadas** e são carregadas através dos **ServiceProviders** de cada domínio, seguindo as melhores práticas do Laravel.

### Arquivos Principais

#### `routes/web.php`
**Arquivo limpo!** Contém apenas comentários explicativos.

#### `routes/api.php`  
**Arquivo limpo!** Contém apenas comentários explicativos.

#### `routes/auth.php`
**Arquivo limpo!** Todas as rotas de autenticação foram movidas para o domínio Auth.

### Carregamento via ServiceProviders

Cada domínio possui seu próprio **ServiceProvider** que carrega automaticamente:
- **Rotas Web**: `Routes/web.php` com middleware `web`
- **Rotas API**: `Routes/api.php` com middleware `api` e prefixo `/api`
- **Views**: `Resources/views` com namespace do domínio

### Nomenclatura de Rotas

Para evitar conflitos entre rotas Web e API, utilizamos a seguinte convenção:
- **Rotas Web**: `{resource}.{action}` (ex: `customers.index`, `projects.show`)
- **Rotas API**: `api.{resource}.{action}` (ex: `api.customers.index`, `api.projects.show`)

### Estrutura por Domínio

#### 1. Dashboard Domain
**ServiceProvider:** `app/Domains/Dashboard/Providers/DashboardServiceProvider.php`
**Rotas Web:** `app/Domains/Dashboard/Routes/web.php`
**Controller:** `app/Domains/Dashboard/Http/Controllers/DashboardController.php`
**View:** `app/Domains/Dashboard/Resources/views/index.blade.php`
- `/` (home redirect)
- `/dashboard` - Dashboard principal

#### 2. Auth Domain
**ServiceProvider:** `app/Domains/Auth/Providers/AuthServiceProvider.php`
**Rotas Web:** `app/Domains/Auth/Routes/web.php`
- `/login` (GET, POST) - Login/Logout
- `/forgot-password` (GET, POST) - Reset de senha
- `/reset-password/{token}` (GET, POST) - Nova senha
- `/verify-email` - Verificação de email
- `/confirm-password` - Confirmação de senha
- `/profile` (GET, PATCH, DELETE) - Perfil do usuário

#### 3. Public Domain  
**ServiceProvider:** `app/Domains/Public/Providers/PublicServiceProvider.php`
**Rotas Web:** `app/Domains/Public/Routes/web.php`
- `/project/{projectHash}` - Visualização pública de projeto
- `/update/{updateHash}` - Visualização pública de update
- `/customer/{customerHash}/updates` - Updates de cliente
- `/customer/{customerHash}/project/{projectHash}` - Updates específicos
- `/iframe/{customerHash}/{projectHash}` - Visualização em iframe

#### 4. Customer Domain
**ServiceProvider:** `app/Domains/Customer/Providers/CustomerServiceProvider.php`
**Rotas Web:** `app/Domains/Customer/Routes/web.php`
**Rotas API:** `app/Domains/Customer/Routes/api.php`
- Web: `/customers` (GET, POST), `/customers/create`, `/customers/{id}`, `/customers/{id}/edit`
- API: `/api/customers` (Resource completo com nomes `api.customers.*`)

#### 5. Project Domain
**ServiceProvider:** `app/Domains/Project/Providers/ProjectServiceProvider.php`
**Rotas Web:** `app/Domains/Project/Routes/web.php`
**Rotas API:** `app/Domains/Project/Routes/api.php`
- Web: `/projects` (GET, POST), `/projects/create`, `/projects/{id}`, `/projects/{id}/edit`
- API: `/api/projects` (Resource completo com nomes `api.projects.*`)

#### 6. Update Domain
**ServiceProvider:** `app/Domains/Update/Providers/UpdateServiceProvider.php`
**Rotas Web:** `app/Domains/Update/Routes/web.php`
**Rotas API:** `app/Domains/Update/Routes/api.php`
- Web: `/updates` (GET, POST), `/updates/create`, `/updates/{id}`, `/updates/{id}/edit`
- API: `/api/updates` (Resource completo com nomes `api.updates.*`)

#### 7. Group Domain
**ServiceProvider:** `app/Domains/Group/Providers/GroupServiceProvider.php`
**Rotas Web:** `app/Domains/Group/Routes/web.php`
**Rotas API:** `app/Domains/Group/Routes/api.php`
- Web: `/groups` (GET, POST), `/groups/create`, `/groups/{id}`, `/groups/{id}/edit`
- API: `/api/groups` (Resource completo com nomes `api.groups.*`), `/api/groups-active`

#### 8. User Domain
**ServiceProvider:** `app/Domains/User/Providers/UserServiceProvider.php`
**Rotas Web:** `app/Domains/User/Routes/web.php`
- Web: `/users` (GET, POST), `/users/create`, `/users/{user}`, `/users/{user}/edit` - Admin only

### Registro dos ServiceProviders

Os ServiceProviders são registrados em `bootstrap/providers.php`:
```php
return [
    App\Providers\AppServiceProvider::class,
    App\Domains\Auth\Providers\AuthServiceProvider::class,
    App\Domains\Customer\Providers\CustomerServiceProvider::class,
    App\Domains\Dashboard\Providers\DashboardServiceProvider::class,
    App\Domains\Group\Providers\GroupServiceProvider::class,
    App\Domains\Project\Providers\ProjectServiceProvider::class,
    App\Domains\Public\Providers\PublicServiceProvider::class,
    App\Domains\Update\Providers\UpdateServiceProvider::class,
    App\Domains\User\Providers\UserServiceProvider::class,
];
```

### Middlewares

#### Rotas Web Protegidas
- `auth` - Usuário autenticado
- `verified` - Email verificado
- `active` - Usuário ativo

#### Rotas API
- `api` - Middleware padrão da API

#### Rotas Admin
- `auth` + `verified` + `active` + `admin`

#### Rotas Públicas
- Sem middleware (acesso livre)

## Vantagens da Nova Estrutura

1. **🏗️ Arquitetura Limpa**: ServiceProviders gerenciam todo o domínio
2. **📦 Encapsulamento**: Cada domínio é completamente auto-suficiente
3. **🔧 Manutenibilidade**: Fácil localizar e modificar rotas específicas
4. **📈 Escalabilidade**: Novos domínios podem ser adicionados facilmente
5. **🎯 Separação de Responsabilidades**: Cada arquivo tem um propósito claro
6. **⚡ Performance**: Carregamento automático sem includes manuais
7. **🧪 Testabilidade**: Domínios isolados facilitam testes
8. **🚫 Sem Conflitos**: Nomenclatura clara evita conflitos entre rotas Web e API
9. **🧹 Arquivos Limpos**: routes/web.php e routes/auth.php completamente limpos

## Como Adicionar Novas Rotas

1. **Identifique o domínio apropriado**
2. **Edite o arquivo de rotas do domínio**:
   - `app/Domains/{Domain}/Routes/web.php` - Para rotas web
   - `app/Domains/{Domain}/Routes/api.php` - Para rotas API
3. **Use os middlewares apropriados dentro do arquivo**
4. **Para rotas API, defina nomes únicos**:
   ```php
   Route::apiResource('items', ItemController::class)->names([
       'index' => 'api.items.index',
       'store' => 'api.items.store',
       'show' => 'api.items.show',
       'update' => 'api.items.update',
       'destroy' => 'api.items.destroy',
   ]);
   ```
5. **As rotas serão automaticamente carregadas** pelo ServiceProvider

## Como Criar Novo Domínio

1. **Crie a estrutura** do domínio
2. **Crie o ServiceProvider**:
   ```php
   Route::middleware('web')->group(function () {
       $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
   });
   
   Route::middleware('api')->prefix('api')->group(function () {
       $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
   });
   ```
3. **Registre em** `bootstrap/providers.php`
4. **Crie os arquivos de rotas** web e API com nomenclatura adequada

## Verificação

Para verificar se todas as rotas estão carregadas:
```bash
php artisan route:clear
php artisan route:list
```

## Migração Completa - 100% Finalizada

✅ **Rotas movidas do arquivo principal para os domínios**
✅ **ServiceProviders configurados para carregamento automático**
✅ **Rotas API separadas por domínio**
✅ **Conflitos de nomenclatura resolvidos**
✅ **Domínio Dashboard criado e configurado**
✅ **Todas as rotas de autenticação movidas para o domínio Auth**
✅ **Arquivos routes/web.php e routes/auth.php completamente limpos**
✅ **Controllers e views movidos para os domínios apropriados**
✅ **Documentação atualizada**
✅ **Testes de funcionamento realizados**

**🎉 Arquitetura 100% modularizada! Todos os arquivos de rotas principais estão limpos e toda funcionalidade foi distribuída nos domínios apropriados.** 