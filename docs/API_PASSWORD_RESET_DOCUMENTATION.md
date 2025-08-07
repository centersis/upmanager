# API de Recuperação de Senha - Documentação

Esta documentação descreve os endpoints de recuperação e alteração de senha disponíveis para o frontend React.

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

### 1. Solicitar Reset de Senha

Envia um link de redefinição de senha para o email do usuário.

**Endpoint:** `POST /api/auth/forgot-password`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Body:**
```json
{
  "email": "usuario@exemplo.com"
}
```

**Resposta de Sucesso (200):**
```json
{
  "success": true,
  "message": "Link de redefinição de senha enviado para seu email."
}
```

**Resposta - Email Não Cadastrado (200):**
```json
{
  "success": true,
  "message": "Se o email estiver cadastrado, você receberá um link para redefinir sua senha."
}
```

**Resposta de Erro - Usuário Inativo (403):**
```json
{
  "success": false,
  "message": "Esta conta está inativa. Entre em contato com o administrador."
}
```

**Resposta de Erro - Validação (422):**
```json
{
  "success": false,
  "message": "Dados inválidos.",
  "errors": {
    "email": [
      "O campo email é obrigatório."
    ]
  }
}
```

**Resposta de Erro - Rate Limit (422):**
```json
{
  "success": false,
  "message": "Dados inválidos.",
  "errors": {
    "email": [
      "Muitas tentativas de recuperação de senha. Tente novamente em 5 minutos."
    ]
  }
}
```

---

### 2. Validar Token de Reset

Verifica se um token de redefinição de senha é válido.

**Endpoint:** `POST /api/auth/validate-reset-token`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Body:**
```json
{
  "token": "abc123def456",
  "email": "usuario@exemplo.com"
}
```

**Resposta de Sucesso (200):**
```json
{
  "success": true,
  "message": "Token válido.",
  "data": {
    "email": "usuario@exemplo.com",
    "name": "Nome do Usuário"
  }
}
```

**Resposta de Erro - Token Inválido (400):**
```json
{
  "success": false,
  "message": "Token inválido ou expirado."
}
```

**Resposta de Erro - Usuário Não Encontrado (400):**
```json
{
  "success": false,
  "message": "Token inválido ou expirado."
}
```

**Resposta de Erro - Validação (422):**
```json
{
  "success": false,
  "message": "Dados inválidos.",
  "errors": {
    "token": [
      "O token é obrigatório."
    ],
    "email": [
      "O campo email deve ser um endereço de email válido."
    ]
  }
}
```

---

### 3. Redefinir Senha

Redefine a senha do usuário usando um token válido.

**Endpoint:** `POST /api/auth/reset-password`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Body:**
```json
{
  "token": "abc123def456",
  "email": "usuario@exemplo.com",
  "password": "novaSenha123",
  "password_confirmation": "novaSenha123"
}
```

**Resposta de Sucesso (200):**
```json
{
  "success": true,
  "message": "Senha redefinida com sucesso. Faça login com sua nova senha."
}
```

**Resposta de Erro - Token Inválido (400):**
```json
{
  "success": false,
  "message": "Token inválido ou expirado."
}
```

**Resposta de Erro - Usuário Não Encontrado (400):**
```json
{
  "success": false,
  "message": "Usuário não encontrado."
}
```

**Resposta de Erro - Validação (422):**
```json
{
  "success": false,
  "message": "Dados inválidos.",
  "errors": {
    "password": [
      "A senha deve ter pelo menos 8 caracteres.",
      "A confirmação da senha não confere."
    ]
  }
}
```

---

### 4. Alterar Senha (Usuário Autenticado)

Permite que um usuário autenticado altere sua senha.

**Endpoint:** `POST /api/auth/change-password`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Body:**
```json
{
  "current_password": "senhaAtual123",
  "password": "novaSenha123",
  "password_confirmation": "novaSenha123",
  "logout_other_devices": true
}
```

**Parâmetros do Body:**
- `current_password` (string, obrigatório): Senha atual do usuário
- `password` (string, obrigatório): Nova senha (mínimo 8 caracteres)
- `password_confirmation` (string, obrigatório): Confirmação da nova senha
- `logout_other_devices` (boolean, opcional): Se true, faz logout em todos os outros dispositivos

**Resposta de Sucesso (200):**
```json
{
  "success": true,
  "message": "Senha alterada com sucesso."
}
```

**Resposta de Erro - Não Autenticado (401):**
```json
{
  "success": false,
  "message": "Usuário não autenticado."
}
```

