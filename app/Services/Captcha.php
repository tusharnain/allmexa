<?php

namespace App\Services;


use \GdImage;

class Captcha
{
    private GdImage $gd;
    private string $keyPrefix = 'captcha_hash:';
    private string $defaultBackgroundAlpha = '000';
    private array $directoryFonts;

    public string $key;
    public int $width = 150;
    public int $height = 45;
    public int $stringSize = 4;
    public int $stringType = 3; // Letters (1), Numbers (2), Letters & Numbers (3)
    public int $case = 3; // Capitalcase (1), Smallcase (2), Mixed (3)
    public int $fontSize = 20;
    public string $backgroundColor = '255,255,255,127'; //RGBA
    public string $borderColor; //RGB
    public string $textColor = '000,000,000'; //RGB
    public int $angle;
    public int $gap = 15;
    public string $fontFile; // Path to TrueType Font
    public string $fontDirectory;
    public bool $randomCharactersFont = true; // only if no font is specified


    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function build()
    {
        load_helper_if_not_function('path', 'public_path');
        load_helper_if_not_function('filesystem', 'directory_map');

        /*
         *------------------------------------------------------------------------------------
         * DEFAULT DIRECTORY
         *------------------------------------------------------------------------------------
         */
        if (!isset($this->fontDirectory))
            $this->fontDirectory = base_dir('lexer69/captcha'); // Default direcotry, if dir not provided
    }

    public function imageAndExit()
    {
        $this->generateCaptcha();

        header("Content-type: image/png");
        ImagePNG($this->gd);

        exit(); // necessary
    }
    public function getBase64()
    {
        $this->generateCaptcha();

        ob_start();
        ImagePNG($this->gd);
        $image_data = ob_get_clean();

        return base64_encode($image_data);
    }

    private function generateCaptcha()
    {
        $this->setupFonts();

        if ($this->stringSize < 3 or $this->stringSize > 10) {
            throw new \Exception('The captcha code must have at least 3 characters and at most 10 characters.');
        }

        $this->gd = ImageCreate($this->width, $this->height);

        $string = $this->generateString();

        $bgColor = $this->getBackgroundColor();
        $borderColor = $this->getBorderColor();
        $textcolor = $this->getTextColor();

        if ($bgColor) {
            ImageFill($this->gd, 0, 0, $bgColor);
        }

        if ($borderColor) {
            ImageRectangle($this->gd, 0, 0, $this->width - 1, $this->height - 1, $borderColor);
        }

        $y = $this->height / 2 + $this->fontSize / 2; // Calculate the center y-coordinate

        for ($i = 0; $i < $this->stringSize; $i++) {
            $char = $string[$i];

            $factor = 15;
            $x = ($factor * ($i + 1)) + ($i * $this->gap); // Add the gap

            $angle = isset($this->angle) ? (rand(0, 1) == 0 ? -1 : 1) * rand(-$this->angle, $this->angle) : 0;

            imagettftext($this->gd, $this->fontSize, $angle, $x, $y, $textcolor, $this->getFont(), $char);
        }

        $this->setCaptchaSession($string);
    }



    private function setupFonts()
    {
        if (isset($this->fontFile)) {

            $this->fontFile = "$this->fontDirectory/$this->fontFile";

        } else {

            $this->directoryFonts = directory_map($this->fontDirectory);

            if ($this->randomCharactersFont) {

                foreach ($this->directoryFonts as &$font)
                    $font = "$this->fontDirectory/$font";

            } else {

                $font = $this->directoryFonts[array_rand($this->directoryFonts)];

                $this->fontFile = "$this->fontDirectory/$font";

            }
        }
    }
    private function getFont(): string
    {
        $font = '';

        if (isset($this->fontFile))
            $font = $this->fontFile;

        if ($this->randomCharactersFont)
            $font = $this->directoryFonts[array_rand($this->directoryFonts)];

        return $font;
    }



    private function getBackgroundColor()
    {
        $array = explode(',', $this->backgroundColor, 5);

        list($red, $green, $blue) = $array;

        $alpha = $array[3] ?? $this->defaultBackgroundAlpha;

        return ImageColorAllocateAlpha($this->gd, $red, $green, $blue, $alpha);
    }
    private function getTextColor()
    {
        list($red, $green, $blue) = explode(',', $this->textColor, 4);
        return ImageColorAllocate($this->gd, $red, $green, $blue);
    }
    private function getBorderColor()
    {
        if (!isset($this->borderColor))
            return null;

        list($red, $green, $blue) = explode(',', $this->borderColor);

        return ImageColorAllocate($this->gd, $red, $green, $blue);
    }

    private function getStringCaseRange(): array
    {
        $range = [];

        if ($this->case == 1) {
            $range = range('A', 'Z'); // Capital Case
        } else if ($this->case == 2) {
            $range = range('a', 'z'); // Small Case
        } else {
            // Mixed
            $range = array_merge(range('A', 'Z'), range('a', 'z'));
        }

        return $range;
    }




    private function generateString(): string
    {
        if ($this->stringType == 1) // letters
        {
            $array = $this->getStringCaseRange();
            $array = array_rand(array_flip($array), $this->stringSize);

        } else if ($this->stringType == 2) // numbers
        {
            $array = range(0, 9);
            $array = array_rand(array_flip($array), $this->stringSize);
        } else // letters & numbers
        {
            $x = ceil($this->stringSize / 2);

            $stringRange = $this->getStringCaseRange();
            $numRange = range(0, 9);

            $array_one = array_rand(array_flip($stringRange), $x);

            $array_two = array_rand(array_flip($numRange), $this->stringSize - $x);

            $array = array_merge($array_one, $array_two);

        }

        shuffle($array);

        $string = implode($array);

        return $string;
    }


    /*
     *------------------------------------------------------------------------------------
     *  Session set & Validation 
     *------------------------------------------------------------------------------------
     */
    private function setCaptchaSession(string $string)
    {
        $str = $this->keyPrefix . $this->key;

        session()->set($str, md5($string));
    }
    public function validate(string $code)
    {
        $hash = session()->get($this->keyPrefix . $this->key);



        if ($hash and !empty($hash))
            return $hash === md5($code);

        return false;
    }

}