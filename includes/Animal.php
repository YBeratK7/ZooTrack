<?php
require_once __DIR__ . '/db.php';

class Animal {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function getAll(int $userId, string $role): array {
        if ($role === 'admin') {
            $stmt = $this->pdo->query(
                'SELECT a.*, u.full_name AS keeper_name
                 FROM animals a
                 JOIN users u ON a.added_by = u.id
                 ORDER BY a.created_at DESC'
            );
        } else {
            $stmt = $this->pdo->prepare(
                'SELECT a.*, u.full_name AS keeper_name
                 FROM animals a
                 JOIN users u ON a.added_by = u.id
                 WHERE a.added_by = ?
                 ORDER BY a.created_at DESC'
            );
            $stmt->execute([$userId]);
        }
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->pdo->prepare('SELECT * FROM animals WHERE id = ?');
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): bool {
        $stmt = $this->pdo->prepare(
            'INSERT INTO animals (name, species, gender, birth_date, enclosure, diet, status, weight_kg, notes, added_by)
             VALUES (:name, :species, :gender, :birth_date, :enclosure, :diet, :status, :weight_kg, :notes, :added_by)'
        );
        return $stmt->execute([
            ':name'       => $data['name'],
            ':species'    => $data['species'],
            ':gender'     => $data['gender'],
            ':birth_date' => $data['birth_date'] ?: null,
            ':enclosure'  => $data['enclosure'],
            ':diet'       => $data['diet'],
            ':status'     => $data['status'],
            ':weight_kg'  => $data['weight_kg'] ?: null,
            ':notes'      => $data['notes'],
            ':added_by'   => $data['added_by'],
        ]);
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->pdo->prepare(
            'UPDATE animals SET name=:name, species=:species, gender=:gender, birth_date=:birth_date,
             enclosure=:enclosure, diet=:diet, status=:status, weight_kg=:weight_kg, notes=:notes
             WHERE id=:id'
        );
        return $stmt->execute([
            ':name'       => $data['name'],
            ':species'    => $data['species'],
            ':gender'     => $data['gender'],
            ':birth_date' => $data['birth_date'] ?: null,
            ':enclosure'  => $data['enclosure'],
            ':diet'       => $data['diet'],
            ':status'     => $data['status'],
            ':weight_kg'  => $data['weight_kg'] ?: null,
            ':notes'      => $data['notes'],
            ':id'         => $id,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM animals WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function getStats(): array {
        $stats = [];
        $stmt = $this->pdo->query('SELECT COUNT(*) as total FROM animals');
        $stats['total'] = $stmt->fetchColumn();

        $stmt = $this->pdo->query("SELECT COUNT(*) as healthy FROM animals WHERE status = 'Sağlıklı'");
        $stats['healthy'] = $stmt->fetchColumn();

        $stmt = $this->pdo->query("SELECT COUNT(*) as treatment FROM animals WHERE status IN ('Tedavide','Karantinada')");
        $stats['treatment'] = $stmt->fetchColumn();

        $stmt = $this->pdo->query('SELECT COUNT(DISTINCT species) as species FROM animals');
        $stats['species'] = $stmt->fetchColumn();

        return $stats;
    }

    public function canEdit(int $animalId, int $userId, string $role): bool {
        if ($role === 'admin') return true;
        $animal = $this->getById($animalId);
        return $animal && (int)$animal['added_by'] === $userId;
    }
}