**Resposta de Erro - Senha Atual Incorreta (422):**
```json
{
  "success": false,
  "message": "Senha atual incorreta.",
  "errors": {
    "current_password": [
      "Senha atual incorreta."
    ]
  }
}
```

**Resposta de Erro - Validação (422):**
```json
{
  "success": false,
  "message": "Dados inválidos.",
  "errors": {
    "password": [
      "A nova senha deve ter pelo menos 8 caracteres.",
      "A confirmação da nova senha não confere."
    ]
  }
}
```

---

## Códigos de Status HTTP

- **200 OK**: Requisição bem-sucedida
- **400 Bad Request**: Token inválido ou expirado
- **401 Unauthorized**: Token de autenticação inválido ou ausente
- **403 Forbidden**: Usuário inativo
- **422 Unprocessable Entity**: Dados de entrada inválidos
- **500 Internal Server Error**: Erro interno do servidor

## Rate Limiting

- **Forgot Password**: Máximo de 3 tentativas por 5 minutos por IP/email
- **Outros endpoints**: Sem limite específico

## Fluxo de Recuperação de Senha

### 1. Usuário Esqueceu a Senha
```
Frontend → POST /api/auth/forgot-password
Backend → Envia email com link
Email → Contém link: https://app.com/reset-password?token=xxx&email=xxx
```

### 2. Usuário Clica no Link
```
Frontend → Extrai token e email da URL
Frontend → POST /api/auth/validate-reset-token (opcional, para validar antes de mostrar formulário)
Frontend → Mostra formulário de redefinição se token válido
```

### 3. Usuário Define Nova Senha
```
Frontend → POST /api/auth/reset-password
Backend → Redefine senha e revoga todos os tokens existentes
Frontend → Redireciona para login
```

## Segurança

### Medidas de Proteção

1. **Rate Limiting**: Previne ataques de força bruta
2. **Token Expiration**: Tokens de reset têm tempo limitado (padrão: 60 minutos)
3. **Single Use**: Cada token só pode ser usado uma vez
4. **Email Verification**: Só usuários com acesso ao email podem resetar
5. **Active User Check**: Só usuários ativos podem solicitar reset
6. **Token Revocation**: Todos os tokens são revogados após reset bem-sucedido

### Informações Não Reveladas

- Sistema não informa se email existe ou não (segurança)
- Mensagens de erro genéricas para tokens inválidos
- Rate limiting aplicado mesmo para emails inexistentes

## Implementação em React

### Hook personalizado para recuperação de senha

```javascript
// hooks/usePasswordReset.js
import { useState } from 'react';
import axios from 'axios';

const API_BASE_URL = 'http://localhost:8000/api';

export const usePasswordReset = () => {
  const [loading, setLoading] = useState(false);

  const forgotPassword = async (email) => {
    setLoading(true);
    try {
      const response = await axios.post(`${API_BASE_URL}/auth/forgot-password`, {
        email
      });

      if (response.data.success) {
        return { success: true, message: response.data.message };
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

  const validateResetToken = async (token, email) => {
    try {
      const response = await axios.post(`${API_BASE_URL}/auth/validate-reset-token`, {
        token,
        email
      });

      if (response.data.success) {
        return { success: true, data: response.data.data };
      }
    } catch (error) {
      if (error.response?.data) {
        return { success: false, error: error.response.data };
      }
      return { success: false, error: { message: 'Erro de conexão' } };
    }
  };

  const resetPassword = async (token, email, password, passwordConfirmation) => {
    setLoading(true);
    try {
      const response = await axios.post(`${API_BASE_URL}/auth/reset-password`, {
        token,
        email,
        password,
        password_confirmation: passwordConfirmation
      });

      if (response.data.success) {
        return { success: true, message: response.data.message };
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

  const changePassword = async (currentPassword, newPassword, passwordConfirmation, logoutOtherDevices = false) => {
    setLoading(true);
    try {
      const token = localStorage.getItem('auth_token');
      const response = await axios.post(`${API_BASE_URL}/auth/change-password`, {
        current_password: currentPassword,
        password: newPassword,
        password_confirmation: passwordConfirmation,
        logout_other_devices: logoutOtherDevices
      }, {
        headers: {
          'Authorization': `Bearer ${token}`
        }
      });

      if (response.data.success) {
        return { success: true, message: response.data.message };
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

  return {
    loading,
    forgotPassword,
    validateResetToken,
    resetPassword,
    changePassword
  };
};
```

### Componente de Recuperação de Senha

