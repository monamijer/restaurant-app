<div class="container mt-4">
    <h2 class="mb-4">⚙️ Paramètres du restaurant</h2>

    <div id="alert-zone"></div>

    <form id="form-parametres">
        <div class="card mb-3">
            <div class="card-header">Informations générales</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Nom du restaurant</label>
                    <input type="text" class="form-control" name="nom_restaurant"
                           value="<?= htmlspecialchars($params['nom_restaurant']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email de contact</label>
                    <input type="email" class="form-control" name="email_contact"
                           value="<?= htmlspecialchars($params['email_contact']) ?>">
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Règles de réservation & acompte</div>
            <div class="card-body">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="acompte_actif" value="1"
                           <?= $params['acompte_actif'] == '1' ? 'checked' : '' ?>>
                    <label class="form-check-label">Activer l'acompte obligatoire</label>
                </div>

                <div class="mb-3">
                    <label class="form-label">Acompte demandé à partir de combien de personnes ?</label>
                    <input type="number" class="form-control" name="nb_personnes_min_acompte"
                           value="<?= htmlspecialchars($params['nb_personnes_min_acompte']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Montant de l'acompte par personne</label>
                    <input type="number" class="form-control" name="montant_acompte_par_personne"
                           value="<?= htmlspecialchars($params['montant_acompte_par_personne']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Délai d'annulation gratuite (heures avant)</label>
                    <input type="number" class="form-control" name="delai_annulation_gratuite_h"
                           value="<?= htmlspecialchars($params['delai_annulation_gratuite_h']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Délai de grâce avant libération de table (minutes)</label>
                    <input type="number" class="form-control" name="delai_grace_retard_min"
                           value="<?= htmlspecialchars($params['delai_grace_retard_min']) ?>">
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer les paramètres</button>
    </form>
</div>

<script src="/assets/js/admin.js"></script>