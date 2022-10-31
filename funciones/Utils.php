<?php
//namespace CfdiUtils\Utils;

/**
 * This class provides static methods to format the values of the attributes
 */
class Utils
{
    public static function number(float $value, $decimals = 2): string
    {
        return number_format($value, $decimals, '.', '');
    }

    public static function datetime(int $timestamp): string
    {
        return date('Y-m-d\TH:i:s', $timestamp);
    }

public function remoteUrl(): string
{
    return sprintf(
        'https://rdc.sat.gob.mx/rccf/%s/%s/%s/%s/%s/%s.cer',
        substr($this->id, 0, 6),
        substr($this->id, 6, 6),
        substr($this->id, 12, 2),
        substr($this->id, 14, 2),
        substr($this->id, 16, 2),
        $this->id
    );
}

public static function isValidCertificateNumber(string $id): bool
{
    return (bool) preg_match('/^[0-9]{20}$/', $id);
}

/**
     * @param string $filename
     * @throws \UnexpectedValueException when the file does not exists or is not readable
     * @return void
     */
    protected function assertFileExists(string $filename)
    {
        if (! file_exists($filename) || ! is_readable($filename)) {
            throw new \UnexpectedValueException("File $filename does not exists or is not readable");
        }
    }








}
