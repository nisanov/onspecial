<?php

declare(strict_types=1);

namespace App\Service\Services;

use App\Service\Generic;

class Captcha extends Generic
{
    /**
     * The permitted characters that will create the captcha string.
     *
     * @var string
     */
    protected const CHARS = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

    /**
     * The generated captcha string.
     *
     * @var string
     */
    protected string $string = "";

    /**
     * The generated captcha image.
     *
     * @var resource
     */
    protected $image;

    /**
     * Construct the class and initialize the randomly generated captcha string.
     *
     * @param int $strength
     */
    public function __construct(int $strength = 5)
    {
        $this->generateString($strength);
        $this->generateBackground();
        $this->generateCharacters();
    }

    /**
     * Generates random captcha string.
     *
     * @param int $strength
     * @return void
     */
    private function generateString(int $strength): void
    {
        $length = strlen(self::CHARS) - 1;

        for ($i = 0; $i < $strength; $i++) {
            $this->string .= self::CHARS[mt_rand(0, $length)];
        }
    }

    /**
     * Generates the background for the captcha image.
     *
     * @return void
     */
    private function generateBackground(): void
    {
        $this->image = imagecreatetruecolor(200, 50);

        imageantialias($this->image, true);

        $colors = [];

        for ($i = 0; $i < 5; $i++) {
            $colors[] = imagecolorallocate($this->image, rand(125, 175) - 20 * $i, rand(125, 175) - 20 * $i, rand(125, 175) - 20 * $i);
        }

        imagefill($this->image, 0, 0, $colors[0]);

        for ($i = 0; $i < 10; $i++) {
            imagesetthickness($this->image, rand(2, 10));
            $rect_color = $colors[rand(1, 4)];
            imagerectangle($this->image, rand(-10, 190), rand(-10, 10), rand(-10, 190), rand(40, 60), $rect_color);
        }
    }

    /**
     * Generates the background for the character image.
     *
     * @return void
     */
    private function generateCharacters(): void
    {
        $black = imagecolorallocate($this->image, 0, 0, 0);
        $white = imagecolorallocate($this->image, 255, 255, 255);

        $textColours = [$black, $white];

        $length = strlen($this->string);
        for ($i = 0; $i < $length; $i++) {
            $letter_space = 170 / $length;
            $initial = 15;
            imagettftext(
                $this->image,
                20,
                rand(-15, 15),
                (int)($initial + $i * $letter_space),
                rand(20, 40),
                $textColours[rand(0, 1)],
                '/code/public_src/fonts/Quicksand-Bold.ttf',
                $this->string[$i],
            );
        }
    }

    /**
     * Render the randomly generated captcha image.
     *
     * @return resource
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Get the randomly generated captcha string.
     *
     * @return string
     */
    public function getString(): string
    {
        return $this->string;
    }
}
