<?php
/**
 * Copyright (c) 2013 Jürgen Mackiol
 *
 * http://www.mackiol.net
 *
 * Contributors:
 * Jürgen Mackiol - initial contents
 *
 * Outputs a PNG image with random text and adds hash to session
 */

session_start();

$nonCollidingCharactersString
    = "A B C D E F G H J K M N Q R T U V W X Y 2 3 4 5 7 8";

$characterArray = explode(" ", $nonCollidingCharactersString);

// randomize content
shuffle($characterArray);

// extract first 6 characters from random array
$text = array_slice($characterArray, 0, 5);

// save hash of random text to user session (validation in webmailer.php)
$_SESSION['captcha'] = md5(implode($text));

Header("Content-type: image/png");

// loads base image without text - has to be located in same directory as this script;
// other file location requires absolute path change
$baseImage = ImageCreateFromPNG("captcha.png");

// base colors
$whiteColor = ImageColorAllocate($baseImage, 255, 255, 255);
$blackColor = ImageColorAllocate($baseImage, 0, 0, 0);

$FontDirectoryPath = dirname(__FILE__) . '/';
$Font = $FontDirectoryPath . "../font/adler.regular.ttf";
// format: imageObject, font size, angle of rotation, x-position, y-position, color, font-file, text
// the font file has to exist and must be readable for the web server; font can be of TTF or OTF type
ImageTTFText($baseImage, 16, -30, 70, 35, $whiteColor, $Font, $text[0]);
ImageTTFText($baseImage, 16, -90, 90, 65, $whiteColor, $Font, $text[1]);
ImageTTFText($baseImage, 16, 0, 65, 105, $whiteColor, $Font, $text[2]);
ImageTTFText($baseImage, 16, 45, 40, 95, $whiteColor, $Font, $text[3]);
ImageTTFText($baseImage, 16, 99, 40, 60, $whiteColor, $Font, $text[4]);

// sets the png output
ImagePng($baseImage);

// frees resources
ImageDestroy($baseImage);
?>
