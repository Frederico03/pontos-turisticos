https://youtu.be/4QFR8x44qYU
# Sistema de Turismo e Viagens

Este projeto consiste em um sistema web completo (Backend + Frontend) para o gerenciamento e consulta de pontos turísticos, desenvolvido para atender a requisitos robustos de persistência poliglota e escalabilidade.

## 🚀 Tecnologias Utilizadas

A stack tecnológica foi escolhida para alinhar produtividade com alta performance:

- **Backend**: [Laravel](https://laravel.com) (PHP)
  - Escolhido pela sua robustez, segurança e facilidade de integração com múltiplos bancos de dados.
- **Frontend**: Blade Templates + Vue.js (via [Vite](https://vitejs.dev))
  - Interface dinâmica e responsiva com TailwindCSS.
- **Containerização**: [Docker](https://www.docker.com) & Docker Compose
  - Ambiente de desenvolvimento isolado e reproduzível.

### 💾 Persistência de Dados (Abordagem Híbrida)

O sistema utiliza uma abordagem de **Persistência Poliglota** para otimizar o armazenamento de acordo com a natureza do dado:

1.  **PostgreSQL (Relacional)**:
    - Entidades estruturadas e críticas: `Pontos Turísticos`, `Usuários`, `Hospedagens`.
    - Garante integridade referencial e consistência (ACID).
    
2.  **MongoDB (NoSQL)**:
    - Dados flexíveis e volumosos: `Comentários` (com suporte a aninhamento) e Metadados de `Fotos`.
    - Permite alta escalabilidade para dados não estruturados.

3.  **Redis (Chave-Valor)**:
    - **Cache**: Armazena sessões e dados acessados frequentemente (ex: listagem de pontos) para reduzir a carga no banco principal e reduzir latência.

4.  **Filesystem (Disco)**:
    - Armazenamento físico das imagens (via Docker Volumes), com referências salvas no MongoDB.

---

## 📋 Funcionalidades Implementadas

### Requisitos Funcionais
- [x] **Autenticação**: Login e Registro de usuários (Laravel Breeze).
- [x] **CRUD de Pontos Turísticos**: Gerenciamento completo (Nome, Descrição, Geolocalização).
- [x] **Upload de Fotos**: Imagens salvas em disco com metadados no MongoDB.
- [x] **Avaliações e Comentários**: Sistema híbrido onde avaliações (notas) podem impactar métricas relacionais, enquanto comentários detalhados residem no NoSQL.
- [x] **Hospedagens**: Associação de locais de estadia aos pontos turísticos.
- [x] **Geolocalização**: Armazenamento de Latitude/Longitude.

### Requisitos Não Funcionais
- **Execução via Docker**: Setup completo com um único comando.
- **Performance**: Uso de filas (Queue) e Cache (Redis).

---

## 🛠️ Como Executar o Projeto

Certifique-se de ter o **Docker** e **Docker Compose** instalados.

### Passo Rápido (Automático)

Utilize o script de inicialização preparado para seu sistema operacional. Ele irá subir os containers, instalar dependências do PHP (Composer), rodar migrações e iniciar o servidor frontend.

**Windows:**
```powershell
.\start.bat
```

**Linux / Mac / WSL:**
```bash
chmod +x start.sh
./start.sh
```

### Passo Manual (Caso prefira)

1. **Subir Containers**:
   ```bash
   docker compose up --build -d
   ```

2. **Instalar Dependências e Configurar Banco**:
   ```bash
   docker exec -it turismo-app bash -c "composer install && php artisan migrate:fresh --seed && php artisan key:generate"
   ```

3. **Iniciar Frontend (Local)**:
   ```bash
   npm run dev
   ```

O sistema estará acessível em: `http://localhost:8000`

---

## 📂 Estrutura de Pastas Chave

- `app/Models`: Modelos Eloquent (Postgres) e Moloquent (MongoDB).
- `docker/`: Configurações de infraestrutura (Nginx, PHP).
- `docker-compose.yml`: Orquestração dos serviços (App, DBs, Cache).
- `routes/web.php`: Rotas da aplicação.

## 📝 Decisões de Projeto

- **Laravel vs Java**: Optou-se pelo Laravel devido à sua sintaxe expressiva e ecossistema rico (Eloquent, Sail, Breeze) que acelera o desenvolvimento de aplicações complexas sem sacrificar a robustez exigida para integrações com múltiplos SGBDs.
- **Redis para Cache**: Implementado para mitigar gargalos de performance em consultas repetitivas de leitura.
