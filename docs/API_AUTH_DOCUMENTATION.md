# API de Autenticação - Documentação

Esta documentação descreve os endpoints de autenticação disponíveis para o frontend React.

## Base URL

```
http://localhost:8000/api
```

## Headers Padrão

Para endpoints autenticados, inclua o header:

```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

## Endpoints

### 1. Login

Autentica um usuário e retorna um token de acesso.

**Endpoint:** `POST /api/auth/login`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Body:**
```json
{
  "email": "admin@upmanager.com",
  "password": "password123",
  "device_name": "React App" // Opcional
}
```

**Resposta de Sucesso (200):**
```json
{
  "success": true,
  "message": "Login realizado com sucesso",
  "data": {
    "user": {
      "id": 1,
      "name": "Administrador",
      "email": "admin@upmanager.com",
      "role": "admin",
      "role_display": "Administrador",
      "is_active": true,
      "phone": "+55 11 99999-9999",
      "position": "Administrador do Sistema",
      "last_login_at": "2024-01-20T10:30:00.000000Z"
    },
    "token": "1|abcdefghijklmnopqrstuvwxyz123456789",
    "token_type": "Bearer"
  }
}
```

**Resposta de Erro (401):**
```json
{
  "success": false,
  "message": "Credenciais inválidas",
  "errors": {
    "email": [
      "As credenciais fornecidas não correspondem aos nossos registros."
    ]
  }
}
```

**Resposta de Erro - Validação (422):**
```json
{
  "success": false,
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "O campo email é obrigatório."
    ],
    "password": [
      "A senha deve ter pelo menos 8 caracteres."
    ]
  }
}
```

**Resposta de Erro - Rate Limit (422):**
```json
{
  "success": false,
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "Muitas tentativas de login. Tente novamente em 60 segundos."
    ]
  }
}
```

---

### 2. Informações do Usuário Autenticado

Retorna as informações do usuário autenticado.

**Endpoint:** `GET /api/auth/me`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Resposta de Sucesso (200):**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "Administrador",
      "email": "admin@upmanager.com",
      "role": "admin",
      "role_display": "Administrador",
      "is_active": true,
      "phone": "+55 11 99999-9999",
      "position": "Administrador do Sistema",
      "last_login_at": "2024-01-20T10:30:00.000000Z",
      "email_verified_at": "2024-01-01T00:00:00.000000Z"
    }
  }
}
```

**Resposta de Erro (401):**
```json
{
  "success": false,
  "message": "Unauthenticated."
}
```

---

### 3. Logout

Invalida o token atual do usuário.

**Endpoint:** `POST /api/auth/logout`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Resposta de Sucesso (200):**
```json
{
  "success": true,
  "message": "Logout realizado com sucesso"
}
```

**Resposta de Erro (401):**
```json
{
  "success": false,
  "message": "Unauthenticated."
}
```

---

### 4. Logout de Todos os Dispositivos

Invalida todos os tokens do usuário.

**Endpoint:** `POST /api/auth/logout-all`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Resposta de Sucesso (200):**
```json
{
  "success": true,
  "message": "Logout de todos os dispositivos realizado com sucesso"
}
```

**Resposta de Erro (401):**
```json
{
  "success": false,
  "message": "Unauthenticated."
}
```

---

### 5. Renovar Token

Gera um novo token e invalida o token atual.

**Endpoint:** `POST /api/auth/refresh`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Resposta de Sucesso (200):**
```json
{
  "success": true,
  "message": "Token renovado com sucesso",
  "data": {
    "token": "2|newabcdefghijklmnopqrstuvwxyz123456789",
    "token_type": "Bearer"
  }
}
```

**Resposta de Erro (401):**
```json
{
  "success": false,
  "message": "Unauthenticated."
}
```

---

### 6. Recuperação e Alteração de Senha

Para endpoints relacionados à recuperação e alteração de senha, consulte a documentação específica:

📄 **[API de Recuperação de Senha - Documentação](./API_PASSWORD_RESET_DOCUMENTATION.md)**

**Endpoints disponíveis:**
- `POST /api/auth/forgot-password` - Solicitar reset de senha
- `POST /api/auth/validate-reset-token` - Validar token de reset
- `POST /api/auth/reset-password` - Redefinir senha
- `POST /api/auth/change-password` - Alterar senha (usuário autenticado)

---

## Códigos de Status HTTP

- **200 OK**: Requisição bem-sucedida
- **401 Unauthorized**: Token inválido ou expirado
- **422 Unprocessable Entity**: Dados de entrada inválidos
- **500 Internal Server Error**: Erro interno do servidor

## Rate Limiting

