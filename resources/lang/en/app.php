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

    /*
     * Errors
     */
    'not_found' => 'Resource not found.',

    /*
     * User permisssions
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
     * Backend template
     */
    'no_js'             => 'JavaScript not enabled. Please enable JavaScript!',
    'admin_dashboard'   => 'Admin-Dashboard',
    'new_messages'      => 'One new message|:count new messages',
    'top'               => 'Go to top',

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
    'date_time'     => 'Date & Time',
    'yes'           => 'Yes',
    'no'            => 'No',
    'valid'         => 'valid',
    'invalid'       => 'invalid',
    'enabled'       => 'Enabled',
    'disbaled'      => 'Disabled',
    'read_more'     => 'Read more...',
    'name'          => 'Name',
    'username'      => 'Username',
    'email'         => 'Email',
    'password'      => 'Password',
    'file'          => 'File',
    'link'          => 'Link',
    'link'          => 'Links',
    'send'          => 'Send',
    'position'      => 'Position',
    'location'      => 'Location',
    'url'           => 'URL',
    'quote'         => 'Quote',
    'size'          => 'Size',
    'ip'            => 'IP',
    'online'        => 'Online',
    'viewers'       => 'Viewers',
    'task'          => 'Task',
    'add_tags'      => 'Add tags',
    'reply'         => 'Reply',
    'latest'        => 'Latest',
    'item'          => 'Item',
    'home'          => 'Home',
    'remove'        => 'Remove',
    'of'            => 'of',
    'role'          => 'Role',
    'message'       => 'Message',
    'subject'       => 'Subject',
    'latest_msgs'   => 'Latest Feed-Messages', // Admin dashboard feed messages
    'quick_access'  => 'Quick Access', // Admin dashboard quick access
    'setting'       => 'Setting',
    'value'         => 'Value',
    'placeholder'   => 'Placeholder, please change!', // Diag module
    'compiled'      => 'compiled',
    'calendar'      => 'Calendar',
    'profile'       => 'Profile',
    'edit_profile'  => 'Edit Profile',
    'install'       => 'Install',
    'preview'       => 'Preview',
    'total'         => 'Total',

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
    'object_comments'       => 'Comments',
    'object_config'         => 'Configuration',
    'object_contact'        => 'Contact',
    'object_coutries'       => 'Countries',
    'object_dashboard'      => 'Dashboard',
    'object_diag'           => 'Diagnostics',
    'object_downloads'      => 'Downloads',
    'object_events'         => 'Events',
    'object_forums'         => 'Forums',
    'object_friends'        => 'Friends',
    'object_galleries'      => 'Galleries',
    'object_games'          => 'Games',
    'object_groups'         => 'Groups',
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
    'object_search'         => 'Search',
    'object_servers'        => 'Servers',
    'object_shouts'         => 'Shouts',
    'object_slides'         => 'Slides',
    'object_streams'        => 'Streams',
    'object_teams'          => 'Teams',
    'object_tournaments'    => 'Tournaments',
    'object_users'          => 'Users',
    'object_videos'         => 'Videos',
    'object_visitors'       => 'Visitors',

    /*
     * Model names
     */
    'object_advert'             => 'Advertisement',
    'object_advertcat'          => 'Advertisement-Category',
    'object_award'              => 'Award',
    'object_contact_message'    => 'Contact Message',
    'object_join_message'       => 'Application',
    'object_country'            => 'Country',
    'object_download'           => 'Download',
    'object_downloadcat'        => 'Download-Category',
    'object_event'              => 'Event',
    'object_post'               => 'Post',
    'object_thread'             => 'Thread',
    'object_forum'              => 'Forum',
    'object_forum_post'         => 'Forum Post',
    'object_forum_report'       => 'Forum Report',
    'object_forum_thread'       => 'Forum Thread',
    'object_gallery'            => 'Gallery',
    'object_game'               => 'Game',
    'object_group'              => 'Group',
    'object_image'              => 'Image',
    'object_language'           => 'Language',
    'object_map'                => 'Map',
    'object_match'              => 'Match',
    'object_match_score'        => 'Match Score',
    'object_messages'           => 'Message',
    'object_module'             => 'Module',
    'object_navigation'         => 'Navigation',
    'object_newscat'            => 'News-Category',
    'object_opponent'           => 'Opponent',
    'object_article'            => 'Article',
    'object_custom_page'        => 'Custom Page',
    'object_fragment'           => 'Fragment',
    'object_page'               => 'Page',
    'object_pagecat'            => 'Page-Category',
    'object_partner'            => 'Partner',
    'object_partnercat'         => 'Partner-Category',
    'object_server'             => 'Server',
    'object_slide'              => 'Slide',
    'object_slidecat'           => 'Slide-Category',
    'object_stream'             => 'Stream',
    'object_team'               => 'Team',
    'object_teamcat'            => 'Team-Category',
    'object_tournament'         => 'Tournament',
    'object_user'               => 'User',
    'object_video'              => 'Video',

    /* 
     * Controller names (without "admin" prefix)
     */
    'object_advertcats'         => 'Advertisement-Categories',
    'object_login'              => 'Login',
    'object_registration'       => 'Registration',
    'object_restore_password'   => 'Restore Password',
    'object_join'               => 'Join',
    'object_reports'            => 'Reports',
    'object_inbox'              => 'Inbox',
    'object_outbox'             => 'Outbox',
    'object_newscats'           => 'News-Categories',
    'object_articles'           => 'Articles',
    'object_custom_pages'       => 'Custom Pages',
    'object_partnercats'        => 'Partner-Categories',
    'object_slidecats'          => 'Slide-Categories',
    'object_member'             => 'Members',
    'object_activities'         => 'Activities',
    'object_threads'            => 'Threads',
    'object_match_scores'       => 'Match Scores',

];
