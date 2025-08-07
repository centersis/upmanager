# API de Dashboard - Documentação

Esta documentação descreve os endpoints da dashboard disponíveis para o frontend React.

## Base URL

```
http://localhost:8000/api
```

## Headers Padrão

Para todos os endpoints (autenticação obrigatória):

```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

## Endpoints

### 1. Overview da Dashboard

Retorna todos os dados principais da dashboard em uma única requisição.

**Endpoint:** `GET /api/dashboard/overview`

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
    "stats": {
      "customers": {
        "total": 25,
        "active": 20,
        "inactive": 5
      },
      "projects": {
        "total": 12,
        "active": 8,
        "completed": 3,
        "on_hold": 1
      },
      "updates": {
        "total": 45,
        "this_month": 15,
        "this_week": 5
      },
      "groups": {
        "total": 3
      }
    },
    "recent_updates": [
      {
        "id": 1,
        "title": "Nova funcionalidade implementada",
        "description": "Implementação do sistema de notificações",
        "type": "feature",
        "status": "published",
        "is_global": false,
        "created_at": "2024-01-20T10:30:00.000000Z",
        "project": {
          "id": 1,
          "name": "Sistema Web"
        },
        "customer": {
          "id": 1,
          "name": "Cliente ABC"
        }
      }
    ],
    "recent_projects": [
      {
        "id": 1,
        "name": "Sistema Web",
        "description": "Desenvolvimento de sistema web completo",
        "status": "active",
        "start_date": "2024-01-01",
        "end_date": "2024-06-30",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "customers": [
          {
            "id": 1,
            "name": "Cliente ABC"
          }
        ],
        "group": {
          "id": 1,
          "name": "Desenvolvimento"
        }
      }
    ],
    "recent_activity": [
      {
        "type": "update",
        "title": "Nova funcionalidade implementada",
        "description": "Nova atualização criada",
        "date": "2024-01-20T10:30:00.000000Z",
        "project": "Sistema Web",
        "customer": "Cliente ABC"
      }
    ]
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

**Resposta de Erro (403):**
```json
{
  "success": false,
  "message": "Sua conta está inativa. Entre em contato com o administrador."
}
```

---

### 2. Estatísticas da Dashboard

Retorna estatísticas detalhadas de clientes, projetos, atualizações e grupos.

**Endpoint:** `GET /api/dashboard/stats`

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
    "customers": {
      "total": 25,
      "active": 20,
      "inactive": 5
    },
    "projects": {
      "total": 12,
      "active": 8,
      "completed": 3,
      "on_hold": 1
    },
    "updates": {
      "total": 45,
      "this_month": 15,
      "this_week": 5
    },
    "groups": {
      "total": 3
    }
  }
}
```

---

### 3. Atualizações Recentes

Retorna as atualizações mais recentes do sistema.

**Endpoint:** `GET /api/dashboard/recent-updates`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Parâmetros de Query:**
- `limit` (opcional): Número de itens a retornar (padrão: 10, máximo: 50)

**Exemplo:** `GET /api/dashboard/recent-updates?limit=5`

**Resposta de Sucesso (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Nova funcionalidade implementada",
      "description": "Implementação do sistema de notificações",
      "type": "feature",
      "status": "published",
      "is_global": false,
      "created_at": "2024-01-20T10:30:00.000000Z",
      "project": {
        "id": 1,
        "name": "Sistema Web"
      },
      "customer": {
        "id": 1,
        "name": "Cliente ABC"
      }
    },
    {
      "id": 2,
      "title": "Correção de bug crítico",
      "description": "Corrigido problema na autenticação",
      "type": "bugfix",
      "status": "published",
      "is_global": true,
      "created_at": "2024-01-19T15:20:00.000000Z",
      "project": null,
      "customer": null
    }
  ]
}
```

---

### 4. Projetos Recentes

Retorna os projetos mais recentes do sistema.

**Endpoint:** `GET /api/dashboard/recent-projects`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Parâmetros de Query:**
- `limit` (opcional): Número de itens a retornar (padrão: 10, máximo: 50)

**Exemplo:** `GET /api/dashboard/recent-projects?limit=5`

**Resposta de Sucesso (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Sistema Web",
      "description": "Desenvolvimento de sistema web completo",
      "status": "active",
      "start_date": "2024-01-01",
      "end_date": "2024-06-30",
      "created_at": "2024-01-01T00:00:00.000000Z",
      "customers": [
        {
          "id": 1,
          "name": "Cliente ABC"
        },
        {
          "id": 2,
          "name": "Cliente XYZ"
        }
      ],
      "group": {
        "id": 1,
        "name": "Desenvolvimento"
      }
    }
  ]
}
```

