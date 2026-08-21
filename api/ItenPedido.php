<?php

// Active Record para a tabela `itens_pedido`

class ItemPedido {

    private ?int $id;
    private int $pedido_id;
    private int $produto_id;
    private int $quantidade;
    private float $preco_unitario;
    private float $subtotal; // campo STORED – somente leitura (não possui setter)
    private string $criado_em;
    private string $atualizado_em;

    public function __construct(array $dados = [])
    {
        $this->id = $dados['id'] ?? null;
        $this->pedido_id = $dados['pedido_id'] ?? 0;
        $this->produto_id = $dados['produto_id'] ?? 0;
        $this->quantidade = (int) ($dados['quantidade'] ?? 1);
        $this->preco_unitario = (float) ($dados['preco_unitario'] ?? 0.0);
        $this->subtotal = (float) ($dados['subtotal'] ?? 0.0); // calculado pelo BD, mas pode ser lido
        $this->criado_em = $dados['criado_em'] ?? date('Y-m-d H:i:s');
        $this->atualizado_em = $dados['atualizado_em'] ?? date('Y-m-d H:i:s');
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getPedidoId(): int { return $this->pedido_id; }
    public function getProdutoId(): int { return $this->produto_id; }
    public function getQuantidade(): int { return $this->quantidade; }
    public function getPrecoUnitario(): float { return $this->preco_unitario; }
    public function getSubtotal(): float { return $this->subtotal; }
    public function getCriadoEm(): string { return $this->criado_em; }
    public function getAtualizadoEm(): string { return $this->atualizado_em; }

    // Setters (subtotal não possui setter por ser STORED)
    public function setId(?int $id): void { $this->id = $id; }
    public function setPedidoId(int $pedido_id): void { $this->pedido_id = $pedido_id; }
    public function setProdutoId(int $produto_id): void { $this->produto_id = $produto_id; }
    public function setQuantidade(int $quantidade): void { $this->quantidade = $quantidade; }
    public function setPrecoUnitario(float $preco_unitario): void { $this->preco_unitario = $preco_unitario; }
    // Não há setSubtotal()
    public function setCriadoEm(string $criado_em): void { $this->criado_em = $criado_em; }
    public function setAtualizadoEm(string $atualizado_em): void { $this->atualizado_em = $atualizado_em; }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

?>

