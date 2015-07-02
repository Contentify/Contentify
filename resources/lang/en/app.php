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
    'max_size'      => 'Max. Size: :0Byte',
    'accessed'      => 'accessed',
    'no_items'      => 'There are no items.',

    /*
     * Backend template
     */
    'no_js'             => 'JavaScript not enabled. Please enable JavaScript!',
    'admin_dashboard'   => 'Admin-Dashboard',
    'tec_infos'         => 'Show infos about the technologies used',
    'view_profile'      => 'View your profile',
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
    'read_more'     => 'Read more...',
    'name'          => 'Name',
    'username'      => 'Username',
    'email'         => 'Email',
    'password'      => 'Password',
    'file'          => 'File',
    'link'          => 'Link',
    'send'          => 'Send',
    'position'      => 'Position',
    'location'      => 'Location',
    'url'           => 'URL',
    'quote'         => 'Quote',
    'size'          => 'Size',
    'ip'            => 'IP',
    'online'        => 'Online',
    'viewers'       => 'Viewers',
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

];
