<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class FotoService extends MongoDBService
{
    private const MAX_FOTOS_POR_PONTO = 10;
    private const EXTENSOES_PERMITIDAS = ['jpg', 'jpeg', 'png', 'webp'];
    private const TAMANHO_MAXIMO = 5120; // 5MB em KB

    public function __construct()
    {
        parent::__construct();
        $this->setCollection('fotos');
    }

    /**
     * Upload de foto
     */
    public function upload(int $pontoId, int $usuarioId, UploadedFile $arquivo, ?string $titulo = null): array
    {
        // Validar limite de fotos
        $countFotos = $this->contarFotosPorPonto($pontoId);
        if ($countFotos >= self::MAX_FOTOS_POR_PONTO) {
            throw new \Exception("Este ponto turístico já possui o máximo de " . self::MAX_FOTOS_POR_PONTO . " fotos");
        }

        // Validar extensão
        $extensao = strtolower($arquivo->getClientOriginalExtension());
        if (!in_array($extensao, self::EXTENSOES_PERMITIDAS)) {
            throw new \InvalidArgumentException('Extensão de arquivo não permitida. Use: ' . implode(', ', self::EXTENSOES_PERMITIDAS));
        }

        // Validar tamanho
        if ($arquivo->getSize() > self::TAMANHO_MAXIMO * 1024) {
            throw new \InvalidArgumentException('Arquivo muito grande. Tamanho máximo: ' . self::TAMANHO_MAXIMO . 'KB');
        }

        // Gerar nome único
        $filename = uniqid('foto_') . '_' . time() . '.' . $extensao;

        // Salvar arquivo no storage
        $path = $arquivo->storeAs('fotos', $filename, 'public');

        // Salvar metadados no MongoDB
        $documento = [
            'pontoId' => (string) $pontoId,
            'usuarioId' => (string) $usuarioId,
            'filename' => $filename,
            'titulo' => $titulo ?? 'Foto de ' . date('d/m/Y'),
            'path' => '/storage/' . $path,
            'createdAt' => new UTCDateTime()
        ];

        $result = $this->getCollection()->insertOne($documento);
        $documento['_id'] = (string) $result->getInsertedId();

        return $this->formatarDocumento((object) $documento);
    }

    /**
     * Listar fotos de um ponto turístico
     */
    public function listarPorPonto(int $pontoId): array
    {
        $cursor = $this->getCollection()->find(
            ['pontoId' => (string) $pontoId],
            ['sort' => ['createdAt' => -1]]
        );

        $fotos = [];
        foreach ($cursor as $documento) {
            $fotos[] = $this->formatarDocumento($documento);
        }

        return $fotos;
    }

    /**
     * Contar fotos de um ponto
     */
    public function contarFotosPorPonto(int $pontoId): int
    {
        return $this->getCollection()->countDocuments([
            'pontoId' => (string) $pontoId
        ]);
    }

    /**
     * Buscar foto por ID
     */
    public function buscarPorId(string $fotoId): ?array
    {
        $documento = $this->getCollection()->findOne([
            '_id' => new ObjectId($fotoId)
        ]);

        return $documento ? $this->formatarDocumento($documento) : null;
    }

    /**
     * Deletar foto
     */
    public function deletar(string $fotoId): bool
    {
        $foto = $this->buscarPorId($fotoId);

        if (!$foto) {
            return false;
        }

        // Deletar arquivo físico
        $pathRelativo = str_replace('/storage/', '', $foto['path']);
        Storage::disk('public')->delete($pathRelativo);

        // Deletar metadados
        $result = $this->getCollection()->deleteOne([
            '_id' => new ObjectId($fotoId)
        ]);

        return $result->getDeletedCount() > 0;
    }

    /**
     * Formata o documento do MongoDB para array
     */
    private function formatarDocumento($documento): array
    {
        $createdAt = $documento->createdAt ?? null;
        if ($createdAt instanceof UTCDateTime) {
            $createdAt = $createdAt->toDateTime()->format('Y-m-d H:i:s');
        } elseif (is_object($createdAt) && method_exists($createdAt, 'toDateTime')) {
            $createdAt = $createdAt->toDateTime()->format('Y-m-d H:i:s');
        }

        return [
            'id' => (string) $documento->_id,
            'pontoId' => $documento->pontoId,
            'usuarioId' => $documento->usuarioId,
            'filename' => $documento->filename,
            'titulo' => $documento->titulo,
            'path' => $documento->path,
            'createdAt' => $createdAt
        ];
    }
}
