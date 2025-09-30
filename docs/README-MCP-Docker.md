# Docker MCP Server

Este servidor MCP (Model Context Protocol) permite interagir facilmente com Docker através de ferramentas especializadas.

## Funcionalidades Disponíveis

### Gerenciamento de Containers
- **list_containers**: Lista todos os containers (rodando e parados)
- **start_container**: Inicia um container
- **stop_container**: Para um container
- **restart_container**: Reinicia um container
- **get_container_logs**: Obtém logs de um container
- **remove_container**: Remove um container

### Gerenciamento de Imagens
- **list_images**: Lista todas as imagens Docker
- **build_image**: Constrói uma nova imagem
- **remove_image**: Remove uma imagem

### Operações de Container
- **run_container**: Executa um novo container com configurações personalizadas

### Docker Compose (Laravel Sail)
- **docker_compose_up**: Executa `docker-compose up`
- **docker_compose_down**: Executa `docker-compose down`
- **docker_compose_logs**: Mostra logs do docker-compose

## Como Usar

### 1. Instalação
```bash
npm install
```

### 2. Executar o Servidor MCP
```bash
npm run mcp-docker
```

### 3. Configuração no Cursor
Adicione ao seu arquivo de configuração MCP:

```json
{
  "mcpServers": {
    "docker": {
      "command": "npm",
      "args": ["run", "mcp-docker"],
      "cwd": "/caminho/para/seu/projeto"
    }
  }
}
```

## Exemplos de Uso

### Listar Containers
```
Liste todos os containers Docker
```

### Iniciar Laravel Sail
```
Execute docker-compose up para iniciar o ambiente Laravel
```

### Ver Logs do Laravel
```
Mostre os logs do docker-compose para o serviço laravel.test
```

### Construir Imagem
```
Construa uma nova imagem Docker usando o Dockerfile atual
```

## Requisitos

- Docker instalado e rodando
- Node.js 18+ 
- npm ou yarn

## Permissões

O servidor precisa de acesso ao socket do Docker. Certifique-se de que:
- Docker está rodando
- Usuário tem permissões para acessar `/var/run/docker.sock`
- Ou configure `DOCKER_HOST` apropriadamente

## Troubleshooting

### Erro de Permissão
```bash
sudo chmod 666 /var/run/docker.sock
```

### Docker não encontrado
```bash
# Verificar se Docker está rodando
docker ps
```

### Servidor MCP não conecta
- Verifique se o comando está correto no arquivo de configuração
- Certifique-se de que o caminho do projeto está correto
- Verifique se todas as dependências estão instaladas
