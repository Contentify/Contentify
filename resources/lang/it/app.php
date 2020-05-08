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
    'spam_protection' => 'Per la protezione da spam, è necessario che aspetti qualche secondo prima di poter inviare questo form.',
    'debug_warning'   => 'Attenzione: la modalità di debug è attiva, ma questo non sembra essere un server di sviluppo!',
    'not_found'       => 'Risorsa non trovata.',
    'not_possible'    => 'Scusa, questa azione non è disponibile ora.',
    'space_warning'   => 'C\'è poco spazio libero rimasto sul disco (:0). Per favore pulisci la cache, aumenta lo spazio o contatta il nostro team di supporto.',

    /*
     * User permissions
     */ 
    'no_auth'       => 'Accesso negato. Per favore effettua l\'accesso.',
    'access_denied' => 'Accesso negato. Non hai i permessi.',
    
    /*
     * Forms
     */
    'save'      => 'Salva',
    'apply'     => 'Applica',
    'reset'     => 'Reimposta',
    'update'    => 'Aggiorna',

    /*
     * Auto CRUD handling (and related stuff)
     */
    'created'       => ':0 creato.',
    'updated'       => ':0 aggiornato.',
    'deleted'       => ':0 cancellato.',
    'restored'      => ':0 ripristinato.',
    'list_empty'    => 'Per favore, crea almeno un ":0" prima di continuare.',
    'invalid_image' => 'Formato immagine non valido',
    'bad_extension' => "Il caricamento di file con estensione ':0' non è consentito.",
    'delete_error'  => "Errore: Questo oggetto ha :0 dipendenze (Tipo: ':1')! Per favore rimuovi prima loro.",

    /*
     * Model index building
     */
    'create'        => 'Crea nuovo',
    'categories'    => 'Categorie',
    'config'        => 'Configurazione',
    'actions'       => 'Azioni',
    'edit'          => 'Modifica',
    'delete'        => 'Cancella',
    'restore'       => 'Ripristina',
    
    'id'            => 'ID',
    'index'         => 'Indice',
    'title'         => 'Titolo',
    'short_title'   => 'Titolo breve',
    'author'        => 'Autore',
    'creator'       => 'Creatore',
    'category'      => 'Categorie',
    'type'          => 'Tipo',
    'provider'      => 'Provider',
    'text'          => 'Testo',
    'code'          => 'Codice',
    'description'   => 'Descrizione',
    'image'         => 'Immagine',
    'icon'          => 'Icona',
    'date'          => 'Data',
    'published'     => 'Pubblicato',
    'starts_at'     => 'Inizia il',
    'created_at'    => 'Creato il',
    'updated_at'    => 'Aggiornato il',
    'achieved_at'   => 'Ottenuto il',
    'internal'      => 'Interno',
    'featured'      => 'In evidenza',
    'state'         => 'Stato',
    'new'           => 'Nuovo',
    'open'          => 'Aperto',

    /*
     * Misc
     */
    'sorting'       => 'Ordina',
    'search'        => 'Cerca',
    'recycle_bin'   => 'Cestino',
    'max_size'      => 'Grandezza massima: :0Bytes',
    'accessed'      => 'visitato',
    'no_items'      => 'Non ci sono oggetti.',
    'share_this'    => 'Condividi ',
    'save_to_del'   => 'Salva questo contenuto per cancellare il file.',

    /*
     * JavaScript
     */
    'request_failed'    => 'Errore: Richiesta fallita.',
    'delete_item'       => 'Cancellare questo oggetto?',
    'perform_action'    => 'Eseguire questa zione?',

    /*
     * Backend template
     */
    'no_js'             => 'JavaScript non abilitato. Per favore abilita JavaScript!',
    'admin_dashboard'   => 'Pannello di amministrazione',
    'new_messages'      => 'Un nuovo messaggio|:count nuovi messaggi',
    'top'               => 'Vai in cima',
    'help'              => 'Aiuto',
    'welcome'           => 'Benvenuto',

    /*
     * Permissions
     */
    'permissions'       => 'Permessi',
    'permission_none'   => 'Nessuno',
    'permission_read'   => 'Lettura',
    'permission_create' => 'Creazione',
    'permission_update' => 'Aggiornamento',
    'permission_delete' => 'Eliminazione',

    /*
     * Comments
     */
    'comments'              => 'Commenti',
    'comment_create_fail'   => 'Impossibile creare il commento. :0',
    'comment_update_fail'   => 'Impossibile aggiornare il commento. :0',
    'enable_comments'       => 'Abilita i commenti',

    /*
     * Captcha
     */
    'captcha_invalid'   => 'Il codice captcha non è valido!',

    /*
     * General terms
     */
    'date_time'         => 'Data e ora',
    'yes'               => 'Si',
    'no'                => 'No',
    'valid'             => 'valido',
    'invalid'           => 'non valido',
    'enabled'           => 'Abilitato',
    'disabled'          => 'Disabilitato',
    'read_more'         => 'Continua a a leggere...',
    'name'              => 'Nome',
    'username'          => 'Username',
    'email'             => 'Email',
    'password'          => 'Password',
    'file'              => 'File',
    'link'              => 'Collegamento',
    'links'             => 'Collegamenti',
    'send'              => 'Invia',
    'position'          => 'Posizione',
    'location'          => 'Luogo',
    'url'               => 'URL',
    'quote'             => 'Citazione',
    'size'              => 'Grandezza',
    'ip'                => 'IP',
    'online'            => 'Online',
    'offline'           => 'Offline',
    'viewers'           => 'Spettatori',
    'task'              => 'Operazione',
    'add_tags'          => 'Aggiungi tags',
    'reply'             => 'Rispondi',
    'latest'            => 'Più recente',
    'item'              => 'Oggetto',
    'home'              => 'Home',
    'remove'            => 'Rimuovi',
    'cancel'            => 'Cancella',
    'of'                => 'di',
    'role'              => 'Ruolo',
    'message'           => 'Messaggio',
    'subject'           => 'Oggetto',
    'latest_msgs'       => 'Feed-Messaggi più recenti', // Admin dashboard feed messages
    'quick_access'      => 'Accesso rapido', // Admin dashboard quick access
    'setting'           => 'Impostazioni',
    'value'             => 'Valore',
    'placeholder'       => 'Placeholder, non cambiare!', // Diagnostics module
    'no_cron_job'       => 'Mai. Non è stato creato nessun cron job?',
    'compiled'          => 'compilato',
    'website'           => 'Website',
    'calendar'          => 'Calendario',
    'team_members'      => 'Membri delle Squadre',
    'profile'           => 'Proilo',
    'edit_profile'      => 'Modifica profilo',
    'add'               => 'Aggiungi',
    'logout'            => 'Logout',
    'install'           => 'Installa',
    'preview'           => 'Anteprima',
    'total'             => 'Totale',
    'nothing_new'       => 'Scusa, niente di nuovo qui. Ritorna più tardi.',
    'nothing_here'      => 'Scusa, non ci sono ancora contenuti. Per favore, prova di nuovo.',
    'back'              => 'Indietro',
    'general'           => 'Generale',
    'metainfo'			=> 'Meta Info',
    'services'          => 'Servizi',
    'theme'             => 'Tema',
    'theme_christmas'   => 'Modalità Natale (neve)',
    'theme_snow_color'  => 'Colore di fiocchi',
    'loading'           => 'Caricamento',
    'lineup'            => 'Formazione',
    'translate'         => 'Traduci',
    'successful'        => 'Successo!',
    'slots'             => 'Spazi',
    'mode'              => 'Modalità',
    'prize'             => 'Premio',
    'closed'            => 'Chiuso',
    'leave'             => 'Abbandona',
    'join'              => 'Unisciti',
    'confirm'           => 'Conferma',
    'rules'             => 'Regolamento',
    'round'             => 'Round',
    'cup_points'        => 'Punti coppa',
    'revenues'          => 'Ricavo',
    'expenses'          => 'Spese',
    'paid'              => 'Pagato',
    'note'              => 'Nota',
    'person'            => 'Persona',
    'answer'            => 'Risposta',
    'export'            => 'Esporta',
    'download'          => 'Scarica', // Verb - do not confuse with object_download!
    'option'            => 'Opzioni',
    'gdpr'              => 'GDPR', // General Data Protection Regulation
    'gdpr_alert'        => 'Questo sito utilizza i cookies per fornire un\'esperienza migliore al utente.',
    'privacy_policy'    => 'Informativa sulla Privacy',

    /*
     * Days
     */
    'yeah'          => 'Anno',
    'month'         => 'Mese',
    'day'           => 'Giorno',
    'today'         => 'Oggi',
    'yesterday'     => 'Ieri',
    'tomorrow'      => 'Domani',
    'monday'        => 'Lunedì',
    'tuesday'       => 'Martedì',
    'wednesday'     => 'Mercoledì',
    'thursday'      => 'Giovedì',
    'friday'        => 'Venerdì',
    'saturday'      => 'Sabato',
    'sunday'        => 'Domenica', 

    /*
     * Module names
     */
    'object_adverts'        => 'Pubblicità',
    'object_auth'           => 'Autenticazione',
    'object_awards'         => 'Premi',
    'object_cash_flows'     => 'Flusso monetario',
    'object_comments'       => 'Commenti',
    'object_config'         => 'Configurazione',
    'object_contact'        => 'Contatti',
    'object_countries'      => 'Nazioni',
    'object_cups'           => 'Coppe',
    'object_dashboard'      => 'Pannello amministrazione',
    'object_diag'           => 'Diagnostica',
    'object_downloads'      => 'Downloads',
    'object_events'         => 'Eventi',
    'object_forums'         => 'Forum',
    'object_friends'        => 'amici',
    'object_galleries'      => 'Gallerie',
    'object_games'          => 'Giochi',
    'object_images'         => 'Immagini',
    'object_languages'      => 'Lingua',
    'object_maps'           => 'Mappe',
    'object_matches'        => 'Partite',
    'object_messages'       => 'Messaggi',
    'object_modules'        => 'Moduli',
    'object_navigations'    => 'Navigazione',
    'object_news'           => 'Notizie',
    'object_opponents'      => 'Avversari',
    'object_pages'          => 'Pagine',
    'object_partners'       => 'Partners',
    'object_polls'          => 'Votazioni',
    'object_questions'      => 'FAQ',
    'object_roles'          => 'Ruoli',
    'object_search'         => 'Ricerca',
    'object_servers'        => 'Servers',
    'object_shouts'         => 'Shouts',
    'object_slides'         => 'Slides',
    'object_streams'        => 'Streams',
    'object_teams'          => 'Squadre',
    'object_tournaments'    => 'Tornei',
    #'object_update'         => 'Update',
    'object_users'          => 'Utenti',
    'object_videos'         => 'Video',
    'object_visitors'       => 'Visitatori',

    /*
     * Model names
     */
    'object_advert'             => 'Pubblicità',
    'object_advert_cat'         => 'Pubblicità-Categoria',
    'object_article'            => 'Articoli',
    'object_award'              => 'Premi',
    'object_cash_flow'          => 'Flusso monetario',
    'object_contact_message'    => 'Messaggia',
    'object_country'            => 'Paese',
    'object_cup'                => 'Coppe',
    'object_custom_page'        => 'Pagina personalizzata',
    'object_download'           => 'Download',
    'object_download_cat'       => 'Download-Categoria',
    'object_event'              => 'Eventi',
    'object_forum'              => 'Forum',
    'object_forum_post'         => 'Post del Forum',
    'object_forum_report'       => 'Report del Forum',
    'object_forum_thread'       => 'Thread del Forum',
    'object_fragment'           => 'Frammento',
    'object_gallery'            => 'Galleria',
    'object_game'               => 'Gioco',
    'object_image'              => 'Immagine',
    'object_join_message'       => 'Applicazione',
    'object_language'           => 'Lingua',
    'object_map'                => 'Mappa',
    'object_match'              => 'Partita',
    'object_match_score'        => 'Risultato della partita',
    'object_message'            => 'Messagio',
    'object_module'             => 'Modulo',
    'object_navigation'         => 'Navigatione',
    'object_news_cat'           => 'Notizie-Categoria',
    'object_opponent'           => 'Avversario',
    'object_page'               => 'Pagina',
    'object_page_cat'           => 'Pagina-Categoria',
    'object_partner'            => 'Sposor',
    'object_partner_cat'        => 'Sposor-Categoria',
    'object_poll'               => 'Votazioni',
    'object_post'               => 'Post',
    'object_question'           => 'Domanda',
    'object_question_cat'       => 'Domanda-Categoria',
    'object_role'               => 'Ruolo',
    'object_server'             => 'Server',
    'object_slide'              => 'Slide',
    'object_slide_cat'          => 'Slide-Categoria',
    'object_stream'             => 'Stream',
    'object_team'               => 'Squadra',
    'object_team_cat'           => 'Squadra-Categoria',
    'object_thread'             => 'Thread',
    'object_tournament'         => 'Tornei',
    'object_user'               => 'Uteni',
    'object_video'              => 'Video',

    /* 
     * Controller names (without "admin" prefix)
     */
    'object_activities'         => 'Attività',
    'object_advert_cats'        => 'Pubblicità-Categorie',
    'object_articles'           => 'Articoli',
    'object_custom_pages'       => 'Pagine personalizzate',
    'object_download_cats'      => 'Download-Categorie',
    'object_forum_reports'      => 'Report del Forum',
    'object_inbox'              => 'In ingresso',
    'object_join'               => 'Unisci',
    'object_login'              => 'Login',
    'object_match_scores'       => 'Risultati partite',
    'object_members'            => 'Membri',
    'object_news_cats'          => 'Notizie-Categorie',
    'object_outbox'             => 'In uscita',
    'object_participants'       => 'Participanti',
    'object_partner_cats'       => 'Sposor-Categorie',
    'object_question_cats'      => 'Domande-Categorie',
    'object_registration'       => 'Registrazione',
    'object_reports'            => 'Reports',
    'object_restore_password'   => 'Ripristina Password',
    'object_slide_cats'         => 'Slide-Categorie',
    'object_threads'            => 'Threads',

];
