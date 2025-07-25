# Projet Symfony Evaluation

Projet qui constitue l'évaluation des compétences acquises lors du cours de symfony dans la formation HumanBooster DWWM11.

C'est dans l'idée un site de livres où on peut chercher, consulter, ajouter, et commenter (reviews) des livres.

## Fonctionnalités

- Système d'inscription et d'authentification avec roles : la page de garde est accessible à tous, les pages spécifiques aux livres sont pour les utilisateurs seulement. Les admins ont accès à des cruds pour mieux gérer les données du site.
  
- Les utilisateurs peuvent s'abonner à un livre pour le suivre, et être alertés par mail quand le livre reçoit un nouveau review.
  
- Les livres sont initialement non visibles sur la page de garde quand ils sont soumis par un utilisateur. Les admins sont notifiés à chaque soumission pour pouvoir ensuite décider de rendre le nouveau livre visible ou non.
  
- Les utilisateurs donnent une note et un commentaire au livre dans le review, le livre affiche ensuite la note moyenne de tous ses reviews.
  
- Chaque livre a une image qui représente sa couverture. Une image placeholder est attribuée aux livres créés en tant que fixtures.
  
- Un système de recherche dans la navbar permet de chercher des livres en tapant une partie de leur titre. Il est aussi possible de les trier par catégorie.
  
- Plusieurs messages flash qui apparaissent généralement lors du remplissage d'un formulaire.

### Autres détails

- Le CSS est une masse chaotique de tailwind, CSS classique et CSS imbriqué. C'est parti un peu dans tous les sens mais le front était pas le focus de ce module.

- Le front en géénral est vraiment moche, désolé pour les yeux. Surtout pour la navbar pas alignée.
