<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. Depending on your PHP setup, you can choose one of them.
    |
    | Included options:
    |   - \Intervention\Image\Drivers\Gd\Driver::class
    |   - \Intervention\Image\Drivers\Imagick\Driver::class
    |
    */

    'driver' => \Intervention\Image\Drivers\Gd\Driver::class,

    /*
    |--------------------------------------------------------------------------
    | Configuration Options
    |--------------------------------------------------------------------------
    |
    | These options control the behavior of Intervention Image.
    |
    | - "autoOrientation" controls whether an imported image should be
    |    automatically rotated according to any existing Exif data.
    |
    | - "decodeAnimation" decides whether a possibly animated image is
    |    decoded as such or whether the animation is discarded.
    |
    | - "blendingColor" Defines the default blending color.
    |
    | - "strip" controls if meta data like exif tags should be removed when
    |    encoding images.
    */

    'options' => [
        'autoOrientation' => true,
        'decodeAnimation' => true,
        'blendingColor' => 'ffffff',
        'strip' => true, // Change to true to strip metadata for smaller file sizes
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Processing Presets
    |--------------------------------------------------------------------------
    |
    | Default image processing presets for different use cases
    |
    */
    'presets' => [
        'thumbnail' => [
            'width' => 300,
            'height' => 300,
            'quality' => 80,
        ],
        'medium' => [
            'width' => 600,
            'height' => 600,
            'quality' => 85,
        ],
        'responsive' => [
            'sizes' => [
                'sm' => ['width' => 400, 'height' => null, 'quality' => 80],
                'md' => ['width' => 800, 'height' => null, 'quality' => 85],
                'lg' => ['width' => 1200, 'height' => null, 'quality' => 90],
            ],
        ],
    ],
];
