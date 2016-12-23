<?php 

return [

    /*
    |--------------------------------------------------------------------------
    | Global Language Lines
    |--------------------------------------------------------------------------
    |
    | CMS global language lines
    |
    */
   
    /*
     * Date format patterns
     */
    'date_format'        => 'd-m-Y',         // PHP default, for Carbon ( http://www.php.net/manual/en/function.date.php )
    'date_format_alt'    => 'DD-MM-YYYY',    // for the date time picker UI widget
   
    /*
     * Security
     */
    'spam_protection' => 'Spam Protection, merci d\'attendre quelques secondes avant de valider le formulaire.',
    'debug_warning'   => 'Avertissement: Le mode debug est activé, bien que cela semble être un serveur de production!',

    /*
     * Errors
     */
    'not_found'     => 'Pas de ressource trouvée.',
    'not_possible'  => 'Désolé, cette action est actuellement indisponible.',

    /*
     * User permisssions
     */ 
    'no_auth'       => 'Accès refusé. Merci de vous connecter.',
    'access_denied' => 'Accès refusé. Vous n\'avez pas la permisssion.',
    
    /*
     * Forms
     */
    'save'      => 'Enregistrer',
    'apply'     => 'Appliquer',
    'reset'     => 'Réinitialiser',
    'update'    => 'Modifier',

    /*
     * Auto CRUD handling (and related stuff)
     */
    'created'       => ':0 créé.',
    'updated'       => ':0 modifié.',
    'deleted'       => ':0 supprimé.',
    'restored'      => ':0 restauré.',
    'list_empty'    => 'Merci de créer au moins un ":0" avant de continuer.',
    'invalid_image' => 'Format d\'image invalide',
    'bad_extension' => "L'extension ':0' est interdite.",

    /*
     * Model index building
     */
    'create'        => 'Nouveau',
    'categories'    => 'Catégories',
    'config'        => 'Config',
    'actions'       => 'Actions',
    'edit'          => 'Modifier',
    'delete'        => 'Supprimer',
    'restore'       => 'Restaurer',
    'id'            => 'ID',
    'index'         => 'Index',
    'title'         => 'Titre',
    'short_title'   => 'Titre court',
    'author'        => 'Auteur',
    'creator'       => 'Createur',
    'category'      => 'Categorie',
    'type'          => 'Type',
    'provider'      => 'Fournisseur',
    'text'          => 'Texte',
    'code'          => 'Code',
    'description'   => 'Description',
    'image'         => 'Image',
    'icon'          => 'Icône',
    'date'          => 'Date',
    'published'     => 'Publié',
    'starts_at'     => 'Commence à',
    'created_at'    => 'Créé à',
    'updated_at'    => 'Mise à jour à',
    'achieved_at'   => 'Terminé à',
    'internal'      => 'Interne',
    'featured'      => 'En avant',
    'state'         => 'Etat',
    'new'           => 'Nouveau',

    /*
     * Misc
     */
    'sorting'       => 'Trier',
    'search'        => 'Rechercher',
    'recycle_bin'   => 'Corbeille',
    'max_size'      => 'Taille Max: :0 Bytes',
    'accessed'      => 'accédé',
    'no_items'      => 'Il y a aucun item.',
    'share_this'    => 'Partager',
    'save_to_del'   => 'Valider le formulaire pour supprimer l\'image.',

    /*
     * JS
     */
    'request_failed'    => 'Erreur: Requête invalide.',
    'delete_item'       => 'Supprimer cet item?',
    'perform_action'    => 'Exécuter cette action?',

    /*
     * Backend template
     */
    'no_js'             => 'JavaScript non activé. Merci d\'activer JavaScript!',
    'admin_dashboard'   => 'Admin-Panel',
    'new_messages'      => 'Un nouveau message|:count nouveaux messages',
    'top'               => 'Top',
    'help'              => 'Aide',
    'welcome'           => 'Bienvenue',

    /*
     * Comments
     */
    'comments'              => 'Commentaires',
    'comment_create_fail'   => 'Impossible de créer le commentaire. :0',
    'comment_update_fail'   => 'Impossible de modifier le commentaire. :0',
    'enable_comments'       => 'Activer les commentaires',

    /*
     * Captcha
     */
    'captcha_invalid'   => 'Le code captcha est invalide!',

    /*
     * General terms
     */
    'date_time'         => 'Date & Heure',
    'yes'               => 'Oui',
    'no'                => 'Non',
    'valid'             => 'valide',
    'invalid'           => 'invalide',
    'enabled'           => 'Activé',
    'disabled'          => 'Désactivé',
    'read_more'         => 'Lire plus...',
    'name'              => 'Nom',
    'username'          => 'Nom d\'utilisateur',
    'email'             => 'Email',
    'password'          => 'Mot de passe',
    'file'              => 'Fichier',
    'link'              => 'Lien',
    'links'             => 'Liens',
    'send'              => 'Envoyer',
    'position'          => 'Position',
    'location'          => 'Emplacement',
    'url'               => 'URL',
    'quote'             => 'Citer',
    'size'              => 'Taille',
    'ip'                => 'IP',
    'online'            => 'En ligne',
    'offline'           => 'Hors-Ligne',
    'viewers'           => 'Spectateurs',
    'task'              => 'Tâche',
    'add_tags'          => 'Ajouter des tags',
    'reply'             => 'Répondre',
    'latest'            => 'Derniers',
    'item'              => 'Article',
    'home'              => 'Accueil',
    'remove'            => 'Supprimer',
    'of'                => 'de',
    'role'              => 'Rôle',
    'message'           => 'Message',
    'subject'           => 'Sujet',
    'latest_msgs'       => 'Dernier Messages', // Admin dashboard feed messages
    'quick_access'      => 'Accès rapide', // Admin dashboard quick access
    'setting'           => 'Paramètre',
    'value'             => 'Valeur',
    'placeholder'       => 'Clé de développement par défaut, il faut la changer!', // Diag module
    'compiled'          => 'compilé',
    'website'           => 'Site internet',
    'calendar'          => 'Calendrier',
    'team_members'      => 'Membres de l\'équipe',
    'profile'           => 'Profil',
    'edit_profile'      => 'Modifier le Profil',
    'add'               => 'Ajouter',
    'logout'            => 'Déconnecter',
    'install'           => 'Installer',
    'preview'           => 'Previsualiser',
    'total'             => 'Total',
    'nothing_new'       => 'Désoler, rien de nouveau. Revenez plus tard.',
    'back'              => 'Retour',
    'theme'             => 'Thème',
    'theme_christmas'   => 'Mode Nöel (Flocon)',
    'theme_snow_color'  => 'Couleur des flocons',
    'loading'           => 'Chargement',
    'lineup'            => 'Composition',
    'translate'         => 'Traduire',
    'successful'        => 'Avec succès!',
    'slots'             => 'Slots',
    'mode'              => 'Mode',
    'prize'             => 'Prix',
    'closed'            => 'Fermé',
    'leave'             => 'Quitter',
    'join'              => 'Joindre',
    'confirm'           => 'Confirmer',
    'rules'             => 'Règles',
    'round'             => 'Manche',

    /*
     * Days
     */
    'yeah'          => 'Année',
    'month'         => 'Mois',
    'day'           => 'Jour',
    'today'         => 'Aujourd\'hui',
    'yesterday'     => 'Hier',
    'tomorrow'      => 'Demain',
    'monday'        => 'Lundi',
    'tuesday'       => 'Mardi',
    'wednesday'     => 'Mercredi',
    'thursday'      => 'Jeudi',
    'friday'        => 'Vendredi',
    'saturday'      => 'Samedi',
    'sunday'        => 'Dimanche', 

    /*
     * Module names
     */
    'object_adverts'        => 'Annonces',
    'object_auth'           => 'Authentification',
    'object_awards'         => 'Récompenses',
    'object_comments'       => 'Commentaires',
    'object_config'         => 'Configuration',
    'object_contact'        => 'Contact',
    'object_countries'      => 'Pays',
    'object_cups'           => 'Tournois',
    'object_dashboard'      => 'Tableau de bord',
    'object_diag'           => 'Diagnostiques',
    'object_downloads'      => 'Téléchargements',
    'object_events'         => 'Evènements',
    'object_forums'         => 'Forums',
    'object_friends'        => 'Amis',
    'object_galleries'      => 'Galeries',
    'object_games'          => 'Jeux',
    'object_groups'         => 'Groupes',
    'object_images'         => 'Images',
    'object_languages'      => 'Langues',
    'object_maps'           => 'Cartes',
    'object_matches'        => 'Matches',
    'object_messages'       => 'Messages',
    'object_modules'        => 'Modules',
    'object_navigations'    => 'Navigations',
    'object_news'           => 'News',
    'object_opponents'      => 'Adversaires',
    'object_pages'          => 'Pages',
    'object_partners'       => 'Partenaires',
    'object_search'         => 'Rechercher',
    'object_servers'        => 'Serveurs',
    'object_shouts'         => 'Annonce',
    'object_slides'         => 'Slides',
    'object_streams'        => 'Streams',
    'object_teams'          => 'Equipes',
    'object_tournaments'    => 'Compétitions',
    'object_users'          => 'Utilisateurs',
    'object_videos'         => 'Vidéos',
    'object_visitors'       => 'Visiteurs',

    /*
     * Model names
     */
    'object_advert'             => 'Annonce',
    'object_advertcat'          => 'Catégorie d\'annonce',
    'object_award'              => 'Récompense',
    'object_contact_message'    => 'Contact',
    'object_join_message'       => 'Application',
    'object_country'            => 'Pays',
    'object_cup'                => 'Cup',
    'object_download'           => 'Téléchargement',
    'object_downloadcat'        => 'Catégorie de téléchargement',
    'object_event'              => 'Evènement',
    'object_post'               => 'Message',
    'object_thread'             => 'Sujet',
    'object_forum'              => 'Forum',
    'object_forum_post'         => 'Message Forum',
    'object_forum_report'       => 'Signalement Forum',
    'object_forum_thread'       => 'Sujet Forum',
    'object_gallery'            => 'Gallerie',
    'object_game'               => 'Jeu',
    'object_group'              => 'Groupe',
    'object_image'              => 'Image',
    'object_language'           => 'Langue',
    'object_map'                => 'Map',
    'object_match'              => 'Match',
    'object_match_score'        => 'Score du Match',
    'object_messages'           => 'Message',
    'object_module'             => 'Module',
    'object_navigation'         => 'Navigation',
    'object_newscat'            => 'Categorie de news',
    'object_opponent'           => 'Adversaire',
    'object_article'            => 'Article',
    'object_custom_page'        => 'Page perso',
    'object_fragment'           => 'Morceau',
    'object_page'               => 'Page',
    'object_pagecat'            => 'Catégorie de page',
    'object_partner'            => 'Partneraire',
    'object_partnercat'         => 'Type de partenaire',
    'object_server'             => 'Serveur',
    'object_slide'              => 'Slide',
    'object_slidecat'           => 'Catégorie de slide',
    'object_stream'             => 'Stream',
    'object_team'               => 'Equipe',
    'object_teamcat'            => 'Type d\'équipe',
    'object_tournament'         => 'Compétition',
    'object_user'               => 'Utilisateur',
    'object_video'              => 'Vidéo',

    /* 
     * Controller names (without "admin" prefix)
     */
    'object_advertcats'         => 'Advertisement-Categories',
    'object_login'              => 'Connexion',
    'object_registration'       => 'Inscription',
    'object_restore_password'   => 'Réinitialiser le mot de passe',
    'object_join'               => 'Rejoindre',
    'object_reports'            => 'Signalement',
    'object_forum_reports'      => 'Signalement Forum',
    'object_inbox'              => 'Boîte de réception',
    'object_outbox'             => 'Boîte d\'envoie',
    'object_newscats'           => 'Catégories d\'annonces',
    'object_articles'           => 'Articles',
    'object_custom_pages'       => 'Pages personnalisées',
    'object_partnercats'        => 'Type de partenaires',
    'object_slidecats'          => 'Catégories slides',
    'object_members'            => 'Membres',
    'object_activities'         => 'Activités',
    'object_threads'            => 'Sujets',
    'object_match_scores'       => 'Scores de match',
    'object_participants'       => 'Participants',

];
