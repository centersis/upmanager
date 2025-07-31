# Organização de Testes por Domínios

## 🎯 Objetivo

Reorganizar todos os testes do projeto para dentro de seus respectivos domínios, seguindo a arquitetura Domain-Driven Design (DDD) e melhorando a organização e manutenibilidade.

## 📁 Estrutura Anterior vs Nova

### **❌ ANTES - Estrutura Centralizada**
```
tests/
├── Feature/
│   ├── Auth/
│   │   ├── AuthenticationTest.php
│   │   ├── EmailVerificationTest.php
│   │   ├── PasswordConfirmationTest.php
│   │   ├── PasswordResetTest.php
│   │   └── PasswordUpdateTest.php
│   ├── CustomerApiTest.php
│   ├── ExampleTest.php
│   ├── GroupApiTest.php
│   ├── GroupTest.php
│   ├── ProfileTest.php
│   ├── ProjectApiTest.php
│   ├── ProjectGroupIntegrationTest.php
│   └── UpdateApiTest.php
└── Unit/
    └── ExampleTest.php
```

### **✅ DEPOIS - Estrutura por Domínios**
```
app/Domains/
├── Auth/Tests/Feature/
│   ├── AuthenticationTest.php
│   ├── EmailVerificationTest.php
│   ├── PasswordConfirmationTest.php
│   ├── PasswordResetTest.php
│   ├── PasswordUpdateTest.php
│   └── ProfileTest.php
├── Customer/Tests/Feature/
│   └── CustomerApiTest.php
├── Group/Tests/Feature/
│   ├── GroupApiTest.php
│   └── GroupTest.php
├── Project/Tests/Feature/
│   ├── ProjectApiTest.php
│   └── ProjectGroupIntegrationTest.php
├── System/Tests/
│   ├── Feature/
│   │   └── ExampleTest.php
│   └── Unit/
│       └── ExampleTest.php
└── Update/Tests/Feature/
    └── UpdateApiTest.php
```

## 🔄 Migração Realizada

### **1. Criação da Estrutura de Diretórios**
```bash
# Criação dos diretórios de teste para cada domínio
mkdir -p app/Domains/Auth/Tests/Feature
mkdir -p app/Domains/Customer/Tests/Feature
mkdir -p app/Domains/Project/Tests/Feature
mkdir -p app/Domains/Update/Tests/Feature
mkdir -p app/Domains/Group/Tests/Feature
mkdir -p app/Domains/System/Tests/Feature
mkdir -p app/Domains/System/Tests/Unit
```

### **2. Movimentação dos Arquivos**
```bash
# Testes de Autenticação
mv tests/Feature/Auth/* app/Domains/Auth/Tests/Feature/
mv tests/Feature/ProfileTest.php app/Domains/Auth/Tests/Feature/

# Testes de Domínios Específicos
mv tests/Feature/CustomerApiTest.php app/Domains/Customer/Tests/Feature/
mv tests/Feature/ProjectApiTest.php app/Domains/Project/Tests/Feature/
mv tests/Feature/ProjectGroupIntegrationTest.php app/Domains/Project/Tests/Feature/
mv tests/Feature/UpdateApiTest.php app/Domains/Update/Tests/Feature/
mv tests/Feature/GroupTest.php app/Domains/Group/Tests/Feature/
mv tests/Feature/GroupApiTest.php app/Domains/Group/Tests/Feature/

# Testes de Sistema
mv tests/Feature/ExampleTest.php app/Domains/System/Tests/Feature/
mv tests/Unit/ExampleTest.php app/Domains/System/Tests/Unit/
```

### **3. Atualização de Namespaces**

#### **Testes de Autenticação:**
```diff
- namespace Tests\Feature\Auth;
+ namespace App\Domains\Auth\Tests\Feature;
```

#### **Testes de Domínios:**
```diff
- namespace Tests\Feature;
+ namespace App\Domains\{Domain}\Tests\Feature;
```

#### **Testes de Sistema:**
```diff
- namespace Tests\Feature;
+ namespace App\Domains\System\Tests\Feature;

- namespace Tests\Unit;
+ namespace App\Domains\System\Tests\Unit;
```

### **4. Configuração do PHPUnit**

#### **phpunit.xml Atualizado:**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true">
    <testsuites>
        <testsuite name="Unit">
            <directory>app/Domains/*/Tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>app/Domains/*/Tests/Feature</directory>
        </testsuite>
    </testsuites>
    <!-- ... resto da configuração ... -->
