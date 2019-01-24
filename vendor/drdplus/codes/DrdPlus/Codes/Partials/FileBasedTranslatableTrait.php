<?php
declare(strict_types=1);

namespace DrdPlus\Codes\Partials;

trait FileBasedTranslatableTrait
{
    protected function fetchTranslations(): array
    {
        $handle = \fopen($this->getTranslationsFileName(), 'rb');
        $rows = [];
        while (($row = \fgetcsv($handle)) !== false && $row !== null) {
            if ($row !== []) {
                $rows[] = $row;
            }
        }
        \array_shift($rows); // removing header row
        $translations = [];
        foreach ($rows as $row) {
            $translation = [self::$ONE => \trim($row[2])];
            if (\array_key_exists(3, $row)) {
                $translation['few'] = \trim($row[3]);
                if (\array_key_exists(4, $row)) {
                    $translation['many'] = \trim($row[4]);
                }
            }
            $translations[\trim($row[1] /* language */)][\trim($row[0]/* code */)] = $translation;
        }

        return $translations;
    }

    abstract protected function getTranslationsFileName(): string;
}