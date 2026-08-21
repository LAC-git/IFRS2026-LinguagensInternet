<?php

// Active Record para a tabela `funcionarios`

class Funcionario {

    private PDO     $conn;

    private ?string $id;
    private ?string $nome;
    private ?string $cpf;
    private ?string $cargo;
    private ?string $telefone;
    private ?string $email;
    private ?string $hash_senha;
    private ?string $criado_em;
    private ?string $atualizado_em;

    // Construtor – recebe obrigatoriamente a conexão PDO e, opcionalmente, dados iniciais
    public function __construct(PDO $conn, array $dados = []) {

        $this->conn = $conn;

        $this->id              = null;
        $this->nome            = $dados['nome'] ?? null;
        $this->cpf             = $dados['cpf'] ?? null;
        $this->cargo           = $dados['cargo'] ?? null;
        $this->telefone        = $dados['telefone'] ?? null;
        $this->email           = $dados['email'] ?? null;
        $this->hash_senha      = $dados['hash_senha'] ?? null;
        $this->criado_em       = null;
        $this->atualizado_em   = null;
    }

    // Getters
    public function getId():            ?string { return $this->id; }
    public function getNome():          ?string { return $this->nome; }
    public function getCpf():           ?string { return $this->cpf; }
    public function getCargo():         ?string { return $this->cargo; }
    public function getTelefone():      ?string { return $this->telefone; }
    public function getEmail():         ?string { return $this->email; }
    public function getHashSenha():     ?string { return $this->hash_senha; }
    public function getCriadoEm():      ?string { return $this->criado_em; }
    public function getAtualizadoEm():  ?string { return $this->atualizado_em; }

    // Setters 
    public function setNome(?string $nome):             void { $this->nome = $nome; }
    public function setCpf(?string $cpf):               void { $this->cpf = $cpf; }
    public function setCargo(?string $cargo):           void { $this->cargo = $cargo; }
    public function setTelefone(?string $telefone):     void { $this->telefone = $telefone; }
    public function setEmail(?string $email):           void { $this->email = $email; }
    public function setHashSenha(?string $hash_senha):  void { $this->hash_senha = $hash_senha; }

    // Salva (insere ou atualiza) o funcionário no banco
    public function save(): bool {
        if ($this->id) {

            // UPDATE – todos os campos editáveis, exceto id e timestamps
            $sql = "UPDATE funcionarios SET
                        nome = :nome,
                        cpf = :cpf,
                        cargo = :cargo,
                        telefone = :telefone,
                        email = :email,
                        hash_senha = :hash_senha
                    WHERE id = :id";

            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([
                ':nome'         => $this->nome,
                ':cpf'          => $this->cpf,
                ':cargo'        => $this->cargo,
                ':telefone'     => $this->telefone,
                ':email'        => $this->email,
                ':hash_senha'   => $this->hash_senha,
                ':id'           => $this->id
            ]);

        } else {

            // INSERT – todos os campos, exceto id e timestamps (banco preenche automaticamente)
            $sql = "INSERT INTO funcionarios (nome, cpf, cargo, telefone, email, hash_senha)
                    VALUES (:nome, :cpf, :cargo, :telefone, :email, :hash_senha)";

            $stmt = $this->conn->prepare($sql);

            $ok = $stmt->execute([
                ':nome'         => $this->nome,
                ':cpf'          => $this->cpf,
                ':cargo'        => $this->cargo,
                ':telefone'     => $this->telefone,
                ':email'        => $this->email,
                ':hash_senha'   => $this->hash_senha
            ]);

            if ($ok) {
                // Guardar o id no objeto
                $this->id = $this->conn->lastInsertId();
            }

            return $ok;
        }
    }

    // Carrega os dados de um funcionário a partir do ID
    public function load(int $id): bool {
        $stmt = $this->conn->prepare("SELECT * FROM funcionarios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        if ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->id              = $dados['id'];
            $this->nome            = $dados['nome'];
            $this->cpf             = $dados['cpf'];
            $this->cargo           = $dados['cargo'];
            $this->telefone        = $dados['telefone'];
            $this->email           = $dados['email'];
            $this->hash_senha      = $dados['hash_senha'];
            $this->criado_em       = $dados['criado_em'];
            $this->atualizado_em   = $dados['atualizado_em'];
            return true;
        }
        return false;
    }

    // Exclui o funcionário atual do banco de dados
    public function delete(): bool {
        if (!$this->id) { return false; }
        $stmt = $this->conn->prepare("DELETE FROM funcionarios WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    // Retorna todos os funcionários cadastrados
    public static function all(PDO $conn): array {
        $stmt = $conn->query("SELECT * FROM funcionarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Converte a instância atual em um array associativo contendo apenas os dados da entidade.
    public function toArray(): array {
        return [
            'id'              => $this->id,
            'nome'            => $this->nome,
            'cpf'             => $this->cpf,
            'cargo'           => $this->cargo,
            'telefone'        => $this->telefone,
            'email'           => $this->email,
            'hash_senha'      => $this->hash_senha,
            'criado_em'       => $this->criado_em,
            'atualizado_em'   => $this->atualizado_em
        ];
    }
}

?>