```javascript
// components/ForgotPassword.jsx
import React, { useState } from 'react';
import { usePasswordReset } from '../hooks/usePasswordReset';

const ForgotPassword = () => {
  const [email, setEmail] = useState('');
  const [message, setMessage] = useState('');
  const [errors, setErrors] = useState({});
  const { forgotPassword, loading } = usePasswordReset();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({});
    setMessage('');

    const result = await forgotPassword(email);
    
    if (result.success) {
      setMessage(result.message);
      setEmail(''); // Clear form
    } else {
      if (result.error.errors) {
        setErrors(result.error.errors);
      } else {
        setErrors({ general: [result.error.message] });
      }
    }
  };

  return (
    <div className="forgot-password-form">
      <h2>Recuperar Senha</h2>
      
      {message && (
        <div className="alert alert-success">
          {message}
        </div>
      )}

      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label htmlFor="email">Email:</label>
          <input
            type="email"
            id="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            className={errors.email ? 'form-control is-invalid' : 'form-control'}
            required
          />
          {errors.email && (
            <div className="invalid-feedback">{errors.email[0]}</div>
          )}
        </div>

        {errors.general && (
          <div className="alert alert-danger">{errors.general[0]}</div>
        )}

        <button 
          type="submit" 
          className="btn btn-primary"
          disabled={loading}
        >
          {loading ? 'Enviando...' : 'Enviar Link de Recuperação'}
        </button>
      </form>
    </div>
  );
};

export default ForgotPassword;
```

### Componente de Redefinição de Senha

```javascript
// components/ResetPassword.jsx
import React, { useState, useEffect } from 'react';
import { usePasswordReset } from '../hooks/usePasswordReset';
import { useSearchParams, useNavigate } from 'react-router-dom';

const ResetPassword = () => {
  const [searchParams] = useSearchParams();
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    password: '',
    password_confirmation: '',
  });
  const [errors, setErrors] = useState({});
  const [message, setMessage] = useState('');
  const [tokenValid, setTokenValid] = useState(false);
  const [userInfo, setUserInfo] = useState(null);
  const [initialLoading, setInitialLoading] = useState(true);
  const { validateResetToken, resetPassword, loading } = usePasswordReset();

  const token = searchParams.get('token');
  const email = searchParams.get('email');

  useEffect(() => {
    if (token && email) {
      checkToken();
    } else {
      setInitialLoading(false);
      setErrors({ general: ['Link inválido'] });
    }
  }, [token, email]);

  const checkToken = async () => {
    const result = await validateResetToken(token, email);
    
    if (result.success) {
      setTokenValid(true);
      setUserInfo(result.data);
    } else {
      setErrors({ general: [result.error.message] });
    }
    setInitialLoading(false);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({});
    setMessage('');

    const result = await resetPassword(
      token, 
      email, 
      formData.password, 
      formData.password_confirmation
    );
    
    if (result.success) {
      setMessage(result.message);
      setTimeout(() => {
        navigate('/login');
      }, 3000);
    } else {
      if (result.error.errors) {
        setErrors(result.error.errors);
      } else {
        setErrors({ general: [result.error.message] });
      }
    }
  };

  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    });
  };

  if (initialLoading) {
    return (
      <div className="text-center">
        <div className="spinner-border" role="status">
          <span className="sr-only">Verificando token...</span>
        </div>
      </div>
    );
  }

  if (errors.general) {
    return (
      <div className="alert alert-danger">
        {errors.general[0]}
      </div>
    );
  }

  if (!tokenValid) {
    return (
      <div className="alert alert-warning">
        Token inválido ou expirado
      </div>
    );
  }

  return (
    <div className="reset-password-form">
      <h2>Redefinir Senha</h2>
      
      {userInfo && (
        <p className="mb-3">
          Redefinindo senha para: <strong>{userInfo.name}</strong> ({userInfo.email})
        </p>
      )}
      
      {message && (
        <div className="alert alert-success">
          {message}
          <br />
          <small>Redirecionando para o login em 3 segundos...</small>
        </div>
      )}

      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label htmlFor="password">Nova Senha:</label>
          <input
            type="password"
            id="password"
            name="password"
            value={formData.password}
            onChange={handleChange}
            className={errors.password ? 'form-control is-invalid' : 'form-control'}
            required
            minLength="8"
          />
          {errors.password && (
            <div className="invalid-feedback">{errors.password[0]}</div>
          )}
        </div>

        <div className="form-group">
          <label htmlFor="password_confirmation">Confirmar Nova Senha:</label>
          <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            value={formData.password_confirmation}
            onChange={handleChange}
            className="form-control"
            required
            minLength="8"
          />
        </div>

        {errors.general && (
          <div className="alert alert-danger">{errors.general[0]}</div>
        )}

        <button 
          type="submit" 
          className="btn btn-primary"
          disabled={loading}
        >
          {loading ? 'Redefinindo...' : 'Redefinir Senha'}
        </button>
      </form>
    </div>
  );
};

export default ResetPassword;
```

