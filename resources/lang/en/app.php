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
    'spam_protection' => 'For spam protection, you have to wait a few seconds until you can submit this form.',
    'debug_warning'   => 'Warning: Debug mode is enabled but this does not seem to be a development server!',
    'not_found'       => 'Resource not found.',
    'not_possible'    => 'Sorry, but this action is not available right now.',
    'space_warning'   => 'There is little free disk space left (:0). Please clear the cache, increase the space or contact our support team.',

    /*
     * User permissions
     */ 
    'no_auth'       => 'Access denied. Please log in.',
    'access_denied' => 'Access denied. You need permissions.',
    
    /*
     * Forms
     */
    'save'      => 'Save',
    'apply'     => 'Apply',
    'reset'     => 'Reset',
    'update'    => 'Update',

    /*
     * Auto CRUD handling (and related stuff)
     */
    'created'       => ':0 created.',
    'updated'       => ':0 updated.',
    'deleted'       => ':0 deleted.',
    'restored'      => ':0 restored.',
    'list_empty'    => 'Please create at least one ":0" before you continue.',
    'invalid_image' => 'Invalid image file',
    'bad_extension' => "Uploading of files with ':0' extension is restricted.",
    'delete_error'  => "Error: This object has :0 dependencies (Type: ':1')! Please remove them first.",

    /*
     * Model index building
     */
    'create'        => 'Create new',
    'categories'    => 'Categories',
    'config'        => 'Config',
    'actions'       => 'Actions',
    'edit'          => 'Edit',
    'delete'        => 'Delete',
    'restore'       => 'Restore',
    
    'id'            => 'ID',
    'index'         => 'Index',
    'title'         => 'Title',
    'short_title'   => 'Short Title',
    'author'        => 'Author',
    'creator'       => 'Creator',
    'category'      => 'Category',
    'type'          => 'Type',
    'provider'      => 'Provider',
    'text'          => 'Text',
    'code'          => 'Code',
    'description'   => 'Description',
    'image'         => 'Image',
    'icon'          => 'Icon',
    'date'          => 'Date',
    'published'     => 'Published',
    'starts_at'     => 'Starts At',
    'created_at'    => 'Created At',
    'updated_at'    => 'Updated At',
    'achieved_at'   => 'Achieved At',
    'internal'      => 'Internal',
    'featured'      => 'Featured',
    'state'         => 'State',
    'new'           => 'New',
    'open'          => 'Open',

    /*
     * Misc
     */
    'sorting'       => 'Sorting',
    'search'        => 'Search',
    'recycle_bin'   => 'Recycle Bin',
    'max_size'      => 'Max. Size: :0Bytes',
    'accessed'      => 'accessed',
    'no_items'      => 'There are no items.',
    'share_this'    => 'Share this',
    'save_to_del'   => 'Save this form to delete this file.',

    /*
     * JavaScript
     */
    'request_failed'    => 'Error: Request failed.',
    'delete_item'       => 'Delete this item?',
    'perform_action'    => 'Execute this action?',

    /*
     * Backend template
     */
    'no_js'             => 'JavaScript not enabled. Please enable JavaScript!',
    'admin_dashboard'   => 'Admin-Dashboard',
    'new_messages'      => 'One new message|:count new messages',
    'top'               => 'Go to top',
    'help'              => 'Help',
    'welcome'           => 'Welcome',

    /*
     * Permissions
     */
    'permissions'       => 'Permissions',
    'permission_none'   => 'None',
    'permission_read'   => 'Read',
    'permission_create' => 'Create',
    'permission_update' => 'Update',
    'permission_delete' => 'Delete',

    /*
     * Comments
     */
    'comments'              => 'Comments',
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
    'nothing_new'       => 'Sorry, nothing new here. Please return later.',
    'nothing_here'      => 'Sorry, there is no content here yet. Please try again later.',
    'back'              => 'Back',
    'general'           => 'General',
    'metainfo'			=> 'Meta Info',
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
    'option'            => 'Option',
    'gdpr'              => 'GDPR', // General Data Protection Regulation
    'gdpr_alert'        => 'This website uses cookies to provide the best possible user experience.',
    'privacy_policy'    => 'Privacy Policy',

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
    'object_polls'          => 'Polls',
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
    'object_poll'               => 'Poll',
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