</phpunit>
```

#### **Funcionalidades do PHPUnit:**
- ✅ **Wildcards** (`*`) para incluir todos os domínios automaticamente
- ✅ **Separação** clara entre testes Unit e Feature
- ✅ **Autodescoberta** de novos domínios
- ✅ **Compatibilidade** com comandos específicos por domínio

## 📊 Distribuição dos Testes por Domínio

### **📈 Estatísticas:**
| Domínio | Feature Tests | Unit Tests | Total |
|---------|---------------|------------|-------|
| **Auth** | 6 | 0 | 6 |
| **Customer** | 1 | 0 | 1 |
| **Project** | 2 | 0 | 2 |
| **Update** | 1 | 0 | 1 |
| **Group** | 2 | 0 | 2 |
| **System** | 1 | 1 | 2 |
| **TOTAL** | **13** | **1** | **14** |

### **🧪 Detalhamento por Domínio:**

#### **🔐 Auth Domain (6 testes)**
- `AuthenticationTest.php` - Login/logout
- `EmailVerificationTest.php` - Verificação de email
- `PasswordConfirmationTest.php` - Confirmação de senha
- `PasswordResetTest.php` - Reset de senha
- `PasswordUpdateTest.php` - Atualização de senha
- `ProfileTest.php` - Perfil do usuário

#### **👥 Customer Domain (1 teste)**
- `CustomerApiTest.php` - API CRUD de clientes

#### **📋 Project Domain (2 testes)**
- `ProjectApiTest.php` - API CRUD de projetos
- `ProjectGroupIntegrationTest.php` - Integração projeto-grupo

#### **📝 Update Domain (1 teste)**
- `UpdateApiTest.php` - API CRUD de atualizações

#### **🏷️ Group Domain (2 testes)**
- `GroupTest.php` - Interface web de grupos
- `GroupApiTest.php` - API CRUD de grupos

#### **⚙️ System Domain (2 testes)**
- `ExampleTest.php` (Feature) - Teste de exemplo da aplicação
- `ExampleTest.php` (Unit) - Teste unitário básico

## 🚀 Comandos de Execução

### **Executar Todos os Testes:**
```bash
./vendor/bin/sail artisan test
```

### **Executar por Tipo:**
```bash
# Apenas testes Feature
./vendor/bin/sail artisan test --testsuite=Feature

# Apenas testes Unit
./vendor/bin/sail artisan test --testsuite=Unit
```

### **Executar por Domínio:**
```bash
# Testes de um domínio específico
./vendor/bin/sail artisan test app/Domains/Auth/Tests/
./vendor/bin/sail artisan test app/Domains/Customer/Tests/
./vendor/bin/sail artisan test app/Domains/Project/Tests/
./vendor/bin/sail artisan test app/Domains/Update/Tests/
./vendor/bin/sail artisan test app/Domains/Group/Tests/
./vendor/bin/sail artisan test app/Domains/System/Tests/
```

### **Executar Teste Específico:**
```bash
./vendor/bin/sail artisan test app/Domains/Auth/Tests/Feature/AuthenticationTest.php
```

## ✅ Verificação de Funcionamento

### **Resultado da Execução:**
```bash
./vendor/bin/sail artisan test
```

**✅ Saída:**
```
Tests:    64 passed (165 assertions)
Duration: 3.63s
```

### **Testes Organizados por Namespace:**
- ✅ `App\Domains\Auth\Tests\Feature\*` - 6 testes
- ✅ `App\Domains\Customer\Tests\Feature\*` - 1 teste
- ✅ `App\Domains\Project\Tests\Feature\*` - 2 testes
- ✅ `App\Domains\Update\Tests\Feature\*` - 1 teste
- ✅ `App\Domains\Group\Tests\Feature\*` - 2 testes
- ✅ `App\Domains\System\Tests\*` - 2 testes

## 🎯 Benefícios da Nova Organização

### **📁 Organização:**
1. **Coesão por Domínio**: Testes próximos ao código que testam
2. **Separação Clara**: Cada domínio tem seus próprios testes
3. **Facilita Navegação**: Estrutura mais intuitiva
4. **Manutenibilidade**: Easier to maintain domain-specific tests

### **🔧 Desenvolvimento:**
1. **Testes Contextuais**: Desenvolvedores encontram testes relevantes rapidamente
2. **Isolamento**: Mudanças em um domínio não afetam testes de outros
3. **Paralelização**: Possibilidade de executar testes por domínio
4. **Escalabilidade**: Fácil adição de novos testes por domínio

### **🚀 Performance:**
1. **Execução Seletiva**: Rodar apenas testes relevantes
2. **CI/CD Otimizado**: Pipelines podem focar em domínios alterados
3. **Debugging Focado**: Isolamento de problemas por domínio
4. **Feedback Rápido**: Testes mais específicos e direcionados

### **👥 Equipe:**
1. **Ownership Clear**: Cada time pode focar em seus domínios
2. **Onboarding**: Novos desenvolvedores encontram testes facilmente
3. **Code Review**: Reviews mais focados e contextuais
4. **Knowledge Sharing**: Estrutura facilita compartilhamento

## 🎉 Conclusão

**Migração 100% concluída com sucesso!**

- ✅ **14 arquivos de teste** movidos para seus domínios
- ✅ **6 domínios** organizados com estrutura de testes
- ✅ **64 testes** funcionando perfeitamente
- ✅ **Namespaces** atualizados corretamente
- ✅ **PHPUnit** configurado para autodescoberta
- ✅ **Zero quebras** na funcionalidade
- ✅ **Comandos flexíveis** para execução

A nova estrutura de testes está totalmente alinhada com a arquitetura DDD do projeto, proporcionando melhor organização, manutenibilidade e experiência de desenvolvimento! 🚀 