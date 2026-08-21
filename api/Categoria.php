<?php

// Active Record para a tabela `categorias`

class Categoria {

    private PDO     $conn;

    private ?string $id;
    private ?string $nome;
    private ?string $descricao;
    private ?string $criado_em;
    private ?string $atualizado_em;

    // Construtor – recebe obrigatoriamente a conexão PDO e, opcionalmente, dados iniciais
    public function __construct(PDO $conn, array $dados = []) {

        $this->conn = $conn;

        $this->id              = null;
        $this->nome            = $dados['nome'] ?? null;
        $this->descricao       = $dados['descricao'] ?? null;
        $this->criado_em       = null;
        $this->atualizado_em   = null;
    }

    // Getters
    public function getId():            ?string { return $this->id; }
    public function getNome():          ?string { return $this->nome; }
    public function getDescricao():     ?string { return $this->descricao; }
    public function getCriadoEm():      ?string { return $this->criado_em; }
    public function getAtualizadoEm():  ?string { return $this->atualizado_em; }

    // Setters 
    public function setNome(?string $nome):             void { $this->nome = $nome; }
    public function setDescricao(?string $descricao):   void { $this->descricao = $descricao; }

    // Salva (insere ou atualiza) a categoria no banco
    public function save(): bool {
        if ($this->id) {

            // UPDATE – todos os campos editáveis, exceto id e timestamps
            $sql = "UPDATE categorias SET
                        nome = :nome,
                        descricao = :descricao
                    WHERE id = :id";

            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([
                ':nome'         => $this->nome,
                ':descricao'    => $this->descricao,
                ':id'           => $this->id
            ]);

        } else {

            // INSERT – todos os campos, exceto id e timestamps (banco preenche automaticamente)
            $sql = "INSERT INTO categorias (nome, descricao)
                    VALUES (:nome, :descricao)";

            $stmt = $this->conn->prepare($sql);

            $ok = $stmt->execute([
                ':nome'         => $this->nome,
                ':descricao'    => $this->descricao
            ]);

            if ($ok) {
                // Guardar o id no objeto
                $this->id = $this->conn->lastInsertId();
            }

            return $ok;
        }
    }

    // Carrega os dados de uma categoria a partir do ID
    public function load(int $id): bool {
        $stmt = $this->conn->prepare("SELECT * FROM categorias WHERE id = :id");
        $stmt->execute([':id' => $id]);
        if ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->id              = $dados['id'];
            $this->nome            = $dados['nome'];
            $this->descricao       = $dados['descricao'];
            $this->criado_em       = $dados['criado_em'];
            $this->atualizado_em   = $dados['atualizado_em'];
            return true;
        }
        return false;
    }

    // Exclui a categoria atual do banco de dados
    public function delete(): bool {
        if (!$this->id) {
            return false;
        }
        $stmt = $this->conn->prepare("DELETE FROM categorias WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    // Retorna todas as categorias cadastradas.
    public static function all(PDO $conn): array {
        $stmt = $conn->query("SELECT * FROM categorias");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Converte a instância atual em um array associativo contendo apenas os dados da entidade.
    public function toArray(): array {
        return [
            'id'              => $this->id,
            'nome'            => $this->nome,
            'descricao'       => $this->descricao,
            'criado_em'       => $this->criado_em,
            'atualizado_em'   => $this->atualizado_em
        ];
    }
}

?>
