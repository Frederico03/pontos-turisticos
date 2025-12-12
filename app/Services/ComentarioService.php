<?php

namespace App\Services;

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class ComentarioService extends MongoDBService
{
    public function __construct()
    {
        parent::__construct();
        $this->setCollection('comentarios');
    }

    /**
     * Criar um novo comentário
     */
    public function criar(int $pontoId, int $usuarioId, string $texto, array $metadata = []): array
    {
        // Validação: máximo 500 caracteres
        if (strlen($texto) > 500) {
            throw new \InvalidArgumentException('Comentário não pode ter mais de 500 caracteres');
        }

        if (empty(trim($texto))) {
            throw new \InvalidArgumentException('Comentário não pode ser vazio');
        }

        $documento = [
            'pontoId' => (string) $pontoId,
            'usuarioId' => (string) $usuarioId,
            'texto' => $texto,
            'createdAt' => new UTCDateTime(),
            'metadata' => array_merge([
                'language' => 'pt',
                'device' => 'web'
            ], $metadata),
            'respostas' => []
        ];

        $result = $this->getCollection()->insertOne($documento);
        $documento['_id'] = (string) $result->getInsertedId();

        return $documento;
    }

    /**
     * Listar comentários de um ponto turístico
     * Ordenados por data (mais recentes primeiro)
     */
    public function listarPorPonto(int $pontoId, int $limite = 50): array
    {
        $cursor = $this->getCollection()->find(
            ['pontoId' => (string) $pontoId],
            [
                'sort' => ['createdAt' => -1],
                'limit' => $limite
            ]
        );

        $comentarios = [];
        foreach ($cursor as $documento) {
            $comentarios[] = $this->formatarDocumento($documento);
        }

        return $comentarios;
    }

    /**
     * Adicionar resposta a um comentário
     */
    public function adicionarResposta(string $comentarioId, int $usuarioId, string $texto): bool
    {
        if (strlen($texto) > 500) {
            throw new \InvalidArgumentException('Resposta não pode ter mais de 500 caracteres');
        }

        $resposta = [
            'usuarioId' => (string) $usuarioId,
            'texto' => $texto,
            'data' => (new \DateTime())->format('Y-m-d\TH:i:s\Z')
        ];

        $result = $this->getCollection()->updateOne(
            ['_id' => new ObjectId($comentarioId)],
            ['$push' => ['respostas' => $resposta]]
        );

        return $result->getModifiedCount() > 0;
    }

    /**
     * Deletar comentário
     */
    public function deletar(string $comentarioId): bool
    {
        $result = $this->getCollection()->deleteOne([
            '_id' => new ObjectId($comentarioId)
        ]);

        return $result->getDeletedCount() > 0;
    }

    /**
     * Buscar comentário por ID
     */
    public function buscarPorId(string $comentarioId): ?array
    {
        $documento = $this->getCollection()->findOne([
            '_id' => new ObjectId($comentarioId)
        ]);

        return $documento ? $this->formatarDocumento($documento) : null;
    }

    /**
     * Formata o documento do MongoDB para array
     */
    private function formatarDocumento($documento): array
    {
        return [
            'id' => (string) $documento['_id'],
            'pontoId' => $documento['pontoId'],
            'usuarioId' => $documento['usuarioId'],
            'texto' => $documento['texto'],
            'createdAt' => $documento['createdAt']->toDateTime()->format('Y-m-d H:i:s'),
            'metadata' => $documento['metadata'] ?? [],
            'respostas' => $documento['respostas'] ?? []
        ];
    }
}
