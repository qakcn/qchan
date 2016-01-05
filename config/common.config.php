<?php

// Config start
return array(

    /* Information of website that hosting Qchan */
    'site' => array(
        /**
         * Name of website
         * type: string
         */
        'name' => 'Qchan Image Host',

        /**
         * Description of website
         * type: string
         */
        'description' => 'Upload & Share',

        /**
         * Keywords of website
         * type: string
         */
        'keywords' => 'images, photos, image hosting, photo hosting, free image hosting',

        /**
         * Email of administrator of website
         * type: string
         */
        'email' => 'admin@example.com',

        /**
         * Copyright information of website
         * type: string
         */
        'copyright' => '&copy; 2015 Quadra Studios.',
    ),

    /* Parent website Information */
    'parent_site' => array(
        /**
         * Is parent website will be showd
         * type: boolean
         */
        'enabled' => true,

        /**
         * Name of parent website
         * type: string
         */
        'name' => 'lst',

        /**
         * Logo URL of parent website
         */
        'logo' => 'a.jpg',

        /**
         * Homepage URL of parent website
         */
        'url' => 'http://example.com',
    ),

    /* UI settings */
    'ui' => array(
        /**
         * Theme name that will be used, must be under themes directory
         * type: string
         */
        'theme' => 'default',

        /**
         * Default language that will be used, must supported by theme
         * type: string
         */
        'language' => 'en',
    ),

    /* Upload settings */
    'upload' => array(
        /**
         * Size limitation of uploaded file. 'K', 'M', 'G' is allowed
         * type: string
         */
        'size_limit' => '2M',
    ),

    /* Watermark settings */
    'watermark' => array(
        /**
         * Is watermark enabled
         * type: boolean
         */
        'enabled' => false,

        /**
        * The path of watermark image refer to Qchan root
        * type: boolean
        */
        'image_path' => 'images/watermark.png',

        /**
         * Minimum size of image that will be watermarked. Formatted in '<width>x<height>', <width> and <height> must be integer
         * type: string
         */
        'min_size' => '200x200',

        /**
         * Coordinates of watermark position. Formatted in 'x,y', x means left if positive and right if negative, y means top if positive and bottom if nagative. x and y must be integer, refer to image left and top origin.
         * type: string
         */
        'position' => '10,10',
    ),

// End of config
);