---

### 5. Timeline de Atividades

Retorna uma timeline das atividades recentes no sistema.

**Endpoint:** `GET /api/dashboard/activity-timeline`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Parâmetros de Query:**
- `days` (opcional): Número de dias para buscar atividades (padrão: 30, máximo: 365)
- `limit` (opcional): Número de atividades a retornar (padrão: 20)

**Exemplo:** `GET /api/dashboard/activity-timeline?days=7&limit=10`

**Resposta de Sucesso (200):**
```json
{
  "success": true,
  "data": [
    {
      "type": "update",
      "title": "Nova funcionalidade implementada",
      "description": "Nova atualização criada",
      "date": "2024-01-20T10:30:00.000000Z",
      "project": "Sistema Web",
      "customer": "Cliente ABC"
    },
    {
      "type": "project",
      "title": "Sistema Mobile",
      "description": "Novo projeto criado",
      "date": "2024-01-19T14:15:00.000000Z",
      "project": "Sistema Mobile",
      "customer": "Cliente XYZ"
    },
    {
      "type": "customer",
      "title": "Empresa DEF",
      "description": "Novo cliente cadastrado",
      "date": "2024-01-18T09:45:00.000000Z",
      "project": null,
      "customer": "Empresa DEF"
    }
  ]
}
```

**Tipos de Atividade:**
- `update`: Nova atualização criada
- `project`: Novo projeto criado
- `customer`: Novo cliente cadastrado

---

## Códigos de Status HTTP

- **200 OK**: Requisição bem-sucedida
- **401 Unauthorized**: Token inválido ou ausente
- **403 Forbidden**: Usuário inativo
- **500 Internal Server Error**: Erro interno do servidor

## Estrutura de Resposta Padrão

Todas as respostas seguem o padrão:

```json
{
  "success": boolean,
  "data": object,
  "message": "string", // Opcional, apenas em caso de erro
  "error": "string" // Opcional, apenas em desenvolvimento
}
```

## Tratamento de Erros

### Erro de Autenticação (401)
```json
{
  "success": false,
  "message": "Unauthenticated."
}
```

### Erro de Usuário Inativo (403)
```json
{
  "success": false,
  "message": "Sua conta está inativa. Entre em contato com o administrador."
}
```

### Erro Interno (500)
```json
{
  "success": false,
  "message": "Erro ao buscar dados da dashboard",
  "error": "Detalhes do erro (apenas em desenvolvimento)"
}
```

## Implementação em React

### Hook personalizado para dashboard

```javascript
// hooks/useDashboard.js
import { useState, useEffect } from 'react';
import axios from 'axios';

const API_BASE_URL = 'http://localhost:8000/api';

export const useDashboard = () => {
  const [loading, setLoading] = useState(false);
  const [dashboardData, setDashboardData] = useState(null);
  const [error, setError] = useState(null);

  const fetchDashboardOverview = async () => {
    setLoading(true);
    setError(null);
    
    try {
      const token = localStorage.getItem('auth_token');
      const response = await axios.get(`${API_BASE_URL}/dashboard/overview`, {
        headers: {
          'Authorization': `Bearer ${token}`
        }
      });

      if (response.data.success) {
        setDashboardData(response.data.data);
        return { success: true, data: response.data.data };
      }
    } catch (error) {
      const errorMessage = error.response?.data?.message || 'Erro ao carregar dashboard';
      setError(errorMessage);
      return { success: false, error: errorMessage };
    } finally {
      setLoading(false);
    }
  };

  const fetchStats = async () => {
    try {
      const token = localStorage.getItem('auth_token');
      const response = await axios.get(`${API_BASE_URL}/dashboard/stats`, {
        headers: {
          'Authorization': `Bearer ${token}`
        }
      });

      if (response.data.success) {
        return { success: true, data: response.data.data };
      }
    } catch (error) {
      return { 
        success: false, 
        error: error.response?.data?.message || 'Erro ao carregar estatísticas' 
      };
    }
  };

  const fetchRecentUpdates = async (limit = 10) => {
    try {
      const token = localStorage.getItem('auth_token');
      const response = await axios.get(`${API_BASE_URL}/dashboard/recent-updates?limit=${limit}`, {
        headers: {
          'Authorization': `Bearer ${token}`
        }
      });

      if (response.data.success) {
        return { success: true, data: response.data.data };
      }
    } catch (error) {
      return { 
        success: false, 
        error: error.response?.data?.message || 'Erro ao carregar atualizações' 
      };
    }
  };

  const fetchRecentProjects = async (limit = 10) => {
    try {
      const token = localStorage.getItem('auth_token');
      const response = await axios.get(`${API_BASE_URL}/dashboard/recent-projects?limit=${limit}`, {
        headers: {
          'Authorization': `Bearer ${token}`
        }
      });

      if (response.data.success) {
        return { success: true, data: response.data.data };
      }
    } catch (error) {
      return { 
        success: false, 
        error: error.response?.data?.message || 'Erro ao carregar projetos' 
      };
    }
  };

  const fetchActivityTimeline = async (days = 30, limit = 20) => {
    try {
      const token = localStorage.getItem('auth_token');
      const response = await axios.get(`${API_BASE_URL}/dashboard/activity-timeline?days=${days}&limit=${limit}`, {
        headers: {
          'Authorization': `Bearer ${token}`
        }
      });

      if (response.data.success) {
        return { success: true, data: response.data.data };
      }
    } catch (error) {
      return { 
        success: false, 
        error: error.response?.data?.message || 'Erro ao carregar atividades' 
      };
    }
  };

  return {
    loading,
    dashboardData,
    error,
    fetchDashboardOverview,
    fetchStats,
    fetchRecentUpdates,
    fetchRecentProjects,
    fetchActivityTimeline
  };
};
```

