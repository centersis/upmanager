# API de Updates - Documentação

Esta documentação descreve os endpoints de updates (atualizações) disponíveis para o frontend React.

## Base URL

```
http://localhost:8000/api
```

## Headers Padrão

Para endpoints autenticados:

```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

## Endpoints

### 1. Listar Updates

Retorna uma lista paginada de updates com filtros opcionais.

**Endpoint:** `GET /api/updates`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Parâmetros de Query:**
- `per_page` (opcional): Número de itens por página (padrão: 15, máximo: 100)
- `page` (opcional): Página atual (padrão: 1)
- `status` (opcional): Filtrar por status (`pending`, `published`, `draft`, `archived`)
- `project_id` (opcional): Filtrar por projeto
- `customer_id` (opcional): Filtrar por cliente
- `is_global` (opcional): Filtrar por updates globais (`true`/`false`)
- `search` (opcional): Buscar no título, descrição ou legenda

**Exemplo:** `GET /api/updates?status=published&per_page=10&search=funcionalidade`

**Resposta de Sucesso (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Nova funcionalidade implementada",
      "caption": "Sistema de notificações",
      "description": "Implementação completa do sistema de notificações em tempo real",
      "status": "published",
      "is_global": false,
      "views": 25,
      "hash": "uuid-string-here",
      "created_at": "2024-01-20T10:30:00.000000Z",
      "updated_at": "2024-01-20T10:30:00.000000Z",
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
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75,
    "from": 1,
    "to": 15
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

### 2. Criar Update

Cria um novo update no sistema.

**Endpoint:** `POST /api/updates`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Body:**
```json
{
  "project_id": 1,
  "customer_id": 1,
  "title": "Nova funcionalidade",
  "caption": "Breve descrição",
  "description": "Descrição detalhada da atualização",
  "status": "published",
  "is_global": false,
  "hash": "custom-hash-optional"
}
```

**Campos do Body:**
- `project_id` (opcional): ID do projeto relacionado
- `customer_id` (opcional): ID do cliente relacionado
- `title` (obrigatório): Título do update (máx. 255 caracteres)
- `caption` (opcional): Legenda do update (máx. 255 caracteres)
- `description` (opcional): Descrição detalhada
- `status` (opcional): Status (`pending`, `published`, `draft`, `archived`) - padrão: `pending`
- `is_global` (opcional): Se é um update global - padrão: `false`
- `hash` (opcional): Hash personalizado - gerado automaticamente se não fornecido

**Resposta de Sucesso (201):**
```json
{
  "success": true,
  "message": "Atualização criada com sucesso",
  "data": {
    "id": 1,
    "title": "Nova funcionalidade",
    "caption": "Breve descrição",
    "description": "Descrição detalhada da atualização",
    "status": "published",
    "is_global": false,
    "views": 0,
    "hash": "generated-uuid-here",
    "created_at": "2024-01-20T10:30:00.000000Z",
    "updated_at": "2024-01-20T10:30:00.000000Z",
    "project": {
      "id": 1,
      "name": "Sistema Web"
    },
    "customer": {
      "id": 1,
      "name": "Cliente ABC"
    }
  }
}
```

**Resposta de Erro - Validação (422):**
```json
{
  "success": false,
  "message": "The given data was invalid.",
  "errors": {
    "title": [
      "O título é obrigatório."
    ],
    "status": [
      "O status deve ser: pending, published, draft ou archived."
    ]
  }
}
```

---

### 3. Exibir Update

Retorna os detalhes de um update específico e incrementa o contador de visualizações.

**Endpoint:** `GET /api/updates/{id}`

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
    "id": 1,
    "title": "Nova funcionalidade implementada",
    "caption": "Sistema de notificações",
    "description": "Implementação completa do sistema de notificações em tempo real",
    "status": "published",
    "is_global": false,
    "views": 26,
    "hash": "uuid-string-here",
    "created_at": "2024-01-20T10:30:00.000000Z",
    "updated_at": "2024-01-20T10:30:00.000000Z",
    "project": {
      "id": 1,
      "name": "Sistema Web",
      "description": "Sistema web completo",
      "status": "active"
    },
    "customer": {
      "id": 1,
      "name": "Cliente ABC",
      "email": "cliente@abc.com",
      "status": "active"
    }
  }
}
```

