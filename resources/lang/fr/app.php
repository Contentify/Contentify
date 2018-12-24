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
    'debug_warning'   => 'Avertissement: Le mode debug est activé, alors que le site est en production!',
    'space_warning'   => 'Il ne reste pratiquement plus d\'espace libre sur le disque dur (:0). Veuillez vider le cache, augmenter la mémoire ou contacter notre support.',

    /*
     * Errors
     */
    'not_found'     => 'Pas de ressource trouvée.',
    'not_possible'  => 'Désolé, cette action est actuellement indisponible.',
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
    'delete_error'  => "Erreur : Cet objet a :0 dépendances (Type: ':1')! Veuillez d'abord les enlever.",

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
    'open'          => 'Ouvert',

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
     * JavaScript
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
     * Permissions
     */
    'permissions'       => 'Permissions',
    'permission_none'   => 'Rien',
    'permission_read'   => 'Lecture',
    'permission_create' => 'Créer',
    'permission_update' => 'Modifier',
    'permission_delete' => 'Supprimer',

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
    'cancel'            => 'Annuler',
    'of'                => 'de',
    'role'              => 'Rôle',
    'message'           => 'Message',
    'subject'           => 'Sujet',
    'latest_msgs'       => 'Dernier Messages', // Admin dashboard feed messages
    'quick_access'      => 'Accès rapide', // Admin dashboard quick access
    'setting'           => 'Paramètre',
    'value'             => 'Valeur',
    'placeholder'       => 'Clé de développement par défaut, il faut la changer!', // Diag module
    'no_cron_job'       => 'Jamais. Aucune cron job générée?',
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
    'nothing_here'      => 'Désoler, il n\'y a pas encore de contenu. Veuillez réessayer plus tard.',
    'back'              => 'Retour',
    'general'           => 'Généralement',
	'metainfo'			=> 'Méta-Info',
    'services'          => 'Services',
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
    'cup_points'        => 'Tournoi points',
    'revenues'          => 'Revenus',
    'expenses'          => 'Dépenses',
    'paid'              => 'Paiement effectué',
    'note'              => 'Note',
    'person'            => 'Personne',
    'answer'            => 'Réponse',
    'export'            => 'Export',
    'download'          => 'Télécharger', // Verb - do not confuse with object_download!
    'option'            => 'Option',
    'gdpr'              => 'RGPD', // General Data Protection Regulation
    'gdpr_alert'        => 'Ce site Web utilise des cookies pour offrir la meilleure expérience utilisateur possible.',
    'privacy_policy'    => 'Politique de confidentialité',

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
    'object_cash_flows'     => 'Paiements',
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
    'object_polls'          => 'Enquêtes',
    'object_questions'      => 'Foire Aux Questions',
    'object_search'         => 'Rechercher',
    'object_servers'        => 'Serveurs',
    'object_shouts'         => 'Annonce',
    'object_slides'         => 'Slides',
    'object_streams'        => 'Streams',
    'object_teams'          => 'Equipes',
    'object_tournaments'    => 'Compétitions',
    #'object_update'         => 'Actualisation',
    'object_users'          => 'Utilisateurs',    
    'object_videos'         => 'Vidéos',
    'object_visitors'       => 'Visiteurs',

    /*
     * Model names
     */
    'object_advert'             => 'Annonce',
    'object_advert_cat'         => 'Catégorie d\'annonce',
    'object_article'            => 'Article',
    'object_award'              => 'Récompense',
    'object_cash_flow'          => 'Paiement',
    'object_contact_message'    => 'Contact',
    'object_country'            => 'Pays',
    'object_cup'                => 'Cup',
    'object_custom_page'        => 'Page perso',
    'object_download'           => 'Téléchargement',
    'object_download_cat'       => 'Catégorie de téléchargement',
    'object_event'              => 'Evènement',
    'object_forum'              => 'Forum',
    'object_forum_post'         => 'Message Forum',
    'object_forum_report'       => 'Signalement Forum',
    'object_forum_thread'       => 'Sujet Forum',
    'object_fragment'           => 'Morceau',
    'object_gallery'            => 'Gallerie',
    'object_game'               => 'Jeu',
    'object_image'              => 'Image',
    'object_join_message'       => 'Application',
    'object_language'           => 'Langue',
    'object_map'                => 'Map',
    'object_match'              => 'Match',
    'object_match_score'        => 'Score du Match',
    'object_message'            => 'Message',
    'object_module'             => 'Module',
    'object_navigation'         => 'Navigation',
    'object_news_cat'           => 'Categorie de news',
    'object_opponent'           => 'Adversaire',
    'object_page'               => 'Page',
    'object_page_cat'           => 'Catégorie de page',
    'object_partner'            => 'Partneraire',
    'object_partner_cat'        => 'Type de partenaire',
    'object_poll'               => 'Enquête',
    'object_post'               => 'Message',
    'object_question'           => 'Question',
    'object_question_cat'       => 'Catégorie de question',
    'object_role'               => 'Rôle',
    'object_server'             => 'Serveur',
    'object_slide'              => 'Slide',
    'object_slide_cat'          => 'Catégorie de slide',
    'object_stream'             => 'Stream',
    'object_team'               => 'Equipe',
    'object_team_cat'           => 'Type d\'équipe',
    'object_thread'             => 'Sujet',
    'object_tournament'         => 'Compétition',
    'object_user'               => 'Utilisateur',
    'object_video'              => 'Vidéo',

    /* 
     * Controller names (without "admin" prefix)
     */
    'object_activities'         => 'Activités',
    'object_advert_cats'        => 'Advertisement-Categories',
    'object_articles'           => 'Articles',
    'object_custom_pages'       => 'Pages personnalisées',
    'object_download_cats'      => 'Catégories de téléchargements',
    'object_forum_reports'      => 'Signalement Forum',
    'object_inbox'              => 'Boîte de réception',
    'object_join'               => 'Rejoindre',
    'object_login'              => 'Connexion',
    'object_match_scores'       => 'Scores de match',
    'object_members'            => 'Membres',
    'object_news_cats'          => 'Catégories d\'annonces',
    'object_outbox'             => 'Boîte d\'envoie',
    'object_participants'       => 'Participants',
    'object_partner_cats'       => 'Type de partenaires',
    'object_question_cats'      => 'Catégories de questions',
    'object_registration'       => 'Inscription',
    'object_reports'            => 'Signalement',
    'object_restore_password'   => 'Réinitialiser le mot de passe',
    'object_slide_cats'         => 'Catégories slides',
    'object_threads'            => 'Sujets',

];
