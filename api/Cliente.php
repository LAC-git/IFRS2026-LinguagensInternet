<?php

// Active Record para a tabela `clientes`

class Cliente {

    private PDO     $conn;
    
    private ?int    $id;
    private ?string $nome;
    private ?string $email;
    private ?string $hash_senha;
    private ?string $telefone;
    private ?string $endereco;
    private ?string $criado_em;
    private ?string $atualizado_em;

    // O array `$dados` é opcional
    public function __construct(array $dados = [], PDO $conn) {

        $this->conn = $conn;

        $this->id =             $dados['id'] ?? null;
        $this->nome =           $dados['nome'] ?? null;
        $this->email =          $dados['email'] ?? null;
        $this->hash_senha =     $dados['hash_senha'] ?? null;
        $this->telefone =       $dados['telefone'] ?? null;
        $this->endereco =       $dados['endereco'] ?? null;
        $this->criado_em =      $dados['criado_em'] ?? null;
        $this->atualizado_em =  $dados['atualizado_em'] ?? null;
    }

    // Getters
    public function getId():            ?int    { return $this->id; }
    public function getNome():          ?string { return $this->nome; }
    public function getEmail():         ?string { return $this->email; }
    public function getHashSenha():     ?string { return $this->hash_senha; }
    public function getTelefone():      ?string { return $this->telefone; }
    public function getEndereco():      ?string { return $this->endereco; }
    public function getCriadoEm():      ?string { return $this->criado_em; }
    public function getAtualizadoEm():  ?string { return $this->atualizado_em; }

    // Setters
    public function setId(?int $id):                            void { $this->id = $id; }
    public function setNome(?string $nome):                     void { $this->nome = $nome; }
    public function setEmail(?string $email):                   void { $this->email = $email; }
    public function setHashSenha(?string $hash_senha):          void { $this->hash_senha = $hash_senha; }
    public function setTelefone(?string $telefone):             void { $this->telefone = $telefone; }
    public function setEndereco(?string $endereco):             void { $this->endereco = $endereco; }
    public function setCriadoEm(?string $criado_em):            void { $this->criado_em = $criado_em; }
    public function setAtualizadoEm(?string $atualizado_em):    void { $this->atualizado_em = $atualizado_em; }


    // Salvar / Atualizar no banco de dados:
    public function save() {
    if ($this->id) {

        $sql = "UPDATE clientes SET nome=:n   WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id' => $this->id,
        ]);

    } else {
        
        $sql = "INSERT INTO clientes () VALUES ()";
        $stmt = $this->pdo->prepare($sql);
        $ok = $stmt->execute([
        ]);
        
        if ($ok) {
            $this->id $this->pdo->lastInsertId();
        }
        return $ok;
    }
}

}

?>