**Resposta de Erro (404):**
```json
{
  "success": false,
  "message": "Atualização não encontrada"
}
```

---

### 4. Atualizar Update

Atualiza um update existente.

**Endpoint:** `PUT /api/updates/{id}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Body:**
```json
{
  "title": "Título atualizado",
  "status": "archived",
  "description": "Nova descrição"
}
```

**Campos do Body (todos opcionais):**
- `project_id`: ID do projeto relacionado
- `customer_id`: ID do cliente relacionado
- `title`: Título do update
- `caption`: Legenda do update
- `description`: Descrição detalhada
- `status`: Status do update
- `is_global`: Se é um update global
- `hash`: Hash personalizado

**Resposta de Sucesso (200):**
```json
{
  "success": true,
  "message": "Atualização atualizada com sucesso",
  "data": {
    "id": 1,
    "title": "Título atualizado",
    "caption": "Sistema de notificações",
    "description": "Nova descrição",
    "status": "archived",
    "is_global": false,
    "views": 26,
    "hash": "uuid-string-here",
    "created_at": "2024-01-20T10:30:00.000000Z",
    "updated_at": "2024-01-20T15:45:00.000000Z",
    "project": {
      "id": 1,
      "name": "Sistema Web"
    },
    "customer": {
      "id": 1,
      "name": "Cliente ABC"
    }
  }
}
```

---

### 5. Remover Update

Remove um update do sistema.

**Endpoint:** `DELETE /api/updates/{id}`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Resposta de Sucesso (200):**
```json
{
  "success": true,
  "message": "Atualização removida com sucesso"
}
```

**Resposta de Erro (404):**
```json
{
  "success": false,
  "message": "Atualização não encontrada"
}
```

---

### 6. Updates por Projeto

Retorna updates específicos de um projeto.

**Endpoint:** `GET /api/updates/project/{projectId}`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Parâmetros de Query:**
- `per_page` (opcional): Número de itens por página (padrão: 15, máximo: 100)
- `page` (opcional): Página atual

**Resposta de Sucesso (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Update do projeto",
      "caption": "Legenda",
      "description": "Descrição",
      "status": "published",
      "is_global": false,
      "views": 10,
      "hash": "uuid-here",
      "created_at": "2024-01-20T10:30:00.000000Z",
      "updated_at": "2024-01-20T10:30:00.000000Z",
      "customer": {
        "id": 1,
        "name": "Cliente ABC"
      }
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 2,
    "per_page": 15,
    "total": 20
  }
}
```

---

### 7. Updates por Cliente

Retorna updates específicos de um cliente.

**Endpoint:** `GET /api/updates/customer/{customerId}`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Parâmetros de Query:**
- `per_page` (opcional): Número de itens por página (padrão: 15, máximo: 100)
- `page` (opcional): Página atual

**Resposta de Sucesso (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Update do cliente",
      "caption": "Legenda",
      "description": "Descrição",
      "status": "published",
      "is_global": false,
      "views": 15,
      "hash": "uuid-here",
      "created_at": "2024-01-20T10:30:00.000000Z",
      "updated_at": "2024-01-20T10:30:00.000000Z",
      "project": {
        "id": 1,
        "name": "Sistema Web"
      }
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 15,
    "total": 5
  }
}
```

---

### 8. Update por Hash (Público)

Retorna um update específico usando seu hash único. **Não requer autenticação.**

**Endpoint:** `GET /api/updates/hash/{hash}`

**Headers:**
```
Accept: application/json
```

**Resposta de Sucesso (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Update público",
    "caption": "Legenda do update",
    "description": "Descrição completa",
    "status": "published",
    "is_global": true,
    "views": 100,
    "created_at": "2024-01-20T10:30:00.000000Z",
    "updated_at": "2024-01-20T10:30:00.000000Z",
    "project": {
      "id": 1,
      "name": "Sistema Web"
    },
    "customer": {
      "id": 1,
      "name": "Cliente ABC"
    }
  }
}
```

**Resposta de Erro (404):**
```json
{
  "success": false,
  "message": "Atualização não encontrada"
}
```

---

### 9. Opções para Updates

Retorna dados necessários para criar/editar updates (projetos, clientes, status).

