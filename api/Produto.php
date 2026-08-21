<?php

// Active Record para a tabela `produtos`

class Produto {

    private ?int $id;
    private int $categoria_id;
    private string $nome;
    private ?string $descricao;
    private float $preco;
    private int $estoque;
    private string $criado_em;
    private string $atualizado_em;

    public function __construct(array $dados = [])
    {
        $this->id = $dados['id'] ?? null;
        $this->categoria_id = $dados['categoria_id'] ?? 0;
        $this->nome = $dados['nome'] ?? '';
        $this->descricao = $dados['descricao'] ?? null;
        $this->preco = (float) ($dados['preco'] ?? 0.0);
        $this->estoque = (int) ($dados['estoque'] ?? 0);
        $this->criado_em = $dados['criado_em'] ?? date('Y-m-d H:i:s');
        $this->atualizado_em = $dados['atualizado_em'] ?? date('Y-m-d H:i:s');
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getCategoriaId(): int { return $this->categoria_id; }
    public function getNome(): string { return $this->nome; }
    public function getDescricao(): ?string { return $this->descricao; }
    public function getPreco(): float { return $this->preco; }
    public function getEstoque(): int { return $this->estoque; }
    public function getCriadoEm(): string { return $this->criado_em; }
    public function getAtualizadoEm(): string { return $this->atualizado_em; }

    // Setters
    public function setId(?int $id): void { $this->id = $id; }
    public function setCategoriaId(int $categoria_id): void { $this->categoria_id = $categoria_id; }
    public function setNome(string $nome): void { $this->nome = $nome; }
    public function setDescricao(?string $descricao): void { $this->descricao = $descricao; }
    public function setPreco(float $preco): void { $this->preco = $preco; }
    public function setEstoque(int $estoque): void { $this->estoque = $estoque; }
    public function setCriadoEm(string $criado_em): void { $this->criado_em = $criado_em; }
    public function setAtualizadoEm(string $atualizado_em): void { $this->atualizado_em = $atualizado_em; }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

?>

