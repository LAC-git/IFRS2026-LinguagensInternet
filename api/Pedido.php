<?php

// Active Record para a tabela `pedidos`

class Pedido {

    private ?int $id;
    private int $cliente_id;
    private ?int $funcionario_id;
    private string $data_pedido;
    private string $status;
    private float $total;
    private string $criado_em;
    private string $atualizado_em;

    public function __construct(array $dados = [])
    {
        $this->id = $dados['id'] ?? null;
        $this->cliente_id = $dados['cliente_id'] ?? 0;
        $this->funcionario_id = $dados['funcionario_id'] ?? null;
        $this->data_pedido = $dados['data_pedido'] ?? date('Y-m-d H:i:s');
        $this->status = $dados['status'] ?? 'pendente';
        $this->total = (float) ($dados['total'] ?? 0.0);
        $this->criado_em = $dados['criado_em'] ?? date('Y-m-d H:i:s');
        $this->atualizado_em = $dados['atualizado_em'] ?? date('Y-m-d H:i:s');
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getClienteId(): int { return $this->cliente_id; }
    public function getFuncionarioId(): ?int { return $this->funcionario_id; }
    public function getDataPedido(): string { return $this->data_pedido; }
    public function getStatus(): string { return $this->status; }
    public function getTotal(): float { return $this->total; }
    public function getCriadoEm(): string { return $this->criado_em; }
    public function getAtualizadoEm(): string { return $this->atualizado_em; }

    // Setters
    public function setId(?int $id): void { $this->id = $id; }
    public function setClienteId(int $cliente_id): void { $this->cliente_id = $cliente_id; }
    public function setFuncionarioId(?int $funcionario_id): void { $this->funcionario_id = $funcionario_id; }
    public function setDataPedido(string $data_pedido): void { $this->data_pedido = $data_pedido; }
    public function setStatus(string $status): void { $this->status = $status; }
    public function setTotal(float $total): void { $this->total = $total; }
    public function setCriadoEm(string $criado_em): void { $this->criado_em = $criado_em; }
    public function setAtualizadoEm(string $atualizado_em): void { $this->atualizado_em = $atualizado_em; }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

?>

