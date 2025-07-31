# Como Criar um Novo Domínio - Exemplo Prático

## Exemplo: Criando o Domínio "Task"

### 1. Estrutura de Pastas

Crie a estrutura básica do domínio:

```
app/Domains/Task/
├── Database/
│   └── Factories/
│       └── TaskFactory.php
├── Entities/
│   └── Task.php
├── Http/
│   ├── Controllers/
│   │   ├── TaskController.php (API)
│   │   └── TaskWebController.php (Web)
│   └── Requests/
│       ├── StoreTaskRequest.php
│       └── UpdateTaskRequest.php
├── Providers/
│   └── TaskServiceProvider.php
├── Repositories/
│   ├── TaskRepository.php
│   └── TaskRepositoryInterface.php
├── Resources/
│   └── views/
│       ├── create.blade.php
│       ├── edit.blade.php
│       ├── index.blade.php
│       └── show.blade.php
├── Routes/
│   ├── web.php
│   └── api.php
└── Services/
    └── TaskService.php
```

### 2. ServiceProvider

Arquivo: `app/Domains/Task/Providers/TaskServiceProvider.php`

```php
<?php

namespace App\Domains\Task\Providers;

use App\Domains\Task\Repositories\TaskRepository;
use App\Domains\Task\Repositories\TaskRepositoryInterface;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TaskServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
    }

    public function boot(): void
    {
        // Register Task domain views
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'task');
        
        // Register Task domain routes
        Route::middleware('web')->group(function () {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        });
        
        Route::middleware('api')->prefix('api')->group(function () {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        });
    }
}
```

### 3. Rotas Web

Arquivo: `app/Domains/Task/Routes/web.php`

```php
<?php

use App\Domains\Task\Http\Controllers\TaskWebController;
use Illuminate\Support\Facades\Route;

// Task CRUD Routes - Protected
Route::middleware(['auth', 'verified', 'active'])->group(function () {
    Route::get('/tasks', [TaskWebController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskWebController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskWebController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{id}', [TaskWebController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/{id}/edit', [TaskWebController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{id}', [TaskWebController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{id}', [TaskWebController::class, 'destroy'])->name('tasks.destroy');
});
```

### 4. Rotas API

Arquivo: `app/Domains/Task/Routes/api.php`

```php
<?php

use App\Domains\Task\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Task API Routes
Route::apiResource('tasks', TaskController::class);
```

### 5. Registrar o ServiceProvider

Adicione o ServiceProvider em `bootstrap/providers.php`:

```php
return [
    App\Providers\AppServiceProvider::class,
    App\Domains\Auth\Providers\AuthServiceProvider::class,
    App\Domains\Customer\Providers\CustomerServiceProvider::class,
    App\Domains\Group\Providers\GroupServiceProvider::class,
    App\Domains\Project\Providers\ProjectServiceProvider::class,
    App\Domains\Public\Providers\PublicServiceProvider::class,
    App\Domains\Task\Providers\TaskServiceProvider::class, // ← NOVA LINHA
    App\Domains\Update\Providers\UpdateServiceProvider::class,
    App\Domains\User\Providers\UserServiceProvider::class,
];
```

### 6. Controladores

#### Web Controller
Arquivo: `app/Domains/Task/Http/Controllers/TaskWebController.php`

```php
<?php

namespace App\Domains\Task\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Task\Services\TaskService;
// ... imports

class TaskWebController extends Controller
{
    public function __construct(
        private TaskService $taskService
    ) {}

    public function index()
    {
        $tasks = $this->taskService->getAllTasks();
        return view('task::index', compact('tasks'));
    }

    // ... outros métodos
}
```

#### API Controller
Arquivo: `app/Domains/Task/Http/Controllers/TaskController.php`

```php
<?php

namespace App\Domains\Task\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Task\Services\TaskService;
// ... imports

class TaskController extends Controller
{
    public function __construct(
        private TaskService $taskService
    ) {}

    public function index()
    {
        return $this->taskService->getAllTasks();
    }

    // ... outros métodos da API
}
```

### 7. Verificação

Após criar o domínio, teste se as rotas foram carregadas:

```bash
php artisan route:clear
php artisan route:list | grep task
```

Você deve ver as rotas:
- `/tasks` (GET, POST)
- `/tasks/create` (GET)
- `/tasks/{id}` (GET, PUT, DELETE)
- `/tasks/{id}/edit` (GET)
- `/api/tasks` (GET, POST)
- `/api/tasks/{task}` (GET, PUT/PATCH, DELETE)

### 8. Views

Use o namespace do domínio nas views:

```php
// No controller
return view('task::index', compact('tasks'));
return view('task::create');
return view('task::edit', compact('task'));
```

### Benefícios Desta Estrutura

✅ **Auto-suficiente**: O domínio funciona independentemente  
✅ **Fácil manutenção**: Tudo relacionado a Task está em um lugar  
✅ **Testável**: Pode ser testado isoladamente  
✅ **Reutilizável**: Pode ser movido para outro projeto facilmente  
✅ **Escalável**: Adicionar novos recursos é simples  

### Próximos Passos

1. Crie as migrations para a tabela `tasks`
2. Implemente os models e repositories
3. Crie os form requests para validação
4. Adicione testes unitários e de feature
5. Configure as factories para testes

Essa estrutura garante que seu novo domínio seguirá os mesmos padrões dos demais, mantendo a consistência e qualidade do código. 