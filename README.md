# Desafio Técnico - API de Cadastro de Usuários (Laravel + RabbitMQ)

## 🛠 Tecnologias
- **Framework:** Laravel 12 (PHP 8.2)
- **Banco de Dados:** PostgreSQL (Tabela `usuarios`)
- **Cache:** Redis (TTL de 300s)
- **Mensageria:** RabbitMQ (Fila `emails`)
- **Ambiente:** Docker & Docker Compose

---

## 🚀 Como Rodar o Projeto (Passo a Passo)

### 1. Preparação
```bash
# Clone o repositório e aceda à pasta
git clone https://github.com/SimshonHorie/api-cadastro-usuarios.git
cd api-cadastro-usuarios

# Configure as variáveis de ambiente
cp .env.example .env

2. Subir o Ambiente
O comando abaixo irá construir as imagens instalando as extensões necessárias (sockets, pdo_pgsql, redis) e iniciará todos os serviços (App, Banco, Redis, RabbitMQ e o Worker automático):

docker compose up -d --build

3. Configuração da Aplicação
Execute os comandos abaixo para preparar o banco de dados e as dependências:

# Instalar dependências do PHP
docker compose exec app composer install

# Gerar chave da aplicação
docker compose exec app php artisan key:generate

# Rodar as migrações (Cria a tabela 'usuarios' com campos bigserial, nome e email)
docker compose exec app php artisan migrate:fresh

Cadastro via API
Envie um POST para: http://localhost:8000/api/usuarios Payload:
{
  "nome": "Seu Nome",
  "email": "teste@exemplo.com"
}

Verificação do Log (E-mail Simulado)
O processamento da fila é automático. Assim que cadastrar um usuário, o worker processará o Job. Para ver o "e-mail" enviado no log, execute:

docker compose logs -f queue-worker
# OU verifique o arquivo de log diretamente:
docker compose exec app tail -f storage/logs/laravel.log

Painel do RabbitMQ
Acompanhe a fila emails em tempo real:

URL: http://localhost:15672

Login: guest | Senha: guest