- **Login**: Máximo de 5 tentativas por minuto por IP/email
- **Outros endpoints**: Sem limite específico

## Estrutura de Resposta Padrão

Todas as respostas seguem o padrão:

```json
{
  "success": boolean,
  "message": "string",
  "data": object, // Opcional
  "errors": object // Opcional, apenas em caso de erro
}
```

## Tratamento de Erros

### Erros de Validação (422)
```json
{
  "success": false,
  "message": "The given data was invalid.",
  "errors": {
    "campo": ["Mensagem de erro"]
  }
}
```

### Erros de Autenticação (401)
```json
{
  "success": false,
  "message": "Unauthenticated."
}
```

### Erros Internos (500)
```json
{
  "success": false,
  "message": "Erro interno do servidor",
  "error": "Detalhes do erro (apenas em desenvolvimento)"
}
```

## Exemplo de Implementação em React

### Hook personalizado para autenticação

```javascript
// hooks/useAuth.js
import { useState, useEffect } from 'react';
import axios from 'axios';

const API_BASE_URL = 'http://localhost:8000/api';

export const useAuth = () => {
  const [user, setUser] = useState(null);
  const [token, setToken] = useState(localStorage.getItem('auth_token'));
  const [loading, setLoading] = useState(false);

  // Configurar axios
  useEffect(() => {
    if (token) {
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    } else {
      delete axios.defaults.headers.common['Authorization'];
    }
  }, [token]);

  const login = async (email, password, deviceName = 'React App') => {
    setLoading(true);
    try {
      const response = await axios.post(`${API_BASE_URL}/auth/login`, {
        email,
        password,
        device_name: deviceName
      });

      if (response.data.success) {
        const { token, user } = response.data.data;
        setToken(token);
        setUser(user);
        localStorage.setItem('auth_token', token);
        return { success: true, data: response.data.data };
      }
    } catch (error) {
      if (error.response?.data) {
        return { success: false, error: error.response.data };
      }
      return { success: false, error: { message: 'Erro de conexão' } };
    } finally {
      setLoading(false);
    }
  };

  const logout = async () => {
    try {
      await axios.post(`${API_BASE_URL}/auth/logout`);
    } catch (error) {
      console.error('Erro no logout:', error);
    } finally {
      setToken(null);
      setUser(null);
      localStorage.removeItem('auth_token');
    }
  };



  const getUser = async () => {
    if (!token) return;
    
    try {
      const response = await axios.get(`${API_BASE_URL}/auth/me`);
      if (response.data.success) {
        setUser(response.data.data.user);
      }
    } catch (error) {
      if (error.response?.status === 401) {
        logout();
      }
    }
  };

  return {
    user,
    token,
    loading,
    login,
    logout,
    getUser,
    isAuthenticated: !!token
  };
};
```

### Componente de Login

```javascript
// components/Login.jsx
import React, { useState } from 'react';
import { useAuth } from '../hooks/useAuth';

const Login = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [errors, setErrors] = useState({});
  const { login, loading } = useAuth();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({});

    const result = await login(email, password);
    
    if (!result.success) {
      if (result.error.errors) {
        setErrors(result.error.errors);
      } else {
        setErrors({ general: [result.error.message] });
      }
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      <div>
        <label>Email:</label>
        <input
          type="email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          required
        />
        {errors.email && (
          <span className="error">{errors.email[0]}</span>
        )}
      </div>

      <div>
        <label>Senha:</label>
        <input
          type="password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          required
        />
        {errors.password && (
          <span className="error">{errors.password[0]}</span>
        )}
      </div>

      {errors.general && (
        <div className="error">{errors.general[0]}</div>
      )}

      <button type="submit" disabled={loading}>
        {loading ? 'Entrando...' : 'Entrar'}
      </button>
    </form>
  );
};

export default Login;
```

## Configuração do Axios

```javascript
// utils/api.js
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Interceptor para adicionar token automaticamente
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Interceptor para lidar com respostas
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Token expirado, redirecionar para login
      localStorage.removeItem('auth_token');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default api;
```

## Notas Importantes

1. **Segurança**: Nunca armazene tokens em localStorage em produção. Use httpOnly cookies quando possível.

2. **CORS**: Certifique-se de que o backend está configurado para aceitar requisições do frontend React.

3. **Ambiente**: Ajuste a `API_BASE_URL` conforme o ambiente (desenvolvimento/produção).

4. **Rate Limiting**: Implemente debounce no frontend para evitar múltiplas tentativas de login.

5. **Refresh Token**: Para implementações mais robustas, considere implementar refresh tokens automáticos.

6. **Validação**: Sempre valide dados no frontend antes de enviar para a API.
