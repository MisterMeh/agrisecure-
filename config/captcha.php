<?php

return [
    'disable' => env('CAPTCHA_DISABLE', false),
    'characters' => ['2', '3', '4', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'm', 'n', 'p', 'q', 'r', 't', 'u', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'M', 'N', 'P', 'Q', 'R', 'T', 'U', 'X', 'Y', 'Z'],
    // In your config/captcha.php file
'default' => [
    'length' => 6, // The captcha in your image has 5 characters
    'width' => 120,
    'height' => 36,
    'quality' => 90,
    'bgColor' => 'rgba(255, 255, 255, 0)', // <-- This is white with 100% transparency
    // You may need to adjust font and line colors for visibility
    'fontColors' => ['#2E8B57'], // Darker greens for contrast
    'lines' => -1, // Your image seems to have a few lines
    'lineColors' => ['#2E8B57', '#3CB371'],
],
    'math' => [
        'length' => 9,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
        'math' => true,
    ],

    'flat' => [
    'length' => 6,
    'width' => 160,
    'height' => 46,
    'quality' => 90,
    'lines' => 0,
    'bgImage' => false,
    'bgColor' => 'rgba(158, 244, 148, 0)', // A very light green
    'fontColors' => ['#41955f'],
    'contrast' => -5,
],
    'mini' => [
        'length' => 3,
        'width' => 60,
        'height' => 32,
    ],
    'inverse' => [
        'length' => 5,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
        'sensitive' => true,
        'angle' => 12,
        'sharpen' => 10,
        'blur' => 2,
        'invert' => true,
        'contrast' => -5,
    ]
];