### Componente de Alteração de Senha

```javascript
// components/ChangePassword.jsx
import React, { useState } from 'react';
import { usePasswordReset } from '../hooks/usePasswordReset';

const ChangePassword = () => {
  const [formData, setFormData] = useState({
    current_password: '',
    password: '',
    password_confirmation: '',
    logout_other_devices: false
  });
  const [errors, setErrors] = useState({});
  const [message, setMessage] = useState('');
  const { changePassword, loading } = usePasswordReset();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({});
    setMessage('');

    const result = await changePassword(
      formData.current_password,
      formData.password,
      formData.password_confirmation,
      formData.logout_other_devices
    );
    
    if (result.success) {
      setMessage(result.message);
      // Reset form
      setFormData({
        current_password: '',
        password: '',
        password_confirmation: '',
        logout_other_devices: false
      });
    } else {
      if (result.error.errors) {
        setErrors(result.error.errors);
      } else {
        setErrors({ general: [result.error.message] });
      }
    }
  };

  const handleChange = (e) => {
    const value = e.target.type === 'checkbox' ? e.target.checked : e.target.value;
    setFormData({
      ...formData,
      [e.target.name]: value
    });
  };

  return (
    <div className="change-password-form">
      <h2>Alterar Senha</h2>
      
      {message && (
        <div className="alert alert-success">{message}</div>
      )}

      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label htmlFor="current_password">Senha Atual:</label>
          <input
            type="password"
            id="current_password"
            name="current_password"
            value={formData.current_password}
            onChange={handleChange}
            className={errors.current_password ? 'form-control is-invalid' : 'form-control'}
            required
          />
          {errors.current_password && (
            <div className="invalid-feedback">{errors.current_password[0]}</div>
          )}
        </div>

        <div className="form-group">
          <label htmlFor="password">Nova Senha:</label>
          <input
            type="password"
            id="password"
            name="password"
            value={formData.password}
            onChange={handleChange}
            className={errors.password ? 'form-control is-invalid' : 'form-control'}
            required
            minLength="8"
          />
          {errors.password && (
            <div className="invalid-feedback">{errors.password[0]}</div>
          )}
        </div>

        <div className="form-group">
          <label htmlFor="password_confirmation">Confirmar Nova Senha:</label>
          <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            value={formData.password_confirmation}
            onChange={handleChange}
            className="form-control"
            required
            minLength="8"
          />
        </div>

        <div className="form-check">
          <input
            type="checkbox"
            id="logout_other_devices"
            name="logout_other_devices"
            checked={formData.logout_other_devices}
            onChange={handleChange}
            className="form-check-input"
          />
          <label htmlFor="logout_other_devices" className="form-check-label">
            Fazer logout em outros dispositivos
          </label>
        </div>

        {errors.general && (
          <div className="alert alert-danger">{errors.general[0]}</div>
        )}

        <button 
          type="submit" 
          className="btn btn-primary"
          disabled={loading}
        >
          {loading ? 'Alterando...' : 'Alterar Senha'}
        </button>
      </form>
    </div>
  );
};

export default ChangePassword;
```

## Configuração de Rotas (React Router)

```javascript
// App.jsx ou routes.jsx
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Login from './components/Login';
import ForgotPassword from './components/ForgotPassword';
import ResetPassword from './components/ResetPassword';
import ChangePassword from './components/ChangePassword';

function App() {
  return (
    <Router>
      <Routes>
        <Route path="/login" element={<Login />} />
        <Route path="/forgot-password" element={<ForgotPassword />} />
        <Route path="/reset-password" element={<ResetPassword />} />
        <Route path="/change-password" element={<ChangePassword />} />
        {/* outras rotas */}
      </Routes>
    </Router>
  );
}

export default App;
```

## Notas Importantes

1. **Configuração de Email**: Configure o Laravel para envio de emails (SMTP, Mailgun, etc.)

2. **Template de Email**: Personalize o template em `resources/views/emails/auth/reset-password.blade.php`

3. **Expiração de Token**: Tokens expiram em 60 minutos por padrão (configurável em `config/auth.php`)

4. **CORS**: Certifique-se de que o frontend está configurado no CORS do backend

5. **Validação de Senha**: Use as regras de validação do Laravel para senhas seguras

6. **Logs**: Monitore tentativas de reset em `storage/logs/laravel.log`