**Endpoint:** `GET /api/updates-options`

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
    "projects": [
      {
        "id": 1,
        "name": "Sistema Web",
        "status": "active"
      },
      {
        "id": 2,
        "name": "App Mobile",
        "status": "active"
      }
    ],
    "customers": [
      {
        "id": 1,
        "name": "Cliente ABC",
        "status": "active"
      },
      {
        "id": 2,
        "name": "Cliente XYZ",
        "status": "active"
      }
    ],
    "statuses": [
      {
        "value": "pending",
        "label": "Pendente"
      },
      {
        "value": "published",
        "label": "Publicado"
      },
      {
        "value": "draft",
        "label": "Rascunho"
      },
      {
        "value": "archived",
        "label": "Arquivado"
      }
    ]
  }
}
```

---

## Códigos de Status HTTP

- **200 OK**: Requisição bem-sucedida
- **201 Created**: Recurso criado com sucesso
- **401 Unauthorized**: Token inválido ou ausente
- **403 Forbidden**: Usuário inativo
- **404 Not Found**: Recurso não encontrado
- **422 Unprocessable Entity**: Dados de entrada inválidos
- **500 Internal Server Error**: Erro interno do servidor

## Estrutura de Resposta Padrão

Todas as respostas seguem o padrão:

```json
{
  "success": boolean,
  "data": object|array,
  "message": "string", // Opcional
  "pagination": object, // Apenas para listas paginadas
  "errors": object // Apenas em caso de erro de validação
}
```

## Status de Updates

- `pending`: Pendente de aprovação
- `published`: Publicado e visível
- `draft`: Rascunho (não publicado)
- `archived`: Arquivado (não visível)

## Updates Globais

Updates globais (`is_global: true`) são atualizações que não estão vinculadas a um projeto ou cliente específico. Eles aparecem para todos os usuários e podem ser usados para anúncios gerais, manutenções do sistema, etc.

## Sistema de Hash

Cada update possui um hash único que permite acesso público sem autenticação. Isso é útil para:
- Compartilhar updates específicos com clientes
- Links públicos para atualizações
- Integração com sistemas externos

## Implementação em React

### Hook personalizado para updates

```javascript
// hooks/useUpdates.js
import { useState, useEffect } from 'react';
import axios from 'axios';

const API_BASE_URL = 'http://localhost:8000/api';

