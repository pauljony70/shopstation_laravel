<?php

namespace App\Helpers;

class ColorHelper
{
    /**
     * Convert hex color code to RGB.
     *
     * @param string $hexColor
     * @return array|false
     */
    public static function hexToRgb($hexColor)
    {
        // Remove any leading # from the hex color code
        $hexColor = str_replace('#', '', $hexColor);

        // Validate the hex color code
        if (!preg_match('/^[a-fA-F0-9]{6}$/', $hexColor)) {
            return false; // Invalid hex color code
        }

        // Split the hex color code into individual color components
        $red = hexdec(substr($hexColor, 0, 2));
        $green = hexdec(substr($hexColor, 2, 2));
        $blue = hexdec(substr($hexColor, 4, 2));

        // Return the RGB values as an associative array
        return ['red' => $red, 'green' => $green, 'blue' => $blue];
    }

    /**
     * Get dark color style.
     *
     * @param string $hexColor
     * @return string|false
     */
    public static function getDarkColorStyle($hexColor)
    {
        // Convert hex color to RGB
        $rgbValues = self::hexToRgb($hexColor);

        // Check if the hex color is valid
        if ($rgbValues === false) {
            return false;
        }

        // Calculate dark color by reducing each RGB component
        $darkRed = max(0, $rgbValues['red'] - 50);
        $darkGreen = max(0, $rgbValues['green'] - 50);
        $darkBlue = max(0, $rgbValues['blue'] - 50);

        // Convert dark color back to hex
        $darkColor = sprintf("#%02x%02x%02x", $darkRed, $darkGreen, $darkBlue);

        // Return the dark color style
        return 'background-color:' . $hexColor . '; border: 1px solid ' . $darkColor . ';';
    }
}
