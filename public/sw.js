// Service worker minimal — rend le site installable sans mettre en cache
// le contenu dynamique (réservations, panier, admin), pour éviter d'afficher
// des données périmées.

self.addEventListener("install", function (event) {
  self.skipWaiting();
});

self.addEventListener("activate", function (event) {
  event.waitUntil(self.clients.claim());
});

self.addEventListener("fetch", function (event) {
  // Laisse passer toutes les requêtes normalement (pas de cache).
  // On pourra ajouter un cache sélectif plus tard si besoin (ex: assets statiques).
});
