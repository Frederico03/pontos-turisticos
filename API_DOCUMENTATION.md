# 📚 API Documentation - Turismo

## Endpoints Implementados

### 🗺️ Pontos Turísticos

#### Listar Pontos
```http
GET /pontos
```

**Query Parameters:**
- `search` (string): Busca por nome, descrição ou cidade
- `cidade` (string): Filtrar por cidade
- `estado` (string): Filtrar por estado (sigla)
- `nota_minima` (decimal): Nota mínima
- `order_by` (string): Campo para ordenação (default: created_at)
- `order` (string): Direção (asc|desc, default: desc)
- `per_page` (int): Itens por página (default: 15)

**Resposta:**
```json
{
  "data": {
    "current_page": 1,
    "data": [...],
    "total": 50
  },
  "filters": {
    "estados": ["GO", "SP", "RJ"],
    "cidades": ["Goiânia", "São Paulo"]
  }
}
```

---

#### Criar Ponto
```http
POST /pontos
Authorization: Bearer {token}
```

**Body:**
```json
{
  "nome": "Parque Vaca Brava",
  "descricao": "Um dos parques mais bonitos de Goiânia",
  "cidade": "Goiânia",
  "estado": "GO",
  "pais": "Brasil",
  "latitude": -16.716,
  "longitude": -49.261,
  "endereco": "Setor Bueno, Goiânia - GO"
}
```

---

#### Visualizar Ponto
```http
GET /pontos/{id}
```

**Resposta:**
```json
{
  "data": {
    "id": 1,
    "nome": "Parque Vaca Brava",
    "descricao": "...",
    "nota_media": 4.5,
    "criador": {...},
    "avaliacoes": [...],
    "hospedagens": [...]
  },
  "is_favorited": true
}
```

---

#### Atualizar Ponto
```http
PUT /pontos/{id}
Authorization: Bearer {token}
```

**Body:** (campos opcionais)
```json
{
  "nome": "Novo nome",
  "descricao": "Nova descrição"
}
```

---

#### Deletar Ponto
```http
DELETE /pontos/{id}
Authorization: Bearer {token}
```

---

#### Buscar Pontos Próximos (Geolocalização)
```http
GET /pontos-proximos?latitude=-16.716&longitude=-49.261&raio=50
```

**Query Parameters:**
- `latitude` (required, decimal): Latitude do centro
- `longitude` (required, decimal): Longitude do centro
- `raio` (optional, int): Raio em km (default: 50, max: 500)

**Resposta:**
```json
{
  "data": [
    {
      "id": 1,
      "nome": "Parque Vaca Brava",
      "distancia": 2.5,
      ...
    }
  ],
  "centro": {
    "latitude": -16.716,
    "longitude": -49.261
  },
  "raio_km": 50
}
```

---

#### Favoritar/Desfavoritar
```http
POST /pontos/{id}/favoritar
Authorization: Bearer {token}
```

**Resposta:**
```json
{
  "message": "Ponto adicionado aos favoritos!",
  "is_favorited": true
}
```

---

#### Meus Favoritos
```http
GET /meus-favoritos
Authorization: Bearer {token}
```

---

### 💬 Comentários (MongoDB)

#### Listar Comentários de um Ponto
```http
GET /pontos/{ponto_id}/comentarios
```

**Query Parameters:**
- `limite` (int): Máximo de comentários (default: 50)

**Resposta:**
```json
{
  "data": [
    {
      "id": "507f1f77bcf86cd799439011",
      "pontoId": "1",
      "usuarioId": "123",
      "texto": "Lugar incrível!",
      "createdAt": "2024-01-15 10:30:00",
      "respostas": [...]
    }
  ],
  "ponto": {
    "id": 1,
    "nome": "Parque Vaca Brava"
  }
}
```

---

#### Criar Comentário
```http
POST /pontos/{ponto_id}/comentarios
Authorization: Bearer {token}
```

**Body:**
```json
{
  "texto": "Adorei este lugar!",
  "metadata": {
    "language": "pt",
    "device": "mobile"
  }
}
```

**Validação:**
- `texto`: min:3, max:500 caracteres
- Não pode ser vazio

---

#### Responder Comentário
```http
POST /comentarios/{comentario_id}/responder
Authorization: Bearer {token}
```

**Body:**
```json
{
  "texto": "Concordo totalmente!"
}
```

---

#### Deletar Comentário
```http
DELETE /comentarios/{comentario_id}
Authorization: Bearer {token}
```

**Permissões:** Apenas o autor ou administrador

---

### ⭐ Avaliações

#### Listar Avaliações de um Ponto
```http
GET /pontos/{ponto_id}/avaliacoes
```

**Query Parameters:**
- `per_page` (int): Itens por página (default: 10)

---

#### Criar Avaliação
```http
POST /pontos/{ponto_id}/avaliacoes
Authorization: Bearer {token}
```

**Body:**
```json
{
  "nota": 5,
  "comentario": "Experiência maravilhosa!"
}
```

**Validação:**
- `nota`: obrigatória, entre 1 e 5
- `comentario`: opcional, máx 1000 caracteres
- **Regra:** Um usuário só pode avaliar cada ponto UMA vez

---

#### Atualizar Minha Avaliação
```http
PUT /avaliacoes/{avaliacao_id}
Authorization: Bearer {token}
```

