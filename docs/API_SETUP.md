# Configuração da API para Frontend React

Este documento descreve como configurar o backend Laravel para trabalhar com o frontend React separado.

## Pré-requisitos

1. Laravel Sanctum instalado e configurado
2. Banco de dados configurado
3. Migrações executadas

## Configuração do Backend

### 1. Variáveis de Ambiente

Adicione as seguintes variáveis no arquivo `.env`:

```env
# URL do frontend React (para CORS)
FRONTEND_URL=http://localhost:3000

# Configuração do Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost:3000
SESSION_DOMAIN=localhost
```

### 2. Configuração do CORS

No arquivo `config/cors.php`, certifique-se de que as configurações estejam corretas:

```php
<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [env('FRONTEND_URL', 'http://localhost:3000')],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
```

### 3. Executar Migrações

Execute as migrações para criar as tabelas do Sanctum:

```bash
php artisan migrate
```

### 4. Testar os Endpoints

Você pode testar os endpoints usando curl ou Postman:

#### Login
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "admin@upmanager.com",
    "password": "password123"
  }'
```

#### Obter informações do usuário
```bash
curl -X GET http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer {seu_token_aqui}" \
  -H "Accept: application/json"
```

#### Logout
```bash
curl -X POST http://localhost:8000/api/auth/logout \
  -H "Authorization: Bearer {seu_token_aqui}" \
  -H "Accept: application/json"
```

## Configuração do Frontend React

### 1. Instalar Dependências

```bash
npm install axios
```

### 2. Configurar Variáveis de Ambiente

Crie um arquivo `.env` no projeto React:

```env
REACT_APP_API_BASE_URL=http://localhost:8000/api
```

### 3. Configurar Axios

Crie um arquivo `src/utils/api.js`:

```javascript
import axios from 'axios';

const api = axios.create({
  baseURL: process.env.REACT_APP_API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Interceptor para adicionar token
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Interceptor para lidar com respostas
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('auth_token');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default api;
```

## Estrutura dos Endpoints

### Endpoints Públicos
- `POST /api/auth/login` - Login do usuário

### Endpoints Protegidos (requer token)
- `GET /api/auth/me` - Informações do usuário
- `POST /api/auth/logout` - Logout do usuário
- `POST /api/auth/logout-all` - Logout de todos os dispositivos
- `POST /api/auth/refresh` - Renovar token

## Middleware de Segurança

### 1. Verificação de Usuário Ativo

Todos os endpoints protegidos verificam se o usuário está ativo (`is_active = true`).

### 2. Rate Limiting

O endpoint de login tem rate limiting de 5 tentativas por minuto por IP/email.

### 3. Validação de Dados

Todas as requisições passam por validação rigorosa dos dados de entrada.

## Tratamento de Erros

### Códigos de Status
- `200`: Sucesso
- `401`: Não autenticado / Token inválido
- `403`: Usuário inativo
- `422`: Dados de entrada inválidos
- `429`: Rate limit excedido
- `500`: Erro interno do servidor

### Formato de Resposta de Erro
```json
{
  "success": false,
  "message": "Mensagem de erro",
  "errors": {
    "campo": ["Mensagem específica do campo"]
  }
}
```

## Segurança

### 1. Tokens Sanctum
- Tokens são únicos por dispositivo
- Podem ser revogados individualmente
- Não têm expiração automática (configure se necessário)

### 2. Validação de Usuário
- Verifica se o usuário está ativo
- Rate limiting para prevenir ataques de força bruta
- Validação rigorosa de entrada

### 3. CORS
- Configurado para aceitar apenas requisições do frontend
- Headers de segurança configurados

## Logs e Monitoramento

### Logs de Autenticação
Os seguintes eventos são logados:
- Tentativas de login (sucesso/falha)
- Criação de tokens
- Logout de usuários
- Tentativas de acesso com tokens inválidos

### Monitoramento
- Rate limiting por IP
- Tentativas de login falhadas
- Tokens expirados/inválidos

## Troubleshooting

### Problema: CORS Error
**Solução**: Verifique se `FRONTEND_URL` está configurado corretamente no `.env`

### Problema: Token não funciona
**Solução**: 
1. Verifique se o token está sendo enviado no header `Authorization: Bearer {token}`
2. Verifique se o usuário está ativo
3. Verifique se o token não foi revogado

### Problema: 419 CSRF Error
**Solução**: Certifique-se de que está usando `auth:sanctum` e não `auth:web` para API

### Problema: 500 Internal Server Error
**Solução**: 
1. Verifique os logs em `storage/logs/laravel.log`
2. Certifique-se de que as migrações foram executadas
3. Verifique se o banco de dados está configurado corretamente

## Documentação Adicional

- **[API de Autenticação - Documentação](./API_AUTH_DOCUMENTATION.md)** - Endpoints de login, logout, refresh token e informações do usuário
- **[API de Recuperação de Senha - Documentação](./API_PASSWORD_RESET_DOCUMENTATION.md)** - Endpoints completos para recuperação e alteração de senha

## Próximos Passos

1. **Implementar outros módulos da API** (Customers, Projects, Updates, etc.)
2. **Configurar refresh tokens automáticos**
3. **Implementar notificações em tempo real**
4. **Adicionar logs mais detalhados**
5. **Configurar rate limiting personalizado por endpoint**
