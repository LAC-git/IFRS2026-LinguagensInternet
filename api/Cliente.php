<?php

// Active Record para a tabela `clientes`

class Cliente {

    private ?int $id;
    private string $nome;
    private string $email;
    private string $hash_senha;
    private ?string $telefone;
    private ?string $endereco;
    private string $criado_em;
    private string $atualizado_em;

    public function __construct(array $dados = [])
    {
        $this->id = $dados['id'] ?? null;
        $this->nome = $dados['nome'] ?? '';
        $this->email = $dados['email'] ?? '';
        $this->hash_senha = $dados['hash_senha'] ?? '';
        $this->telefone = $dados['telefone'] ?? null;
        $this->endereco = $dados['endereco'] ?? null;
        $this->criado_em = $dados['criado_em'] ?? date('Y-m-d H:i:s');
        $this->atualizado_em = $dados['atualizado_em'] ?? date('Y-m-d H:i:s');
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getNome(): string { return $this->nome; }
    public function getEmail(): string { return $this->email; }
    public function getHashSenha(): string { return $this->hash_senha; }
    public function getTelefone(): ?string { return $this->telefone; }
    public function getEndereco(): ?string { return $this->endereco; }
    public function getCriadoEm(): string { return $this->criado_em; }
    public function getAtualizadoEm(): string { return $this->atualizado_em; }

    // Setters
    public function setId(?int $id): void { $this->id = $id; }
    public function setNome(string $nome): void { $this->nome = $nome; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setHashSenha(string $hash_senha): void { $this->hash_senha = $hash_senha; }
    public function setTelefone(?string $telefone): void { $this->telefone = $telefone; }
    public function setEndereco(?string $endereco): void { $this->endereco = $endereco; }
    public function setCriadoEm(string $criado_em): void { $this->criado_em = $criado_em; }
    public function setAtualizadoEm(string $atualizado_em): void { $this->atualizado_em = $atualizado_em; }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}

?>