**Body:**
```json
{
  "nota": 4,
  "comentario": "Na verdade é muito bom, não excelente"
}
```

---

#### Deletar Avaliação
```http
DELETE /avaliacoes/{avaliacao_id}
Authorization: Bearer {token}
```

---

#### Ver Minha Avaliação para um Ponto
```http
GET /pontos/{ponto_id}/minha-avaliacao
Authorization: Bearer {token}
```

**Resposta (se não avaliou):**
```json
{
  "message": "Você ainda não avaliou este ponto.",
  "has_review": false
}
```

---

### 🏨 Hospedagens

#### Listar Hospedagens
```http
GET /hospedagens
```

**Query Parameters:**
- `tipo` (string): hotel, pousada, hostel, resort, apartamento
- `ponto_id` (int): Filtrar por ponto turístico
- `preco_min` (decimal): Preço mínimo da diária
- `preco_max` (decimal): Preço máximo da diária
- `order_by` (string): Campo para ordenação
- `per_page` (int): Itens por página (default: 15)

---

#### Listar Hospedagens de um Ponto
```http
GET /pontos/{ponto_id}/hospedagens
```

**Query Parameters:**
- `tipo` (string): Filtrar por tipo
- `per_page` (int): Itens por página (default: 10)

---

#### Criar Hospedagem
```http
POST /pontos/{ponto_id}/hospedagens
Authorization: Bearer {token}
```

**Body:**
```json
{
  "nome": "Hotel Exemplo",
  "tipo": "hotel",
  "descricao": "Hotel confortável próximo ao parque",
  "endereco": "Rua Exemplo, 123",
  "preco_diaria": 150.00,
  "nota_avaliacao": 4.5,
  "amenidades": ["wifi", "cafe-da-manha", "piscina"],
  "contato": "(62) 3333-4444",
  "site": "https://hotelexemplo.com.br"
}
```

**Validação:**
- `nome`: obrigatório, máx 255 caracteres
- `tipo`: obrigatório, valores: hotel, pousada, hostel, resort, apartamento
- `endereco`: obrigatório
- `preco_diaria`: obrigatório, >= 0
- `nota_avaliacao`: opcional, entre 0 e 5

---

#### Visualizar Hospedagem
```http
GET /hospedagens/{id}
```

---

#### Atualizar Hospedagem
```http
PUT /hospedagens/{id}
Authorization: Bearer {token}
```

---

#### Deletar Hospedagem
```http
DELETE /hospedagens/{id}
Authorization: Bearer {token}
```

---

## 🔐 Autenticação

A maioria dos endpoints de criação, edição e deleção requerem autenticação.

**Headers necessários:**
```http
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json
```

---

## 📊 Códigos de Resposta

- `200 OK`: Sucesso
- `201 Created`: Recurso criado com sucesso
- `400 Bad Request`: Dados inválidos
- `401 Unauthorized`: Não autenticado
- `403 Forbidden`: Sem permissão
- `404 Not Found`: Recurso não encontrado
- `422 Unprocessable Entity`: Validação falhou
- `500 Internal Server Error`: Erro do servidor

---

## 🎯 Features Especiais

### 1. **Busca Geográfica (Haversine)**
A busca por pontos próximos usa a fórmula de Haversine para calcular distâncias precisas com base em coordenadas geográficas.

### 2. **MongoDB para Comentários**
Comentários são armazenados no MongoDB para melhor performance com dados não estruturados e suporte a respostas aninhadas.

### 3. **Atualização Automática de Média**
Quando uma avaliação é criada, atualizada ou deletada, a `nota_media` do ponto turístico é automaticamente recalculada.

### 4. **Validação com Form Requests**
Todas as entradas são validadas usando Form Requests dedicados com mensagens de erro personalizadas em português.

### 5. **Suporte JSON e Views**
Todos os endpoints suportam tanto requisições JSON (API) quanto retorno de views (Web).

---

## 🧪 Exemplos de Uso

### Exemplo: Buscar pontos em Goiânia com nota >= 4
```http
GET /pontos?cidade=Goiânia&nota_minima=4&per_page=20
```

### Exemplo: Criar ponto e adicionar hospedagem
```bash
# 1. Criar ponto
POST /pontos
{
  "nome": "Praça Cívica",
  "cidade": "Goiânia",
  ...
}

# 2. Adicionar hospedagem (assumindo que o ponto tem id=5)
POST /pontos/5/hospedagens
{
  "nome": "Hotel Centro",
  "tipo": "hotel",
  ...
}
```

### Exemplo: Comentar e responder
```bash
# 1. Criar comentário
POST /pontos/1/comentarios
{
  "texto": "Lugar fantástico para passear!"
}

# Resposta: { "data": { "id": "507f..." } }

# 2. Responder ao comentário
POST /comentarios/507f.../responder
{
  "texto": "Concordo!"
}
```

---

## 📝 Notas Importantes

1. **Paginação**: Todos os endpoints de listagem suportam paginação
2. **Soft Deletes**: Alguns modelos podem usar soft deletes
3. **Transações**: Operações críticas usam transações de banco de dados
4. **Eager Loading**: Relacionamentos são carregados com `with()` para evitar N+1
5. **Autorização**: Políticas (Policies) são usadas para controle de acesso