### Componente de Dashboard

```javascript
// components/Dashboard.jsx
import React, { useEffect } from 'react';
import { useDashboard } from '../hooks/useDashboard';

const Dashboard = () => {
  const { 
    loading, 
    dashboardData, 
    error, 
    fetchDashboardOverview 
  } = useDashboard();

  useEffect(() => {
    fetchDashboardOverview();
  }, []);

  if (loading) {
    return (
      <div className="flex justify-center items-center h-64">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <strong className="font-bold">Erro!</strong>
        <span className="block sm:inline"> {error}</span>
      </div>
    );
  }

  if (!dashboardData) {
    return (
      <div className="text-center text-gray-500">
        Nenhum dado disponível
      </div>
    );
  }

  return (
    <div className="space-y-6">
      {/* Header */}
      <div>
        <h1 className="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p className="mt-2 text-sm text-gray-600">
          Visão geral do sistema
        </p>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <StatCard
          title="Clientes"
          total={dashboardData.stats.customers.total}
          subtitle={`${dashboardData.stats.customers.active} ativos`}
          icon="👥"
          color="blue"
        />
        <StatCard
          title="Projetos"
          total={dashboardData.stats.projects.total}
          subtitle={`${dashboardData.stats.projects.active} ativos`}
          icon="📁"
          color="green"
        />
        <StatCard
          title="Atualizações"
          total={dashboardData.stats.updates.total}
          subtitle={`${dashboardData.stats.updates.this_month} este mês`}
          icon="📄"
          color="yellow"
        />
        <StatCard
          title="Grupos"
          total={dashboardData.stats.groups.total}
          subtitle="Total de grupos"
          icon="🏷️"
          color="purple"
        />
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Recent Updates */}
        <div className="bg-white shadow rounded-lg p-6">
          <h2 className="text-lg font-medium text-gray-900 mb-4">
            Atualizações Recentes
          </h2>
          <div className="space-y-3">
            {dashboardData.recent_updates.map((update) => (
              <div key={update.id} className="flex items-start space-x-3">
                <div className="flex-shrink-0">
                  <div className="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    📄
                  </div>
                </div>
                <div className="flex-1 min-w-0">
                  <p className="text-sm font-medium text-gray-900">
                    {update.title}
                  </p>
                  <p className="text-sm text-gray-500">
                    {update.project?.name || 'Global'}
                  </p>
                  <p className="text-xs text-gray-400">
                    {new Date(update.created_at).toLocaleDateString('pt-BR')}
                  </p>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Recent Projects */}
        <div className="bg-white shadow rounded-lg p-6">
          <h2 className="text-lg font-medium text-gray-900 mb-4">
            Projetos Recentes
          </h2>
          <div className="space-y-3">
            {dashboardData.recent_projects.map((project) => (
              <div key={project.id} className="flex items-start space-x-3">
                <div className="flex-shrink-0">
                  <div className="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                    📁
                  </div>
                </div>
                <div className="flex-1 min-w-0">
                  <p className="text-sm font-medium text-gray-900">
                    {project.name}
                  </p>
                  <p className="text-sm text-gray-500">
                    {project.customers.map(c => c.name).join(', ')}
                  </p>
                  <p className="text-xs text-gray-400">
                    Status: {project.status}
                  </p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* Activity Timeline */}
      <div className="bg-white shadow rounded-lg p-6">
        <h2 className="text-lg font-medium text-gray-900 mb-4">
          Atividades Recentes
        </h2>
        <div className="space-y-4">
          {dashboardData.recent_activity.map((activity, index) => (
            <div key={index} className="flex items-start space-x-3">
              <div className="flex-shrink-0">
                <div className={`w-8 h-8 rounded-full flex items-center justify-center ${
                  activity.type === 'update' ? 'bg-blue-100' :
                  activity.type === 'project' ? 'bg-green-100' :
                  'bg-gray-100'
                }`}>
                  {activity.type === 'update' ? '📄' :
                   activity.type === 'project' ? '📁' : '👤'}
                </div>
              </div>
              <div className="flex-1 min-w-0">
                <p className="text-sm font-medium text-gray-900">
                  {activity.title}
                </p>
                <p className="text-sm text-gray-500">
                  {activity.description}
                </p>
                <p className="text-xs text-gray-400">
                  {new Date(activity.date).toLocaleDateString('pt-BR')} - {activity.customer}
                </p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

const StatCard = ({ title, total, subtitle, icon, color }) => {
  const colorClasses = {
    blue: 'bg-blue-100 text-blue-600',
    green: 'bg-green-100 text-green-600',
    yellow: 'bg-yellow-100 text-yellow-600',
    purple: 'bg-purple-100 text-purple-600'
  };

  return (
    <div className="bg-white overflow-hidden shadow rounded-lg">
      <div className="p-5">
        <div className="flex items-center">
          <div className="flex-shrink-0">
            <div className={`w-8 h-8 rounded-full flex items-center justify-center ${colorClasses[color]}`}>
              {icon}
            </div>
          </div>
          <div className="ml-5 w-0 flex-1">
            <dl>
              <dt className="text-sm font-medium text-gray-500 truncate">
                {title}
              </dt>
              <dd className="text-lg font-medium text-gray-900">
                {total}
              </dd>
              <dd className="text-sm text-gray-500">
                {subtitle}
              </dd>
            </dl>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;
```

