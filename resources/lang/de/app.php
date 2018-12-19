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
     * Date format pattern
     */
    'date_format'     => 'd.m.Y',      // PHP default, for Carbon ( http://www.php.net/manual/en/function.date.php )
    'date_format_alt' => 'DD.MM.YYYY', // for the date time picker UI widget

    /*
     * Security
     */
    'spam_protection' => 'Um Spam vorzubeugen, müssen Sie einige Sekunden warten, ehe Sie dieses Formular absenden können.',
    'debug_warning'   => 'Warnung: Der Debug-Modus ist aktiviert, obwohl dies ein Produktivserver zu sein scheint!',
    'space_warning'   => 'Es ist kaum noch freier Festplattenspeicher vorhanden (:0). Bitte den Cache leeren, den Speicher vergrößern oder unseren Support kontaktieren.',

    /*
     * Errors
     */
    'not_found'     => 'Ressource konnte nicht gefunden werden.',
    'not_possible'  => 'Entschuldigung, diese Aktion ist derzeit nicht verfügbar.',
    'no_auth'       => 'Zugriff verweigert. Bitte melden Sie sich an.',
    'access_denied' => 'Zugriff verweigert. Sie verfügen nicht über die benötigten Berechtigungen.',
    
    /*
     * Forms
     */
    'save'      => 'Speichern',
    'apply'     => 'Übernehmen',
    'reset'     => 'Zurücksetzen',
    'update'    => 'Updaten',

    /*
     * Auto CRUD handling (and related stuff)
     */
    'created'       => ':0 erstellt.',
    'updated'       => ':0 aktualisiert.',
    'deleted'       => ':0 gelöscht.',
    'restored'      => ':0 wiederhergestellt.',
    'list_empty'    => 'Bitte erstellen Sie mindestens ein/eine ":0", ehe Sie fortfahren.',
    'invalid_image' => 'Ungültige Bilddatei',
    'bad_extension' => "Das Hochladen von Dateien mit der Endung ':0' ist nicht erlaubt.",
    'delete_error'  => "Fehler: Dieses Objekt besitzt :0 Abhängigkeiten (Typ: ':1')! Bitte diese zunächst aufheben.",

    /*
     * Model index building
     */
    'create'        => 'Neu erstellen',
    'categories'    => 'Kategorien',
    'config'        => 'Konfigurieren',
    'actions'       => 'Aktionen',
    'edit'          => 'Editieren',
    'delete'        => 'Löschen',
    'restore'       => 'Wiederherstellen',
    
    'id'            => 'ID',
    'index'         => 'Index',
    'title'         => 'Titel',
    'short_title'   => 'Kurzer Titel',
    'author'        => 'Autor',
    'creator'       => 'Ersteller',
    'category'      => 'Kategorie',
    'type'          => 'Typ',
    'provider'      => 'Provider',
    'text'          => 'Text',
    'code'          => 'Code',
    'description'   => 'Beschreibung',
    'image'         => 'Bild',
    'icon'          => 'Symbol',
    'date'          => 'Datum',
    'published'     => 'Veröffentlicht',
    'starts_at'     => 'Beginnt am',
    'created_at'    => 'Erstellt am',
    'updated_at'    => 'Editiert am',
    'achieved_at'   => 'Erreicht am',
    'internal'      => 'Intern',
    'featured'      => 'Featured',
    'state'         => 'Status',
    'new'           => 'Neu',
    'open'          => 'Offen',

    /*
     * Misc
     */
    'sorting'       => 'Sortierung',
    'search'        => 'Suchen',
    'recycle_bin'   => 'Papierkorb',
    'max_size'      => 'Max. Größe: :0Bytes',
    'accessed'      => 'aufgerufen',
    'no_items'      => 'Es gibt noch keine Einträge.',
    'share_this'    => 'Teilen',
    'save_to_del'   => 'Speichern Sie das Formular, um die Datei zu löschen.',

    /*
     * JavaScript
     */
    'request_failed'    => 'Fehler: Anfrage fehlgeschlagen.',
    'delete_item'       => 'Soll dieses Objekt wirklich gelöscht werden?',
    'perform_action'    => 'Soll diese Aktion wirklich durchgeführt werden?',

    /*
     * Backend template
     */
    'no_js'             => 'JavaScript ist nicht aktiviert. Bitte aktivieren Sie JavaScript!',
    'admin_dashboard'   => 'Admin-Dashboard',
    'new_messages'      => 'Eine neue Nachricht|:count neue Nachrichten',
    'top'               => 'Nach oben',
    'help'              => 'Hilfe',
    'welcome'           => 'Willkommen',

    /*
     * Permissions
     */
    'permissions'       => 'Berechtigungen',
    'permission_none'   => 'Keine',
    'permission_read'   => 'Lesen',
    'permission_create' => 'Erstellen',
    'permission_update' => 'Editieren',
    'permission_delete' => 'Löschen',

    /*
     * Comments
     */
    'comments'              => 'Kommentare',
    'comment_create_fail'   => 'Kann Kommentar nicht erstellen. :0',
    'comment_update_fail'   => 'Kann Kommentar nicht editieren. :0',
    'enable_comments'       => 'Kommentare aktivieren',

    /*
     * Captcha
     */
    'captcha_invalid'   => 'Der Captcha-Code ist ungültig!',

    /*
     * General terms
     */
    'date_time'         => 'Datum & Zeit',
    'yes'               => 'Ja',
    'no'                => 'Nein',
    'valid'             => 'gültig',
    'invalid'           => 'ungültig',
    'enabled'           => 'Aktiviert',
    'disabled'          => 'Deaktiviert',
    'read_more'         => 'Weiterlesen...',
    'name'              => 'Name',
    'username'          => 'Benutzername',
    'email'             => 'Email',
    'password'          => 'Passwort',
    'file'              => 'Datei',
    'link'              => 'Link',
    'links'             => 'Links',
    'send'              => 'Senden',
    'position'          => 'Position',
    'location'          => 'Ort',
    'url'               => 'URL',
    'quote'             => 'Zitieren',
    'size'              => 'Größe',
    'ip'                => 'IP',
    'online'            => 'Online',
    'offline'           => 'Offline',
    'viewers'           => 'Betrachter',
    'task'              => 'Aufgabe',
    'add_tags'          => 'Tags hinzufügen',
    'reply'             => 'Antworten',
    'latest'            => 'Neueste',
    'item'              => 'Objekt',
    'home'              => 'Start',
    'remove'            => 'Entfernen',
    'cancel'            => 'Abbrechen',
    'of'                => 'von',
    'role'              => 'Rolle',
    'message'           => 'Nachricht',
    'subject'           => 'Titel', // Vague translation
    'latest_msgs'       => 'Neueste Feed-Nachrichten', // Admin dashboard feed messages
    'quick_access'      => 'Schnellzugriff', // Admin dashboard quick access
    'setting'           => 'Einstellung',
    'value'             => 'Wert',
    'placeholder'       => 'Platzhalter, bitte ändern!', // Diag module
    'no_cron_job'       => 'Niemals. Keinen Cron-Job erstellt?',
    'compiled'          => 'kompiliert',
    'website'           => 'Website',
    'calendar'          => 'Kalender',
    'team_members'      => 'Team-Mitglieder',
    'profile'           => 'Profil',
    'edit_profile'      => 'Profil editieren',
    'add'               => 'Hinzufügen',
    'logout'            => 'Abmelden',
    'install'           => 'Installieren',
    'preview'           => 'Vorschau',
    'total'             => 'Gesamt',
    'nothing_new'       => 'Entschuldigung, es gibt noch nichts Neues. Bitte versuchen Sie es später erneut.',
    'nothing_here'      => 'Entschuldigung, es gibt hier noch keinen Inhalt. Bitte versuchen Sie es später erneut.',
    'back'              => 'Zurück',
    'general'           => 'Allgemein',
    'services'          => 'Services',
    'metainfo'			=> 'Metainfos',
    'theme'             => 'Theme',
    'theme_christmas'   => 'Weihnachtsmodus (Schnee)',
    'theme_snow_color'  => 'Flockenfarbe',
    'loading'           => 'Lade',
    'lineup'            => 'Aufstellung',
    'translate'         => 'Übersetzen',
    'successful'        => 'Erfolgreich!',
    'slots'             => 'Slots',
    'mode'              => 'Modus',
    'prize'             => 'Preis',
    'closed'            => 'Geschlossen',
    'leave'             => 'Verlassen',
    'join'              => 'Beitreten',
    'confirm'           => 'Bestätigen',
    'rules'             => 'Regeln',
    'round'             => 'Runde',
    'cup_points'        => 'Cup-Punkte',
    'revenues'          => 'Einnahmen',
    'expenses'          => 'Ausgaben',
    'paid'              => 'Zahlung erfolgt',
    'note'              => 'Notiz',
    'person'            => 'Person',
    'answer'            => 'Antwort',
    'export'            => 'Export',
    'download'          => 'Herunterladen', // Verb - do not confuse with object_download!
    'option'            => 'Option',
    'gdpr'              => 'DSVGO', // General Data Protection Regulation
    'gdpr_alert'        => 'Diese Website nutzt Cookies, um die bestmögliche Nutzung zu bieten.',
    'privacy_policy'    => 'Datenschutzerklärung',

    /*
     * Days
     */
    'year'          => 'Jahr',
    'month'         => 'Monat',
    'day'           => 'Tag',
    'today'         => 'Heute',
    'yesterday'     => 'Gestern',
    'tomorrow'      => 'Morgen',
    'monday'        => 'Montag',
    'tuesday'       => 'Dienstag',
    'wednesday'     => 'Mittwoch',
    'thursday'      => 'Donnertstag',
    'friday'        => 'Freitag',
    'saturday'      => 'Samstag',
    'sunday'        => 'Sonntag', 

    /*
     * Module names
     */
    'object_adverts'        => 'Werbung',
    'object_auth'           => 'Authentifizierung ',
    'object_awards'         => 'Awards',
    'object_cash_flows'     => 'Zahlungen',
    'object_comments'       => 'Kommentare',
    'object_config'         => 'Einstellungen',
    'object_contact'        => 'Kontakt',
    'object_countries'      => 'Staaten',
    'object_cups'           => 'Cups',
    'object_dashboard'      => 'Dashboard',
    'object_diag'           => 'Diagnose',
    'object_downloads'      => 'Downloads',
    'object_events'         => 'Events',
    'object_forums'         => 'Forum',
    'object_friends'        => 'Freunde',
    'object_galleries'      => 'Galerien',
    'object_games'          => 'Spiele',
    'object_images'         => 'Bilder',
    'object_languages'      => 'Sprachen',
    'object_maps'           => 'Maps',
    'object_matches'        => 'Matches',
    'object_messages'       => 'Nachrichten',
    'object_modules'        => 'Module',
    'object_navigations'    => 'Navigationen',
    'object_news'           => 'News',
    'object_opponents'      => 'Herausforderer',
    'object_pages'          => 'Seiten',
    'object_partners'       => 'Partner',
    'object_polls'          => 'Umfragen',
    'object_questions'      => 'Häufig gestellte Fragen',
    'object_roles'          => 'Rollen',
    'object_search'         => 'Suche',
    'object_servers'        => 'Server',
    'object_shouts'         => 'Shoutbox',
    'object_slides'         => 'Slides',
    'object_streams'        => 'Streams',
    'object_teams'          => 'Teams',
    'object_tournaments'    => 'Turniere',
    #'object_update'         => 'Update',
    'object_users'          => 'Benutzer',
    'object_videos'         => 'Videos',
    'object_visitors'       => 'Besucher',

    /*
     * Model names
     */
    'object_advert'             => 'Werbung',
    'object_advert_cat'         => 'Werbung-Kategorie',
    'object_article'            => 'Artikel',
    'object_award'              => 'Award',
    'object_cash_flow'          => 'Zahlung',
    'object_contact_message'    => 'Kontaktnachricht',
    'object_country'            => 'Staat',
    'object_cup'                => 'Cup',
    'object_custom_page'        => 'Eigene Seite',
    'object_download'           => 'Download',
    'object_download_cat'       => 'Download-Kategorie',
    'object_event'              => 'Event',
    'object_forum'              => 'Forum',
    'object_forum_post'         => 'Forum-Beitrag',
    'object_forum_report'       => 'Forum-Meldung',
    'object_forum_thread'       => 'Forum-Thread',
    'object_fragment'           => 'Fragment',
    'object_gallery'            => 'Galerie',
    'object_game'               => 'Spiel',
    'object_image'              => 'Bild',
    'object_join_message'       => 'Bewerbung',
    'object_language'           => 'Sprache',
    'object_map'                => 'Map',
    'object_match'              => 'Match',
    'object_match_score'        => 'Matchergebnis',
    'object_message'            => 'Nachricht',
    'object_module'             => 'Modul',
    'object_navigation'         => 'Navigation',
    'object_news_cat'           => 'News-Kategorie',
    'object_opponent'           => 'Herausforderer',
    'object_page'               => 'Seite',
    'object_page_cat'           => 'Seiten-Kategorie',
    'object_partner'            => 'Partner',
    'object_partner_cat'        => 'Partner-Kategorie',
    'object_poll'               => 'Umfrage',
    'object_post'               => 'Beitrag',
    'object_question'           => 'Frage',
    'object_question_cat'       => 'Fragen-Kategorie',
    'object_role'               => 'Rolle',
    'object_server'             => 'Server',
    'object_slide'              => 'Slide',
    'object_slide_cat'          => 'Slide-Kategorie',
    'object_stream'             => 'Stream',
    'object_team'               => 'Team',
    'object_team_cat'           => 'Team-Kategorie',
    'object_thread'             => 'Thread',
    'object_tournament'         => 'Turnier',
    'object_user'               => 'Benutzer',
    'object_video'              => 'Video',

    /* 
     * Controller names (without "admin" prefix)
     */
    'object_activities'         => 'Aktivitäten',
    'object_advert_cats'        => 'Werbung-Kategorien',
    'object_articles'           => 'Artikel',
    'object_custom_pages'       => 'Eigene Seiten',
    'object_download_cats'      => 'Download-Kategorien',
    'object_forum_reports'      => 'Forum-Meldungen',
    'object_inbox'              => 'Eingang',
    'object_join'               => 'Bewerbung',
    'object_login'              => 'Anmeldung',
    'object_match_scores'       => 'Match-Ergebnisse',
    'object_members'            => 'Mitglieder',
    'object_news_cats'          => 'News-Kategorien',
    'object_outbox'             => 'Ausgang',
    'object_participants'       => 'Teilnehmer',
    'object_partner_cats'       => 'Partner-Kategorien',
    'object_question_cats'      => 'Frage-Kategorien',
    'object_registration'       => 'Registrierung',
    'object_reports'            => 'Meldungen',
    'object_restore_password'   => 'Passwort Wiederherstellen',
    'object_slide_cats'         => 'Slide-Kategorien',
    'object_threads'            => 'Threads',

];