export const useUpdates = () => {
  const [loading, setLoading] = useState(false);
  const [updates, setUpdates] = useState([]);
  const [pagination, setPagination] = useState(null);
  const [error, setError] = useState(null);

  const fetchUpdates = async (params = {}) => {
    setLoading(true);
    setError(null);
    
    try {
      const token = localStorage.getItem('auth_token');
      const queryString = new URLSearchParams(params).toString();
      const url = `${API_BASE_URL}/updates${queryString ? `?${queryString}` : ''}`;
      
      const response = await axios.get(url, {
        headers: {
          'Authorization': `Bearer ${token}`
        }
      });

      if (response.data.success) {
        setUpdates(response.data.data);
        setPagination(response.data.pagination);
        return { success: true, data: response.data.data };
      }
    } catch (error) {
      const errorMessage = error.response?.data?.message || 'Erro ao carregar updates';
      setError(errorMessage);
      return { success: false, error: errorMessage };
    } finally {
      setLoading(false);
    }
  };

  const createUpdate = async (updateData) => {
    setLoading(true);
    setError(null);
    
    try {
      const token = localStorage.getItem('auth_token');
      const response = await axios.post(`${API_BASE_URL}/updates`, updateData, {
        headers: {
          'Authorization': `Bearer ${token}`
        }
      });

      if (response.data.success) {
        return { success: true, data: response.data.data };
      }
    } catch (error) {
      const errorData = error.response?.data;
      return { 
        success: false, 
        error: errorData?.message || 'Erro ao criar update',
        errors: errorData?.errors || {}
      };
    } finally {
      setLoading(false);
    }
  };

  const updateUpdate = async (id, updateData) => {
    setLoading(true);
    setError(null);
    
    try {
      const token = localStorage.getItem('auth_token');
      const response = await axios.put(`${API_BASE_URL}/updates/${id}`, updateData, {
        headers: {
          'Authorization': `Bearer ${token}`
        }
      });

      if (response.data.success) {
        return { success: true, data: response.data.data };
      }
    } catch (error) {
      const errorData = error.response?.data;
      return { 
        success: false, 
        error: errorData?.message || 'Erro ao atualizar update',
        errors: errorData?.errors || {}
      };
    } finally {
      setLoading(false);
    }
  };

  const deleteUpdate = async (id) => {
    setLoading(true);
    setError(null);
    
    try {
      const token = localStorage.getItem('auth_token');
      const response = await axios.delete(`${API_BASE_URL}/updates/${id}`, {
        headers: {
          'Authorization': `Bearer ${token}`
        }
      });

      if (response.data.success) {
        return { success: true };
      }
    } catch (error) {
      const errorMessage = error.response?.data?.message || 'Erro ao remover update';
      return { success: false, error: errorMessage };
    } finally {
      setLoading(false);
    }
  };

  const getUpdate = async (id) => {
    setLoading(true);
    setError(null);
    
    try {
      const token = localStorage.getItem('auth_token');
      const response = await axios.get(`${API_BASE_URL}/updates/${id}`, {
        headers: {
          'Authorization': `Bearer ${token}`
        }
      });

      if (response.data.success) {
        return { success: true, data: response.data.data };
      }
    } catch (error) {
      const errorMessage = error.response?.data?.message || 'Erro ao buscar update';
      return { success: false, error: errorMessage };
    } finally {
      setLoading(false);
    }
  };

  const getUpdateByHash = async (hash) => {
    setLoading(true);
    setError(null);
    
    try {
      // No authentication required for public hash access
      const response = await axios.get(`${API_BASE_URL}/updates/hash/${hash}`);

      if (response.data.success) {
        return { success: true, data: response.data.data };
      }
    } catch (error) {
      const errorMessage = error.response?.data?.message || 'Update não encontrado';
      return { success: false, error: errorMessage };
    } finally {
      setLoading(false);
    }
  };

  const getUpdateOptions = async () => {
    try {
      const token = localStorage.getItem('auth_token');
      const response = await axios.get(`${API_BASE_URL}/updates-options`, {
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
        error: error.response?.data?.message || 'Erro ao carregar opções' 
      };
    }
  };

  return {
    loading,
    updates,
    pagination,
    error,
    fetchUpdates,
    createUpdate,
    updateUpdate,
    deleteUpdate,
    getUpdate,
    getUpdateByHash,
    getUpdateOptions
  };
};
```

### Componente de Lista de Updates

```javascript
// components/UpdatesList.jsx
import React, { useEffect, useState } from 'react';
import { useUpdates } from '../hooks/useUpdates';

const UpdatesList = () => {
  const { 
    loading, 
    updates, 
    pagination, 
    error, 
    fetchUpdates,
    deleteUpdate 
  } = useUpdates();
  
  const [filters, setFilters] = useState({
    status: '',
    search: '',
    per_page: 15
  });

  useEffect(() => {
    fetchUpdates(filters);
  }, [filters]);

  const handleFilterChange = (key, value) => {
    setFilters(prev => ({
      ...prev,
      [key]: value,
      page: 1 // Reset to first page when filtering
    }));
  };

  const handlePageChange = (page) => {
    setFilters(prev => ({
      ...prev,
      page
    }));
  };

  const handleDelete = async (id) => {
    if (window.confirm('Tem certeza que deseja remover este update?')) {
      const result = await deleteUpdate(id);
      if (result.success) {
        fetchUpdates(filters); // Refresh list
      } else {
        alert(result.error);
      }
    }
  };

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

  return (
    <div className="space-y-6">
      {/* Filters */}
      <div className="bg-white p-4 rounded-lg shadow">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label className="block text-sm font-medium text-gray-700">
              Status
            </label>
            <select
              value={filters.status}
              onChange={(e) => handleFilterChange('status', e.target.value)}
              className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
            >
              <option value="">Todos</option>
              <option value="pending">Pendente</option>
              <option value="published">Publicado</option>
              <option value="draft">Rascunho</option>
              <option value="archived">Arquivado</option>
            </select>
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700">
              Buscar
            </label>
            <input
              type="text"
              value={filters.search}
              onChange={(e) => handleFilterChange('search', e.target.value)}
              placeholder="Buscar por título, descrição..."
              className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700">
              Por página
            </label>
            <select
              value={filters.per_page}
              onChange={(e) => handleFilterChange('per_page', parseInt(e.target.value))}
              className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
            >
              <option value={10}>10</option>
              <option value={15}>15</option>
              <option value={25}>25</option>
              <option value={50}>50</option>
            </select>
          </div>
        </div>
      </div>

      {/* Updates List */}
      <div className="bg-white shadow rounded-lg">
        <div className="px-4 py-5 sm:p-6">
          <h3 className="text-lg leading-6 font-medium text-gray-900 mb-4">
            Updates ({pagination?.total || 0})
          </h3>
          
          {updates.length === 0 ? (
            <p className="text-gray-500 text-center py-8">
              Nenhum update encontrado
            </p>
          ) : (
            <div className="space-y-4">
              {updates.map((update) => (
                <div key={update.id} className="border border-gray-200 rounded-lg p-4">
                  <div className="flex items-start justify-between">
                    <div className="flex-1">
                      <h4 className="text-lg font-medium text-gray-900">
                        {update.title}
                      </h4>
                      {update.caption && (
                        <p className="text-sm text-gray-600 mt-1">
                          {update.caption}
                        </p>
                      )}
                      <div className="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                        <span className={`px-2 py-1 rounded text-xs font-medium ${
                          update.status === 'published' ? 'bg-green-100 text-green-800' :
                          update.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                          update.status === 'draft' ? 'bg-gray-100 text-gray-800' :
                          'bg-red-100 text-red-800'
                        }`}>
                          {update.status}
                        </span>
                        <span>{update.views} visualizações</span>
                        <span>{new Date(update.created_at).toLocaleDateString('pt-BR')}</span>
                        {update.project && (
                          <span>Projeto: {update.project.name}</span>
                        )}
                        {update.customer && (
                          <span>Cliente: {update.customer.name}</span>
                        )}
                      </div>
                    </div>
                    
                    <div className="flex items-center space-x-2">
                      <button
                        onClick={() => window.open(`/updates/hash/${update.hash}`, '_blank')}
                        className="text-blue-600 hover:text-blue-800"
                      >
                        Ver
                      </button>
                      <button
                        onClick={() => handleDelete(update.id)}
                        className="text-red-600 hover:text-red-800"
                      >
                        Remover
                      </button>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>
      </div>

      {/* Pagination */}
      {pagination && pagination.last_page > 1 && (
        <div className="flex items-center justify-between">
          <div className="text-sm text-gray-700">
            Mostrando {pagination.from} a {pagination.to} de {pagination.total} resultados
          </div>
          
          <div className="flex space-x-2">
            <button
              onClick={() => handlePageChange(pagination.current_page - 1)}
              disabled={pagination.current_page === 1}
              className="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
            >
              Anterior
            </button>
            
            <span className="px-3 py-2 text-sm text-gray-700">
              Página {pagination.current_page} de {pagination.last_page}
            </span>
            
            <button
              onClick={() => handlePageChange(pagination.current_page + 1)}
              disabled={pagination.current_page === pagination.last_page}
              className="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
            >
              Próxima
            </button>
          </div>
        </div>
      )}
    </div>
  );
};

export default UpdatesList;
```

### Componente de Formulário de Update

```javascript
// components/UpdateForm.jsx
import React, { useState, useEffect } from 'react';
import { useUpdates } from '../hooks/useUpdates';

const UpdateForm = ({ updateId = null, onSuccess, onCancel }) => {
  const { 
    loading, 
    createUpdate, 
    updateUpdate, 
    getUpdate, 
    getUpdateOptions 
  } = useUpdates();
  
  const [formData, setFormData] = useState({
    project_id: '',
    customer_id: '',
    title: '',
    caption: '',
    description: '',
    status: 'pending',
    is_global: false
  });
  
  const [options, setOptions] = useState({
    projects: [],
    customers: [],
    statuses: []
  });
  
  const [errors, setErrors] = useState({});
  const [isEditMode, setIsEditMode] = useState(false);

  useEffect(() => {
    loadOptions();
    
    if (updateId) {
      setIsEditMode(true);
      loadUpdate(updateId);
    }
  }, [updateId]);

  const loadOptions = async () => {
    const result = await getUpdateOptions();
    if (result.success) {
      setOptions(result.data);
    }
  };

  const loadUpdate = async (id) => {
    const result = await getUpdate(id);
    if (result.success) {
      const update = result.data;
      setFormData({
        project_id: update.project?.id || '',
        customer_id: update.customer?.id || '',
        title: update.title,
        caption: update.caption || '',
        description: update.description || '',
        status: update.status,
        is_global: update.is_global
      });
    }
  };

  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: type === 'checkbox' ? checked : value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({});

    const submitData = {
      ...formData,
      project_id: formData.project_id || null,
      customer_id: formData.customer_id || null,
    };

    const result = isEditMode 
      ? await updateUpdate(updateId, submitData)
      : await createUpdate(submitData);

    if (result.success) {
      onSuccess?.(result.data);
    } else {
      if (result.errors) {
        setErrors(result.errors);
      } else {
        alert(result.error);
      }
    }
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-6">
      <div>
        <label className="block text-sm font-medium text-gray-700">
          Título *
        </label>
        <input
          type="text"
          name="title"
          value={formData.title}
          onChange={handleChange}
          className={`mt-1 block w-full border rounded-md shadow-sm ${
            errors.title ? 'border-red-300' : 'border-gray-300'
          }`}
          required
        />
        {errors.title && (
          <p className="mt-1 text-sm text-red-600">{errors.title[0]}</p>
        )}
      </div>

      <div>
        <label className="block text-sm font-medium text-gray-700">
          Legenda
        </label>
        <input
          type="text"
          name="caption"
          value={formData.caption}
          onChange={handleChange}
          className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
        />
      </div>

      <div>
        <label className="block text-sm font-medium text-gray-700">
          Descrição
        </label>
        <textarea
          name="description"
          value={formData.description}
          onChange={handleChange}
          rows={4}
          className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
        />
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label className="block text-sm font-medium text-gray-700">
            Projeto
          </label>
          <select
            name="project_id"
            value={formData.project_id}
            onChange={handleChange}
            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
          >
            <option value="">Selecione um projeto</option>
            {options.projects.map((project) => (
              <option key={project.id} value={project.id}>
                {project.name}
              </option>
            ))}
          </select>
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-700">
            Cliente
          </label>
          <select
            name="customer_id"
            value={formData.customer_id}
            onChange={handleChange}
            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
          >
            <option value="">Selecione um cliente</option>
            {options.customers.map((customer) => (
              <option key={customer.id} value={customer.id}>
                {customer.name}
              </option>
            ))}
          </select>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label className="block text-sm font-medium text-gray-700">
            Status
          </label>
          <select
            name="status"
            value={formData.status}
            onChange={handleChange}
            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
          >
            {options.statuses.map((status) => (
              <option key={status.value} value={status.value}>
                {status.label}
              </option>
            ))}
          </select>
        </div>

        <div className="flex items-center mt-6">
          <input
            type="checkbox"
            name="is_global"
            checked={formData.is_global}
            onChange={handleChange}
            className="h-4 w-4 text-blue-600 border-gray-300 rounded"
          />
          <label className="ml-2 block text-sm text-gray-700">
            Update global
          </label>
        </div>
      </div>

      <div className="flex justify-end space-x-3">
        <button
          type="button"
          onClick={onCancel}
          className="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
        >
          Cancelar
        </button>
        <button
          type="submit"
          disabled={loading}
          className="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50"
        >
          {loading ? 'Salvando...' : isEditMode ? 'Atualizar' : 'Criar'}
        </button>
      </div>
    </form>
  );
};

export default UpdateForm;
```

## Notas Importantes

1. **Autenticação**: Todos os endpoints requerem autenticação, exceto o acesso por hash

2. **Paginação**: Listas são paginadas por padrão (15 itens por página, máximo 100)

3. **Filtros**: Múltiplos filtros podem ser combinados nas listagens

4. **Hash Único**: Cada update possui um hash único para acesso público

5. **Updates Globais**: Updates sem projeto/cliente específico aparecem para todos

6. **Contador de Views**: Visualizações são incrementadas automaticamente

7. **Validação**: Campos obrigatórios e formatos são validados no backend