### Componente de Estatísticas Separado

```javascript
// components/DashboardStats.jsx
import React, { useEffect, useState } from 'react';
import { useDashboard } from '../hooks/useDashboard';

const DashboardStats = () => {
  const { fetchStats } = useDashboard();
  const [stats, setStats] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const loadStats = async () => {
      setLoading(true);
      const result = await fetchStats();
      if (result.success) {
        setStats(result.data);
      }
      setLoading(false);
    };

    loadStats();
  }, []);

  if (loading) {
    return <div>Carregando estatísticas...</div>;
  }

  if (!stats) {
    return <div>Erro ao carregar estatísticas</div>;
  }

  return (
    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
      <div className="bg-white p-6 rounded-lg shadow">
        <h3 className="text-lg font-semibold">Clientes</h3>
        <p className="text-3xl font-bold text-blue-600">{stats.customers.total}</p>
        <p className="text-sm text-gray-500">
          {stats.customers.active} ativos, {stats.customers.inactive} inativos
        </p>
      </div>
      
      <div className="bg-white p-6 rounded-lg shadow">
        <h3 className="text-lg font-semibold">Projetos</h3>
        <p className="text-3xl font-bold text-green-600">{stats.projects.total}</p>
        <p className="text-sm text-gray-500">
          {stats.projects.active} ativos, {stats.projects.completed} concluídos
        </p>
      </div>
      
      <div className="bg-white p-6 rounded-lg shadow">
        <h3 className="text-lg font-semibold">Atualizações</h3>
        <p className="text-3xl font-bold text-yellow-600">{stats.updates.total}</p>
        <p className="text-sm text-gray-500">
          {stats.updates.this_month} este mês
        </p>
      </div>
      
      <div className="bg-white p-6 rounded-lg shadow">
        <h3 className="text-lg font-semibold">Grupos</h3>
        <p className="text-3xl font-bold text-purple-600">{stats.groups.total}</p>
      </div>
    </div>
  );
};

export default DashboardStats;
```

## Configuração de Interceptors

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

1. **Autenticação Obrigatória**: Todos os endpoints requerem autenticação via token Bearer

2. **Usuário Ativo**: Usuário deve estar ativo (`is_active = true`) para acessar os endpoints

3. **Rate Limiting**: Não há rate limiting específico para estes endpoints

4. **Cache**: Considere implementar cache no frontend para melhor performance

5. **Paginação**: Os endpoints `recent-updates` e `recent-projects` suportam limite via parâmetro

6. **Filtros de Data**: O endpoint `activity-timeline` permite filtrar por período

7. **Performance**: O endpoint `overview` combina múltiplas consultas - use com moderação
