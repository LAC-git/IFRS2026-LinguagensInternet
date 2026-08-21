<?php

// Active Record para a tabela `clientes`

class Cliente {

    private PDO     $conn;

    private ?string $id;
    private ?string $nome;
    private ?string $email;
    private ?string $hash_senha;
    private ?string $telefone;
    private ?string $endereco;
    private ?string $criado_em;
    private ?string $atualizado_em;

    // Construtor – recebe obrigatoriamente a conexão PDO e, opcionalmente, dados iniciais.
    public function __construct( PDO $conn, array $dados = []) {

        $this->conn = $conn;

        $this->id =             null;
        $this->nome =           $dados['nome'] ?? null;
        $this->email =          $dados['email'] ?? null;
        $this->hash_senha =     $dados['hash_senha'] ?? null;
        $this->telefone =       $dados['telefone'] ?? null;
        $this->endereco =       $dados['endereco'] ?? null;
        $this->criado_em =      null;
        $this->atualizado_em =  null;
    }

    // Getters
    public function getId():            ?string { return $this->id; }
    public function getNome():          ?string { return $this->nome; }
    public function getEmail():         ?string { return $this->email; }
    public function getHashSenha():     ?string { return $this->hash_senha; }
    public function getTelefone():      ?string { return $this->telefone; }
    public function getEndereco():      ?string { return $this->endereco; }
    public function getCriadoEm():      ?string { return $this->criado_em; }
    public function getAtualizadoEm():  ?string { return $this->atualizado_em; }

    // Setters
    public function setNome(?string $nome):                 void { $this->nome = $nome; }
    public function setEmail(?string $email):               void { $this->email = $email; }
    public function setHashSenha(?string $hash_senha):      void { $this->hash_senha = $hash_senha; }
    public function setTelefone(?string $telefone):         void { $this->telefone = $telefone; }
    public function setEndereco(?string $endereco):         void { $this->endereco = $endereco; }
   

    // Salvar / Atualizar no banco de dados:
    public function save(): bool {
        if ($this->id) {

            // UPDATE – todos os campos editáveis, exceto id e timestamps
            $sql = "UPDATE clientes SET
                    nome = :nome,
                    email = :email,
                    hash_senha = :hash_senha,
                    telefone = :telefone,
                    endereco = :endereco
                    WHERE id = :id";
                
            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([
                ':nome'         => $this->nome,
                ':email'        => $this->email,
                ':hash_senha'   => $this->hash_senha,
                ':telefone'     => $this->telefone,
                ':endereco'     => $this->endereco,
                ':id'           => $this->id
            ]);

        } else {

            // INSERT – todos os campos, exceto id e timestamps (banco preenche automaticamente)
            $sql = "INSERT INTO clientes (nome, email, hash_senha, telefone, endereco)
                    VALUES (:nome, :email, :hash_senha, :telefone, :endereco)";

            $stmt = $this->conn->prepare($sql);

            $ok = $stmt->execute([
                ':nome'         => $this->nome,
                ':email'        => $this->email,
                ':hash_senha'   => $this->hash_senha,
                ':telefone'     => $this->telefone,
                ':endereco'     => $this->endereco
            ]);

            if ($ok) {
                // Guardar o id no objeto
                $this->id = $this->conn->lastInsertId();
            }
        
            return $ok;
        }
    }


    // Carrega os dados de um cliente a partir do ID
    public function load(int $id): bool {
        $stmt = $this->conn->prepare("SELECT * FROM clientes WHERE id = :id");
        $stmt->execute([':id' => $id]);
        if ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->id              = $dados['id'];
            $this->nome            = $dados['nome'];
            $this->email           = $dados['email'];
            $this->hash_senha      = $dados['hash_senha'];
            $this->telefone        = $dados['telefone'];
            $this->endereco        = $dados['endereco'];
            $this->criado_em       = $dados['criado_em'];
            $this->atualizado_em   = $dados['atualizado_em'];
            return true;
        }
        return false;
    }

    // Exclui o cliente atual do banco de dados
    public function delete(): bool {
        if (!$this->id) { return false; }
        $stmt = $this->conn->prepare("DELETE FROM clientes WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }
    
    // Retorna todos os clientes cadastrados
    public static function all(PDO $conn): array {
        $stmt = $conn->query("SELECT * FROM clientes");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Converte uma instância de Cliente em um array associativo
    public function toArray(): array {
        return get_object_vars($this);
    }
}

?>
