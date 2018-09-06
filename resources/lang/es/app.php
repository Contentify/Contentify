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
    'date_format'        => 'Y-m-d',         // PHP default, for Carbon ( http://www.php.net/manual/en/function.date.php )
    'date_format_alt'    => 'YYYY-MM-DD',    // for the date time picker UI widget
   
    /*
     * Security
     */
    'spam_protection' => 'Por protección anti SPAM, tienes que esperar unos segundos antes de enviar el formulario.',
    'debug_warning'   => 'Aviso: El modo Debug está habilitado y esto no parece ser un servidor de desarrollo!',

    /*
     * Errors
     */
    'not_found'     => 'Recurso no encontrado.',
    'not_possible'  => 'Lo sentimos, esta acción no está permitida ahora mismo.',

    /*
     * User permissions
     */ 
    'no_auth'       => 'Acceso denegado. Por favor identifíquese.',
    'access_denied' => 'Acceso denegado. Necesitas permisos.',
    
    /*
     * Forms
     */
    'save'      => 'Guardar',
    'apply'     => 'Aplicar',
    'reset'     => 'Reiniciar',
    'update'    => 'Actualizar',

    /*
     * Auto CRUD handling (and related stuff)
     */
    'created'       => ':0 creados.',
    'updated'       => ':0 actualizados.',
    'deleted'       => ':0 eliminados.',
    'restored'      => ':0 restaurados.',
    'list_empty'    => 'Por favor crea al menos un ":0" antes de continuar.',
    'invalid_image' => 'Archivo de imagen inválido',
    'bad_extension' => "La carga de archivos con la extensión ':0' no está permitida.",
    'delete_error'  => "Error: El objeto tiene :0 dependencias (Tipo: ':1')! Por favor elimínalas primero.",

    /*
     * Model index building
     */
    'create'        => 'Create new',
    'categories'    => 'Categorías',
    'config'        => 'Configuración',
    'actions'       => 'Acciones',
    'edit'          => 'Editar',
    'delete'        => 'Borrar',
    'restore'       => 'Restaurar',
    
    'id'            => 'ID',
    'index'         => 'Índice',
    'title'         => 'Título',
    'short_title'   => 'Título abreviado',
    'author'        => 'Autor',
    'creator'       => 'Creador',
    'category'      => 'Categoría',
    'type'          => 'Tipo',
    'provider'      => 'Proveedor',
    'text'          => 'Texto',
    'code'          => 'Código',
    'description'   => 'Descripción',
    'image'         => 'Imagen',
    'icon'          => 'Icono',
    'date'          => 'Fecha',
    'published'     => 'Publicado',
    'starts_at'     => 'Empieza el',
    'created_at'    => 'Creado el',
    'updated_at'    => 'Actualizado el',
    'achieved_at'   => 'Terminado el',
    'internal'      => 'Interno',
    'featured'      => 'Destacado',
    'state'         => 'Estado',
    'new'           => 'Nuevo',

    /*
     * Misc
     */
    'sorting'       => 'Ordenando',
    'search'        => 'Buscar',
    'recycle_bin'   => 'Papelera de reciclaje',
    'max_size'      => 'Tamaño máx.: :0Bytes',
    'accessed'      => 'acceso',
    'no_items'      => 'No hay elementos.',
    'share_this'    => 'Compartir',
    'save_to_del'   => 'Guarda este formulario para borrar este archivo.',

    /*
     * JS
     */
    'request_failed'    => 'Error: Solicitud fallida.',
    'delete_item'       => 'Eliminar este elemento?',
    'perform_action'    => 'Ejecutar esta acción?',

    /*
     * Backend template
     */
    'no_js'             => 'JavaScript no está habilitado. Por favor, habilita JavaScript!',
    'admin_dashboard'   => 'Panel de Administración',
    'new_messages'      => 'Un nuevo mensaje|:count nuevos mensajes',
    'top'               => 'Arriba',
    'help'              => 'Ayuda',
    'welcome'           => 'Bienvenido',

    /*
     * Permissions
     */
    'permissions'       => 'Permisos',
    'permission_none'   => 'Nada',
    'permission_read'   => 'Leer',
    'permission_create' => 'Crear',
    'permission_update' => 'Editar',
    'permission_delete' => 'Borrar',

    /*
     * Comments
     */
    'comments'              => 'Comentarios',
    'comment_create_fail'   => 'Cannot create comment. :0',
    'comment_update_fail'   => 'Cannot update comment. :0',
    'enable_comments'       => 'Enable comments',

    /*
     * Captcha
     */
    'captcha_invalid'   => 'The captcha code is invalid!',

    /*
     * General terms
     */
    'date_time'         => 'Date & Time',
    'yes'               => 'Yes',
    'no'                => 'No',
    'valid'             => 'valid',
    'invalid'           => 'invalid',
    'enabled'           => 'Enabled',
    'disabled'          => 'Disabled',
    'read_more'         => 'Read more...',
    'name'              => 'Name',
    'username'          => 'Username',
    'email'             => 'Email',
    'password'          => 'Password',
    'file'              => 'File',
    'link'              => 'Link',
    'links'             => 'Links',
    'send'              => 'Send',
    'position'          => 'Position',
    'location'          => 'Location',
    'url'               => 'URL',
    'quote'             => 'Quote',
    'size'              => 'Size',
    'ip'                => 'IP',
    'online'            => 'Online',
    'offline'           => 'Offline',
    'viewers'           => 'Viewers',
    'task'              => 'Task',
    'add_tags'          => 'Add tags',
    'reply'             => 'Reply',
    'latest'            => 'Latest',
    'item'              => 'Item',
    'home'              => 'Home',
    'remove'            => 'Remove',
    'cancel'            => 'Cancel',
    'of'                => 'of',
    'role'              => 'Role',
    'message'           => 'Message',
    'subject'           => 'Subject',
    'latest_msgs'       => 'Latest Feed-Messages', // Admin dashboard feed messages
    'quick_access'      => 'Quick Access', // Admin dashboard quick access
    'setting'           => 'Setting',
    'value'             => 'Value',
    'placeholder'       => 'Placeholder, please change!', // Diagnostics module
    'no_cron_job'       => 'Never. No cron job created?',
    'compiled'          => 'compiled',
    'website'           => 'Website',
    'calendar'          => 'Calendar',
    'team_members'      => 'Team-Members',
    'profile'           => 'Profile',
    'edit_profile'      => 'Edit Profile',
    'add'               => 'Add',
    'logout'            => 'Logout',
    'install'           => 'Install',
    'preview'           => 'Preview',
    'total'             => 'Total',
    'nothing_new'       => 'Sorry, nothing new. Please return later.',
    'back'              => 'Back',
    'general'           => 'General',
    'services'          => 'Services',
    'theme'             => 'Theme',
    'theme_christmas'   => 'Christmas Mode (Snow)',
    'theme_snow_color'  => 'Flakes color',
    'loading'           => 'Loading',
    'lineup'            => 'Lineup',
    'translate'         => 'Translate',
    'successful'        => 'Successful!',
    'slots'             => 'Slots',
    'mode'              => 'Mode',
    'prize'             => 'Prize',
    'closed'            => 'Closed',
    'leave'             => 'Leave',
    'join'              => 'Join',
    'confirm'           => 'Confirm',
    'rules'             => 'Rules',
    'round'             => 'Round',
    'cup_points'        => 'Cup Points',
    'revenues'          => 'Revenues',
    'expenses'          => 'Expenses',
    'paid'              => 'Payment made',
    'note'              => 'Note',
    'person'            => 'Person',
    'answer'            => 'Answer',
    'export'            => 'Export',
    'download'          => 'Download', // Verb - do not confuse with object_download!

    /*
     * Days
     */
    'yeah'          => 'Year',
    'month'         => 'Month',
    'day'           => 'Day',
    'today'         => 'Today',
    'yesterday'     => 'Yesterday',
    'tomorrow'      => 'Tomorrow',
    'monday'        => 'Monday',
    'tuesday'       => 'Tuesday',
    'wednesday'     => 'Wednesday',
    'thursday'      => 'Thursday',
    'friday'        => 'Friday',
    'saturday'      => 'Saturday',
    'sunday'        => 'Sunday', 

    /*
     * Module names
     */
    'object_adverts'        => 'Advertisements',
    'object_auth'           => 'Authentication',
    'object_awards'         => 'Awards',
    'object_cash_flows'     => 'Cash Flows',
    'object_comments'       => 'Comments',
    'object_config'         => 'Configuration',
    'object_contact'        => 'Contact',
    'object_countries'      => 'Countries',
    'object_cups'           => 'Cups',
    'object_dashboard'      => 'Dashboard',
    'object_diag'           => 'Diagnostics',
    'object_downloads'      => 'Downloads',
    'object_events'         => 'Events',
    'object_forums'         => 'Forums',
    'object_friends'        => 'Friends',
    'object_galleries'      => 'Galleries',
    'object_games'          => 'Games',
    'object_images'         => 'Images',
    'object_languages'      => 'Languages',
    'object_maps'           => 'Maps',
    'object_matches'        => 'Matches',
    'object_messages'       => 'Messages',
    'object_modules'        => 'Modules',
    'object_navigations'    => 'Navigations',
    'object_news'           => 'News',
    'object_opponents'      => 'Opponents',
    'object_pages'          => 'Pages',
    'object_partners'       => 'Partners',
    'object_questions'      => 'Frequently Asked Questions',
    'object_roles'          => 'Roles',
    'object_search'         => 'Search',
    'object_servers'        => 'Servers',
    'object_shouts'         => 'Shouts',
    'object_slides'         => 'Slides',
    'object_streams'        => 'Streams',
    'object_teams'          => 'Teams',
    'object_tournaments'    => 'Tournaments',
    #'object_update'         => 'Update',
    'object_users'          => 'Users',
    'object_videos'         => 'Videos',
    'object_visitors'       => 'Visitors',

    /*
     * Model names
     */
    'object_advert'             => 'Advertisement',
    'object_advert_cat'         => 'Advertisement-Category',
    'object_article'            => 'Article',
    'object_award'              => 'Award',
    'object_cash_flow'          => 'Cash Flow',
    'object_contact_message'    => 'Contact Message',
    'object_country'            => 'Country',
    'object_cup'                => 'Cup',
    'object_custom_page'        => 'Custom Page',
    'object_download'           => 'Download',
    'object_download_cat'       => 'Download-Category',
    'object_event'              => 'Event',
    'object_forum'              => 'Forum',
    'object_forum_post'         => 'Forum Post',
    'object_forum_report'       => 'Forum Report',
    'object_forum_thread'       => 'Forum Thread',
    'object_fragment'           => 'Fragment',
    'object_gallery'            => 'Gallery',
    'object_game'               => 'Game',
    'object_image'              => 'Image',
    'object_join_message'       => 'Application',
    'object_language'           => 'Language',
    'object_map'                => 'Map',
    'object_match'              => 'Match',
    'object_match_score'        => 'Match Score',
    'object_message'            => 'Message',
    'object_module'             => 'Module',
    'object_navigation'         => 'Navigation',
    'object_news_cat'           => 'News-Category',
    'object_opponent'           => 'Opponent',
    'object_page'               => 'Page',
    'object_page_cat'           => 'Page-Category',
    'object_partner'            => 'Partner',
    'object_partner_cat'        => 'Partner-Category',
    'object_post'               => 'Post',
    'object_question'           => 'Question',
    'object_question_cat'       => 'Question-Category',
    'object_role'               => 'Role',
    'object_server'             => 'Server',
    'object_slide'              => 'Slide',
    'object_slide_cat'          => 'Slide-Category',
    'object_stream'             => 'Stream',
    'object_team'               => 'Team',
    'object_team_cat'           => 'Team-Category',
    'object_thread'             => 'Thread',
    'object_tournament'         => 'Tournament',
    'object_user'               => 'User',
    'object_video'              => 'Video',

    /* 
     * Controller names (without "admin" prefix)
     */
    'object_activities'         => 'Activities',
    'object_advert_cats'        => 'Advertisement-Categories',
    'object_articles'           => 'Articles',
    'object_custom_pages'       => 'Custom Pages',
    'object_download_cats'      => 'Download-Categories',
    'object_forum_reports'      => 'Forum-Reports',
    'object_inbox'              => 'Inbox',
    'object_join'               => 'Join',
    'object_login'              => 'Login',
    'object_match_scores'       => 'Match Scores',
    'object_members'            => 'Members',
    'object_news_cats'          => 'News-Categories',
    'object_outbox'             => 'Outbox',
    'object_participants'       => 'Participants',
    'object_partner_cats'       => 'Partner-Categories',
    'object_question_cats'      => 'Question-Categories',
    'object_registration'       => 'Registration',
    'object_reports'            => 'Reports',
    'object_restore_password'   => 'Restore Password',
    'object_slide_cats'         => 'Slide-Categories',
    'object_threads'            => 'Threads',

];
