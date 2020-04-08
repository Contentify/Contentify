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
    'spam_protection' => 'Para proteção anti-SPAM, é necessário aguardar poucos segundos antes de enviar o formulário.',
    'debug_warning'   => 'Aviso: O modo de depuração está ativado e isso não parece ser um servidor de desenvolvimento!',
    'space_warning'   => 'Apenas queda espacio libre en el disco duro (:0). Por favor, borre la caché, aumente la memoria o póngase en contacto con nuestro soporte.',

    /*
     * Errors
     */
    'not_found'     => 'Recurso não encontrado.',
    'not_possible'  => 'Sentimos muito, esta ação não é permitida no momento.',
    'no_auth'       => 'Acceso negado. Por favor identifique-se.',
    'access_denied' => 'Acceso negado. Você precisa de permissões.',
    
    /*
     * Forms
     */
    'save'      => 'Salvar',
    'apply'     => 'Aplicar',
    'reset'     => 'Reiniciar',
    'update'    => 'Atualização',

    /*
     * Auto CRUD handling (and related stuff)
     */
    'created'       => ':0 criado.',
    'updated'       => ':0 atualizado.',
    'deleted'       => ':0 removido.',
    'restored'      => ':0 restaurado.',
    'list_empty'    => 'Por favor, crie pelo menos un ":0" antes de continuar.',
    'invalid_image' => 'Arquivo de imagem inválido',
    'bad_extension' => "O upload de arquivos com a extensão ':0' não é permitido.",
    'delete_error'  => "Erro: O objeto possui :0 dependências (Tipo: ':1')! Remova-os primeiro.",

    /*
     * Model index building
     */
    'create'        => 'Criar novo',
    'categories'    => 'Categorias',
    'config'        => 'Configuração',
    'actions'       => 'Ações',
    'edit'          => 'Editar',
    'delete'        => 'Excluir',
    'restore'       => 'Restaurar',
    
    'id'            => 'ID',
    'index'         => 'Índice',
    'title'         => 'Título',
    'short_title'   => 'Título abreviado',
    'author'        => 'Autor',
    'creator'       => 'Criador',
    'category'      => 'Categoria',
    'type'          => 'Tipo',
    'provider'      => 'Fornecedor',
    'text'          => 'Texto',
    'code'          => 'Código',
    'description'   => 'Descrição',
    'image'         => 'Imagem',
    'icon'          => 'ícone',
    'date'          => 'Data',
    'published'     => 'Postado',
    'starts_at'     => 'Iniciar em',
    'created_at'    => 'Criado em',
    'updated_at'    => 'Atualizado el',
    'achieved_at'   => 'Concluído em',
    'internal'      => 'Interno',
    'featured'      => 'Em destaque',
    'state'         => 'Estado',
    'new'           => 'Novo',
    'open'          => 'Aberto',
    'term'          => 'Termo',

    /*
     * Misc
     */
    'sorting'       => 'Arrumando',
    'search'        => 'Buscar',
    'recycle_bin'   => 'Caixote de reciclagem',
    'max_size'      => 'Tamanho máximo: :0Bytes',
    'accessed'      => 'acceso',
    'no_items'      => 'Nenhum item.',
    'share_this'    => 'Compartilhar',
    'save_to_del'   => 'Salve este formulário para excluir este arquivo.',

    /*
     * JavaScript
     */
    'request_failed'    => 'Erro: falha na solicitação.',
    'delete_item'       => 'Excluir este item?',
    'perform_action'    => 'Execute esta ação?',

    /*
     * Backend template
     */
    'no_js'             => 'JavaScript não está ativado. Ative o JavaScript!',
    'admin_dashboard'   => 'Painel de Administração',
    'new_messages'      => 'Uma nova mensagem|:count novas mensagens',
    'top'               => 'Acima',
    'help'              => 'Ajuda',
    'welcome'           => 'Bem vindo',

    /*
     * Permissions
     */
    'permissions'       => 'Permissões',
    'permission_none'   => 'Nada',
    'permission_read'   => 'Ler',
    'permission_create' => 'Criar',
    'permission_update' => 'Editar',
    'permission_delete' => 'Excluir',

    /*
     * Comments
     */
    'comments'              => 'Comentários',
    'comments_disabled'     => 'O recurso de comentários foi desativado para este conteúdo.',
    'comment_create_fail'   => 'Não foi possível criar o comentário. :0',
    'comment_update_fail'   => 'Não foi possível atualizar o comentário. :0',
    'enable_comments'       => 'Ativar comentários',

    /*
     * Captcha
     */
    'captcha_invalid'   => 'Captcha inválido!',

    /*
     * General terms
     */
    'date_time'         => 'Data e Hora',
    'yes'               => 'Sim',
    'no'                => 'Não',
    'valid'             => 'válido',
    'invalid'           => 'inválido',
    'enabled'           => 'Habilitado',
    'disabled'          => 'Desativado',
    'read_more'         => 'Ler mais...',
    'name'              => 'Primeiro nome',
    'username'          => 'Nome',
    'email'             => 'Email',
    'password'          => 'Senha',
    'file'              => 'Arquivo',
    'link'              => 'Link',
    'links'             => 'Links',
    'send'              => 'Enviar',
    'position'          => 'Posição',
    'location'          => 'Localização',
    'url'               => 'URL',
    'quote'             => 'Cita',
    'size'              => 'Tamanho',
    'ip'                => 'IP',
    'online'            => 'Online',
    'offline'           => 'Offline',
    'viewers'           => 'Espectadores',
    'task'              => 'Tarefa',
    'add_tags'          => 'Adicionar tags',
    'reply'             => 'Reposta',
    'latest'            => 'Último',
    'item'              => 'Item',
    'home'              => 'Home',
    'remove'            => 'Remover',
    'cancel'            => 'Cancelar',
    'of'                => 'do',
    'role'              => 'Papel',
    'message'           => 'Mensaje',
    'subject'           => 'Assunto',
    'latest_msgs'       => 'Postagens mais recentes do feed', // Admin dashboard feed messages
    'quick_access'      => 'Acceso rápido', // Admin dashboard quick access
    'setting'           => 'Configuração',
    'value'             => 'Valor',
    'placeholder'       => 'Bookmark, por favor mude!', // Diagnostics module
    'no_cron_job'       => 'Nunca Cronjobs não criados?',
    'compiled'          => 'compilado',
    'website'           => 'Web',
    'calendar'          => 'Calendário',
    'team_members'      => 'Membros da equipe',
    'profile'           => 'Perfil',
    'edit_profile'      => 'Editar Perfil',
    'add'               => 'Adicionar',
    'logout'            => 'Saída',
    'install'           => 'Instale',
    'preview'           => 'Vista prévia',
    'total'             => 'Total',
    'nothing_new'       => 'Desculpe, não há notícias. Por favor volte mais tarde.',
    'nothing_here'      => 'Desculpe, não há conteúdo aqui ainda. Por favor, tente novamente mais tarde.',
    'back'              => 'De volta',
    'general'           => 'Geral',
    'metainfo'          => 'Meta info',
    'services'          => 'Serviços',
    'theme'             => 'Assunto',
    'theme_christmas'   => 'Modo natal(Neve)',
    'theme_snow_color'  => 'Cor da neve',
    'loading'           => 'Carregando',
    'lineup'            => 'alinhar',
    'translate'         => 'Traduzir',
    'successful'        => 'Êxito!',
    'slots'             => 'Espaços',
    'mode'              => 'Mode',
    'prize'             => 'Prêmio',
    'closed'            => 'Fechado',
    'leave'             => 'Saída',
    'join'              => 'Unir-se',
    'confirm'           => 'Confirmar',
    'rules'             => 'Regras',
    'round'             => 'Rodada',
    'cup_points'        => 'Pontos de copa',
    'revenues'          => 'Renda',
    'expenses'          => 'Despesas',
    'paid'              => 'Pagamento realizado',
    'note'              => 'Nota',
    'person'            => 'Pessoa',
    'answer'            => 'Respuesta',
    'export'            => 'Exportar',
    'download'          => 'Baixar', // Verb - do not confuse with object_download!
    'option'            => 'Opção',
    'gdpr'              => 'RGPD', // General Data Protection Regulation
    'gdpr_alert'        => 'Este sitio web utiliza cookies para proporcionar la mejor experiencia posible al usuario.',
    'privacy_policy'    => 'Declaración de privacidad',

    /*
     * Days
     */
    'yeah'          => 'Ano',
    'month'         => 'Mês',
    'day'           => 'Dia',
    'today'         => 'Hoje',
    'yesterday'     => 'Ontem',
    'tomorrow'      => 'Amanhã',
    'monday'        => 'Segunda-feira',
    'tuesday'       => 'Terça-feira',
    'wednesday'     => 'Quarta-feira',
    'thursday'      => 'Quinta-feira',
    'friday'        => 'Sexta-feira',
    'saturday'      => 'Sábado',
    'sunday'        => 'Domingo', 

    /*
     * Module names
     */
    'object_adverts'        => 'Comunicados',
    'object_auth'           => 'Autenticação',
    'object_awards'         => 'Prémios',
    'object_cash_flows'     => 'Movimentos de dinheiro',
    'object_comments'       => 'Comentários',
    'object_config'         => 'Configuração',
    'object_contact'        => 'Contato',
    'object_countries'      => 'Países',
    'object_cups'           => 'Torneio de copos',
    'object_dashboard'      => 'Painel de controle',
    'object_diag'           => 'Diagnóstico',
    'object_downloads'      => 'Descargas',
    'object_events'         => 'Eventos',
    'object_forums'         => 'Fóruns',
    'object_friends'        => 'Amigos',
    'object_galleries'      => 'Galerías',
    'object_games'          => 'Jogos',
    'object_images'         => 'Imagens',
    'object_languages'      => 'Línguas',
    'object_maps'           => 'Mapas',
    'object_matches'        => 'Jogos',
    'object_messages'       => 'Mensagens',
    'object_modules'        => 'Módulos',
    'object_navigations'    => 'Navegação',
    'object_news'           => 'Notícias',
    'object_opponents'      => 'Oponentes',
    'object_pages'          => 'Páginas',
    'object_partners'       => 'Patrocinadores',
    'object_polls'          => 'Pesquisas',
    'object_questions'      => 'FAQ',
    'object_roles'          => 'Papéis',
    'object_search'         => 'Buscar',
    'object_servers'        => 'Servidores',
    'object_shouts'         => 'Shouts',
    'object_slides'         => 'Slide',
    'object_streams'        => 'Directos',
    'object_teams'          => 'Time',
    'object_tournaments'    => 'Torneios',
    #'object_update'         => 'Update',
    'object_users'          => 'Usuários',
    'object_videos'         => 'Vídeos',
    'object_visitors'       => 'Visitantes',

    /*
     * Model names
     */
    'object_advert'             => 'Aviso',
    'object_advert_cat'         => 'Aviso-Categoría',
    'object_article'            => 'Artigo',
    'object_award'              => 'Prémio',
    'object_cash_flow'          => 'Movimentação de dinheiro',
    'object_contact_message'    => 'Mensagem de contato',
    'object_country'            => 'País',
    'object_cup'                => 'Torneio de copos',
    'object_custom_page'        => 'Site customizada',
    'object_download'           => 'Descarga',
    'object_download_cat'       => 'Descarga-Categoría',
    'object_event'              => 'Acontecimento',
    'object_forum'              => 'Fórum',
    'object_forum_post'         => 'Postagem no fórum',
    'object_forum_report'       => 'Relatório do Fórum',
    'object_forum_thread'       => 'Tópico do Fórum',
    'object_fragment'           => 'Fragmento',
    'object_gallery'            => 'Galería',
    'object_game'               => 'Jogo',
    'object_image'              => 'Imagen',
    'object_join_message'       => 'Aplicação',
    'object_language'           => 'Língua',
    'object_map'                => 'Mapa',
    'object_match'              => 'Jogo',
    'object_match_score'        => 'Pontuação do jogo',
    'object_message'            => 'Mensagem',
    'object_module'             => 'Módulo',
    'object_navigation'         => 'Navegação',
    'object_news_cat'           => 'Notícias-Categoría',
    'object_opponent'           => 'Adversário',
    'object_page'               => 'Site',
    'object_page_cat'           => 'Site-Categoría',
    'object_partner'            => 'Parceiro',
    'object_partner_cat'        => 'Parceiro-Categoría',
    'object_poll'               => 'Pesquisa',
    'object_post'               => 'Publicação',
    'object_question'           => 'Pergunta',
    'object_question_cat'       => 'Pergunta-Categoría',
    'object_role'               => 'Papel',
    'object_server'             => 'Servidor',
    'object_slide'              => 'Slide',
    'object_slide_cat'          => 'Slide-Categoría',
    'object_stream'             => 'Transmissão',
    'object_team'               => 'Time',
    'object_team_cat'           => 'Time-Categoría',
    'object_thread'             => 'Tópico',
    'object_tournament'         => 'Torneio',
    'object_user'               => 'Usuário',
    'object_video'              => 'Vídeo',

    /* 
     * Controller names (without "admin" prefix)
     */
    'object_activities'         => 'Actividades',
    'object_advert_cats'        => 'Aviso-Categorías',
    'object_articles'           => 'Artigos',
    'object_custom_pages'       => 'Sites customizadas',
    'object_download_cats'      => 'Descargas-Categorías',
    'object_forum_reports'      => 'Relatórios do fórum',
    'object_inbox'              => 'Caixa de entrada',
    'object_join'               => 'Junte-se',
    'object_login'              => 'Entrar',
    'object_match_scores'       => 'Resultado da Partida',
    'object_members'            => 'Membros',
    'object_news_cats'          => 'Notícias-Categorías',
    'object_outbox'             => 'Caixa de saída',
    'object_participants'       => 'Participantes',
    'object_partner_cats'       => 'Parceiro-Categorías',
    'object_question_cats'      => 'Perguntas-Categorías',
    'object_registration'       => 'Cadastro',
    'object_reports'            => 'Relatório',
    'object_restore_password'   => 'Recuperar senha',
    'object_slide_cats'         => 'Slide-Categorías',
    'object_threads'            => 'Tópico',

];
