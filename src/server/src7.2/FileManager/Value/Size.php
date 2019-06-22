<?php

namespace Nrg\FileManager\Value;

/**
 * Class Size.
 *
 * File size entity implementation.
 */
class Size
{
    /**
     * @var int
     */
    private $value;

    /**
     * Size constructor.
     *
     * @param int $value
     */
    public function __construct(float $value)
    {
        $this->setValue($value);
    }

    /**
     * @return int
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @param int $value
     */
    public function setValue(float $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getValue();
    }

    /**
     * Returns an intelligible view of file size.
     *
     * @return string
     */
    public function toHumanString(): string
    {
        $bytes = $this->getValue();
        $thresh = 1024;

        if (abs($bytes) < $thresh) {
            return $bytes.' B';
        }

        $units = ['KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $u = -1;
        do {
            $bytes /= $thresh;
            ++$u;
        } while (abs($bytes) >= $thresh && $u < count($units) - 1);

        return round($bytes, 1).' '.$units[$u];
    }
}
