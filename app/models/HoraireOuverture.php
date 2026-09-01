<?php

class HoraireOuverture extends Model
{
    protected string $table = 'horaires_ouverture';

    public function getAujourdhui(): ?array
    {
        $jour = (int) date('w'); // 0 = dimanche ... 6 = samedi
        $stmt = $this->db->prepare('SELECT * FROM horaires_ouverture WHERE jour_semaine = ?');
        $stmt->execute([$jour]);
        return $stmt->fetch() ?: null;
    }

    public function getSemaine(): array
    {
        return $this->db->query('SELECT * FROM horaires_ouverture ORDER BY jour_semaine')->fetchAll();
    }
    public function mettreAJourSemaine(array $horaires): void
    {
        foreach ($horaires as $jour => $data) {
            $ferme = isset($data['ferme']) ? 1 : 0;
            $ouverture = $ferme ? null : ($data['heure_ouverture'] ?: null);
            $fermeture = $ferme ? null : ($data['heure_fermeture'] ?: null);

            $existe = $this->db->prepare('SELECT id FROM horaires_ouverture WHERE jour_semaine = ?');
            $existe->execute([$jour]);
            $ligne = $existe->fetch();

            if ($ligne) {
                $this->update($ligne['id'], [
                    'heure_ouverture' => $ouverture,
                    'heure_fermeture' => $fermeture,
                    'ferme' => $ferme,
                ]);
            } else {
                $this->create([
                    'jour_semaine' => $jour,
                    'heure_ouverture' => $ouverture,
                    'heure_fermeture' => $fermeture,
                    'ferme' => $ferme,
                ]);
            }
        }
    }
}
