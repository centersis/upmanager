#!/usr/bin/env node

import { Server } from '@modelcontextprotocol/sdk/server/index.js';
import { StdioServerTransport } from '@modelcontextprotocol/sdk/server/stdio.js';
import {
  CallToolRequestSchema,
  ListToolsRequestSchema,
} from '@modelcontextprotocol/sdk/types.js';
import Docker from 'dockerode';

// Configuração do Docker
const docker = new Docker();

// Interface para resposta de containers
interface ContainerInfo {
  id: string;
  name: string;
  image: string;
  status: string;
  ports: string[];
  created: string;
}

// Interface para resposta de imagens
interface ImageInfo {
  id: string;
  repository: string;
  tag: string;
  size: string;
  created: string;
}

// Servidor MCP
const server = new Server(
  {
    name: 'docker-mcp-server',
    version: '1.0.0',
  },
  {
    capabilities: {
      tools: {},
    },
  }
);

// Listar ferramentas disponíveis
server.setRequestHandler(ListToolsRequestSchema, async () => {
  return {
    tools: [
      {
        name: 'list_containers',
        description: 'Lista todos os containers Docker (rodando e parados)',
        inputSchema: {
          type: 'object',
          properties: {
            all: {
              type: 'boolean',
              description: 'Incluir containers parados (padrão: true)',
              default: true,
            },
          },
        },
      },
      {
        name: 'list_images',
        description: 'Lista todas as imagens Docker disponíveis',
        inputSchema: {
          type: 'object',
          properties: {},
        },
      },
      {
        name: 'start_container',
        description: 'Inicia um container Docker',
        inputSchema: {
          type: 'object',
          properties: {
            containerId: {
              type: 'string',
              description: 'ID ou nome do container',
            },
          },
          required: ['containerId'],
        },
      },
      {
        name: 'stop_container',
        description: 'Para um container Docker',
        inputSchema: {
          type: 'object',
          properties: {
            containerId: {
              type: 'string',
              description: 'ID ou nome do container',
            },
          },
          required: ['containerId'],
        },
      },
      {
        name: 'restart_container',
        description: 'Reinicia um container Docker',
        inputSchema: {
          type: 'object',
          properties: {
            containerId: {
              type: 'string',
              description: 'ID ou nome do container',
            },
          },
          required: ['containerId'],
        },
      },
      {
        name: 'get_container_logs',
        description: 'Obtém logs de um container Docker',
        inputSchema: {
          type: 'object',
          properties: {
            containerId: {
              type: 'string',
              description: 'ID ou nome do container',
            },
            tail: {
              type: 'number',
              description: 'Número de linhas de log para retornar (padrão: 100)',
              default: 100,
            },
          },
          required: ['containerId'],
        },
      },
      {
        name: 'build_image',
        description: 'Constrói uma imagem Docker',
        inputSchema: {
          type: 'object',
          properties: {
            dockerfile: {
              type: 'string',
              description: 'Caminho para o Dockerfile (padrão: ./Dockerfile)',
              default: './Dockerfile',
            },
            tag: {
              type: 'string',
              description: 'Tag para a imagem (padrão: upmanager:latest)',
              default: 'upmanager:latest',
            },
            context: {
              type: 'string',
              description: 'Contexto de build (padrão: .)',
              default: '.',
            },
          },
        },
      },
      {
        name: 'run_container',
        description: 'Executa um novo container Docker',
        inputSchema: {
          type: 'object',
          properties: {
            image: {
              type: 'string',
              description: 'Nome da imagem',
            },
            name: {
              type: 'string',
              description: 'Nome do container',
            },
            ports: {
              type: 'object',
              description: 'Mapeamento de portas (ex: {"8000": "8000"})',
            },
            environment: {
              type: 'object',
              description: 'Variáveis de ambiente',
            },
            volumes: {
              type: 'object',
              description: 'Mapeamento de volumes (ex: {"/host/path": "/container/path"})',
            },
          },
          required: ['image'],
        },
      },
      {
        name: 'remove_container',
        description: 'Remove um container Docker',
        inputSchema: {
          type: 'object',
          properties: {
            containerId: {
              type: 'string',
              description: 'ID ou nome do container',
            },
            force: {
              type: 'boolean',
              description: 'Forçar remoção mesmo se estiver rodando',
              default: false,
            },
          },
          required: ['containerId'],
        },
      },
      {
        name: 'remove_image',
        description: 'Remove uma imagem Docker',
        inputSchema: {
          type: 'object',
          properties: {
            imageId: {
              type: 'string',
              description: 'ID da imagem',
            },
            force: {
              type: 'boolean',
              description: 'Forçar remoção mesmo se estiver em uso',
              default: false,
            },
          },
          required: ['imageId'],
        },
      },
      {
        name: 'docker_compose_up',
        description: 'Executa docker-compose up para o projeto Laravel',
        inputSchema: {
          type: 'object',
          properties: {
            detach: {
              type: 'boolean',
              description: 'Executar em background (padrão: true)',
              default: true,
            },
            build: {
              type: 'boolean',
              description: 'Reconstruir imagens antes de iniciar',
              default: false,
            },
          },
        },
      },
      {
        name: 'docker_compose_down',
        description: 'Para e remove containers do docker-compose',
        inputSchema: {
          type: 'object',
          properties: {
            volumes: {
              type: 'boolean',
              description: 'Remover volumes também',
              default: false,
            },
          },
        },
      },
      {
        name: 'docker_compose_logs',
        description: 'Mostra logs do docker-compose',
        inputSchema: {
          type: 'object',
          properties: {
            service: {
              type: 'string',
              description: 'Nome do serviço específico (opcional)',
            },
            tail: {
              type: 'number',
              description: 'Número de linhas de log',
              default: 100,
            },
          },
        },
      },
    ],
  };
});

