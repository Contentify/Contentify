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
    'comment_create_fail'   => 'No se ha podido crear el comentario. :0',
    'comment_update_fail'   => 'No se ha podido actualizar el comentario. :0',
    'enable_comments'       => 'Habilitar comentarios',

    /*
     * Captcha
     */
    'captcha_invalid'   => 'Captcha inválido!',

    /*
     * General terms
     */
    'date_time'         => 'Fecha y Hora',
    'yes'               => 'Si',
    'no'                => 'No',
    'valid'             => 'válido',
    'invalid'           => 'inválido',
    'enabled'           => 'Habilitado',
    'disabled'          => 'Deshabilitado',
    'read_more'         => 'Leer más...',
    'name'              => 'Nombre',
    'username'          => 'Nombre de usuario',
    'email'             => 'Email',
    'password'          => 'Contraseña',
    'file'              => 'Archivo',
    'link'              => 'Enlace',
    'links'             => 'Enlaces',
    'send'              => 'Enviar',
    'position'          => 'Posición',
    'location'          => 'Localización',
    'url'               => 'URL',
    'quote'             => 'Cita',
    'size'              => 'Tamaño',
    'ip'                => 'IP',
    'online'            => 'Online',
    'offline'           => 'Offline',
    'viewers'           => 'Espectadores',
    'task'              => 'Tarea',
    'add_tags'          => 'Añadir etiquetas',
    'reply'             => 'Contestar',
    'latest'            => 'Último',
    'item'              => 'Elemento',
    'home'              => 'Home',
    'remove'            => 'Quitar',
    'cancel'            => 'Cancelar',
    'of'                => 'de',
    'role'              => 'Rol',
    'message'           => 'Mensaje',
    'subject'           => 'Tema',
    'latest_msgs'       => 'Últimos mensajes de Feed', // Admin dashboard feed messages
    'quick_access'      => 'Acceso rápido', // Admin dashboard quick access
    'setting'           => 'Ajuste',
    'value'             => 'Valor',
    'placeholder'       => 'Marcador, por favor, cámbialo!', // Diagnostics module
    'no_cron_job'       => 'Nunca. No hay cronjobs creados?',
    'compiled'          => 'compilado',
    'website'           => 'Web',
    'calendar'          => 'Calendario',
    'team_members'      => 'Miembros del equipo',
    'profile'           => 'Perfil',
    'edit_profile'      => 'Editar Perfil',
    'add'               => 'Añadir',
    'logout'            => 'Salir',
    'install'           => 'Instalar',
    'preview'           => 'Vista previa',
    'total'             => 'Total',
    'nothing_new'       => 'Lo sentimos, no hay novedades. Por favor, vuelve luego.',
    'back'              => 'Atrás',
    'general'           => 'General',
    'services'          => 'Servicios',
    'theme'             => 'Tema',
    'theme_christmas'   => 'Modo navidad (Nieve)',
    'theme_snow_color'  => 'Color Copos de nieve',
    'loading'           => 'Cargando',
    'lineup'            => 'Alineación',
    'translate'         => 'Traducir',
    'successful'        => 'Exitoso!',
    'slots'             => 'Espacios',
    'mode'              => 'Modo',
    'prize'             => 'Premio',
    'closed'            => 'Cerrado',
    'leave'             => 'Salir',
    'join'              => 'Entrar',
    'confirm'           => 'Confirmar',
    'rules'             => 'Reglas',
    'round'             => 'Ronda',
    'cup_points'        => 'Puntos de copa',
    'revenues'          => 'Ingresos',
    'expenses'          => 'Gastos',
    'paid'              => 'Pago realizado',
    'note'              => 'Nota',
    'person'            => 'Persona',
    'answer'            => 'Respuesta',
    'export'            => 'Exportar',
    'download'          => 'Descargar', // Verb - do not confuse with object_download!

    /*
     * Days
     */
    'yeah'          => 'Año',
    'month'         => 'Mes',
    'day'           => 'Día',
    'today'         => 'Hoy',
    'yesterday'     => 'Ayer',
    'tomorrow'      => 'Mañana',
    'monday'        => 'Lunes',
    'tuesday'       => 'Martes',
    'wednesday'     => 'Miércoles',
    'thursday'      => 'Jueves',
    'friday'        => 'Viernes',
    'saturday'      => 'Sábado',
    'sunday'        => 'Domingo', 

    /*
     * Module names
     */
    'object_adverts'        => 'Anuncios',
    'object_auth'           => 'Autenticación',
    'object_awards'         => 'Premios',
    'object_cash_flows'     => 'Movimientos de dinero',
    'object_comments'       => 'Comentarios',
    'object_config'         => 'Configuración',
    'object_contact'        => 'Contacto',
    'object_countries'      => 'Países',
    'object_cups'           => 'Copas',
    'object_dashboard'      => 'Panel de control',
    'object_diag'           => 'Diagnóstico',
    'object_downloads'      => 'Descargas',
    'object_events'         => 'Eventos',
    'object_forums'         => 'Foros',
    'object_friends'        => 'Amigos',
    'object_galleries'      => 'Galerías',
    'object_games'          => 'Juegos',
    'object_images'         => 'Imágenes',
    'object_languages'      => 'Idiomas',
    'object_maps'           => 'Mapas',
    'object_matches'        => 'Partidos',
    'object_messages'       => 'Mensajes',
    'object_modules'        => 'Módulos',
    'object_navigations'    => 'Navegación',
    'object_news'           => 'Notícias',
    'object_opponents'      => 'Oponentes',
    'object_pages'          => 'Páginas',
    'object_partners'       => 'Patrocinadores',
    'object_questions'      => 'FAQ',
    'object_roles'          => 'Roles',
    'object_search'         => 'Buscar',
    'object_servers'        => 'Servidores',
    'object_shouts'         => 'Gritos',
    'object_slides'         => 'Slides',
    'object_streams'        => 'Directos',
    'object_teams'          => 'Equipos',
    'object_tournaments'    => 'Torneos',
    #'object_update'         => 'Update',
    'object_users'          => 'Usuarios',
    'object_videos'         => 'Vídeos',
    'object_visitors'       => 'Visitantes',

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
