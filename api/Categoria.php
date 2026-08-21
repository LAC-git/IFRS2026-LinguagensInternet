<?php


// Active Record para a tabela `categorias`

class Categoria {

    private ?int $id;
    private string $nome;
    private ?string $descricao;
    private string $criado_em;
    private string $atualizado_em;

    public function __construct(array $dados = [])
    {
        $this->id = $dados['id'] ?? null;
        $this->nome = $dados['nome'] ?? '';
        $this->descricao = $dados['descricao'] ?? null;
        $this->criado_em = $dados['criado_em'] ?? date('Y-m-d H:i:s');
        $this->atualizado_em = $dados['atualizado_em'] ?? date('Y-m-d H:i:s');
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getNome(): string { return $this->nome; }
    public function getDescricao(): ?string { return $this->descricao; }
    public function getCriadoEm(): string { return $this->criado_em; }
    public function getAtualizadoEm(): string { return $this->atualizado_em; }

    // Setters
    public function setId(?int $id): void { $this->id = $id; }
    public function setNome(string $nome): void { $this->nome = $nome; }
    public function setDescricao(?string $descricao): void { $this->descricao = $descricao; }
    public function setCriadoEm(string $criado_em): void { $this->criado_em = $criado_em; }
    public function setAtualizadoEm(string $atualizado_em): void { $this->atualizado_em = $atualizado_em; }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

?>