// Manipulador de chamadas de ferramentas
server.setRequestHandler(CallToolRequestSchema, async (request) => {
  const { name, arguments: args } = request.params;

  try {
    switch (name) {
      case 'list_containers': {
        const containers = await docker.listContainers({ all: args.all ?? true });
        const containerInfo: ContainerInfo[] = containers.map(container => ({
          id: container.Id.substring(0, 12),
          name: container.Names[0]?.replace('/', '') || 'sem nome',
          image: container.Image,
          status: container.Status,
          ports: container.Ports.map(port => `${port.PrivatePort}:${port.PublicPort}`),
          created: new Date(container.Created * 1000).toISOString(),
        }));

        return {
          content: [
            {
              type: 'text',
              text: `## Containers Docker\n\n${containerInfo.map(c => 
                `**${c.name}** (${c.id})\n` +
                `- Imagem: ${c.image}\n` +
                `- Status: ${c.status}\n` +
                `- Portas: ${c.ports.join(', ') || 'Nenhuma'}\n` +
                `- Criado: ${c.created}\n`
              ).join('\n')}`
            }
          ],
        };
      }

      case 'list_images': {
        const images = await docker.listImages();
        const imageInfo: ImageInfo[] = images.map(image => ({
          id: image.Id.substring(0, 12),
          repository: image.RepoTags?.[0]?.split(':')[0] || '<none>',
          tag: image.RepoTags?.[0]?.split(':')[1] || '<none>',
          size: `${Math.round(image.Size / 1024 / 1024)}MB`,
          created: new Date(image.Created * 1000).toISOString(),
        }));

        return {
          content: [
            {
              type: 'text',
              text: `## Imagens Docker\n\n${imageInfo.map(i => 
                `**${i.repository}:${i.tag}** (${i.id})\n` +
                `- Tamanho: ${i.size}\n` +
                `- Criada: ${i.created}\n`
              ).join('\n')}`
            }
          ],
        };
      }

      case 'start_container': {
        const container = docker.getContainer(args.containerId);
        await container.start();
        return {
          content: [
            {
              type: 'text',
              text: `✅ Container ${args.containerId} iniciado com sucesso!`,
            }
          ],
        };
      }

      case 'stop_container': {
        const container = docker.getContainer(args.containerId);
        await container.stop();
        return {
          content: [
            {
              type: 'text',
              text: `🛑 Container ${args.containerId} parado com sucesso!`,
            }
          ],
        };
      }

      case 'restart_container': {
        const container = docker.getContainer(args.containerId);
        await container.restart();
        return {
          content: [
            {
              type: 'text',
              text: `🔄 Container ${args.containerId} reiniciado com sucesso!`,
            }
          ],
        };
      }

      case 'get_container_logs': {
        const container = docker.getContainer(args.containerId);
        const logs = await container.logs({
          stdout: true,
          stderr: true,
          tail: args.tail || 100,
        });
        
        return {
          content: [
            {
              type: 'text',
              text: `## Logs do Container ${args.containerId}\n\n\`\`\`\n${logs.toString()}\n\`\`\``,
            }
          ],
        };
      }

      case 'build_image': {
        const stream = await docker.buildImage({
          context: args.context || '.',
          src: [args.dockerfile || './Dockerfile'],
        }, {
          t: args.tag || 'upmanager:latest',
        });

        return new Promise((resolve, reject) => {
          let output = '';
          stream.on('data', (chunk) => {
            output += chunk.toString();
          });
          
          stream.on('end', () => {
            resolve({
              content: [
                {
                  type: 'text',
                  text: `✅ Imagem construída com sucesso!\n\n**Output do build:**\n\`\`\`\n${output}\n\`\`\``,
                }
              ],
            });
          });
          
          stream.on('error', reject);
        });
      }

      case 'run_container': {
        const container = await docker.createContainer({
          Image: args.image,
          name: args.name,
          ExposedPorts: args.ports ? Object.keys(args.ports).reduce((acc, key) => {
            acc[`${key}/tcp`] = {};
            return acc;
          }, {} as any) : {},
          Env: args.environment ? Object.entries(args.environment).map(([key, value]) => `${key}=${value}`) : [],
          Volumes: args.volumes ? Object.keys(args.volumes).reduce((acc, key) => {
            acc[key] = {};
            return acc;
          }, {} as any) : {},
          HostConfig: {
            PortBindings: args.ports ? Object.entries(args.ports).reduce((acc, [key, value]) => {
              acc[`${key}/tcp`] = [{ HostPort: value.toString() }];
              return acc;
            }, {} as any) : {},
            Binds: args.volumes ? Object.entries(args.volumes).map(([host, container]) => `${host}:${container}`) : [],
          },
        });

        await container.start();
        
        return {
          content: [
            {
              type: 'text',
              text: `✅ Container criado e iniciado com sucesso!\n- ID: ${container.id.substring(0, 12)}\n- Nome: ${args.name || 'sem nome'}`,
            }
          ],
        };
      }

      case 'remove_container': {
        const container = docker.getContainer(args.containerId);
        await container.remove({ force: args.force || false });
        return {
          content: [
            {
              type: 'text',
              text: `🗑️ Container ${args.containerId} removido com sucesso!`,
            }
          ],
        };
      }

      case 'remove_image': {
        await docker.getImage(args.imageId).remove({ force: args.force || false });
        return {
          content: [
            {
              type: 'text',
              text: `🗑️ Imagem ${args.imageId} removida com sucesso!`,
            }
          ],
        };
      }

      case 'docker_compose_up': {
        const { exec } = await import('child_process');
        const { promisify } = await import('util');
        const execAsync = promisify(exec);
        
        const buildFlag = args.build ? '--build' : '';
        const detachFlag = args.detach ? '-d' : '';
        
        const { stdout, stderr } = await execAsync(`docker-compose up ${detachFlag} ${buildFlag}`);
        
        return {
          content: [
            {
              type: 'text',
              text: `✅ Docker Compose executado com sucesso!\n\n**Output:**\n\`\`\`\n${stdout}\n${stderr}\n\`\`\``,
            }
          ],
        };
      }

      case 'docker_compose_down': {
        const { exec } = await import('child_process');
        const { promisify } = await import('util');
        const execAsync = promisify(exec);
        
        const volumesFlag = args.volumes ? '-v' : '';
        
        const { stdout, stderr } = await execAsync(`docker-compose down ${volumesFlag}`);
        
        return {
          content: [
            {
              type: 'text',
              text: `🛑 Docker Compose parado com sucesso!\n\n**Output:**\n\`\`\`\n${stdout}\n${stderr}\n\`\`\``,
            }
          ],
        };
      }

      case 'docker_compose_logs': {
        const { exec } = await import('child_process');
        const { promisify } = await import('util');
        const execAsync = promisify(exec);
        
        const serviceFlag = args.service ? args.service : '';
        const tailFlag = `--tail ${args.tail || 100}`;
        
        const { stdout, stderr } = await execAsync(`docker-compose logs ${tailFlag} ${serviceFlag}`);
        
        return {
          content: [
            {
              type: 'text',
              text: `## Logs do Docker Compose\n\n\`\`\`\n${stdout}\n${stderr}\n\`\`\``,
            }
          ],
        };
      }

      default:
        throw new Error(`Ferramenta desconhecida: ${name}`);
    }
  } catch (error) {
    return {
      content: [
        {
          type: 'text',
          text: `❌ Erro ao executar ${name}: ${error instanceof Error ? error.message : String(error)}`,
        }
      ],
      isError: true,
    };
  }
});

// Iniciar servidor
async function main() {
  const transport = new StdioServerTransport();
  await server.connect(transport);
  console.error('Servidor MCP Docker iniciado');
}

main().catch(console.error);
