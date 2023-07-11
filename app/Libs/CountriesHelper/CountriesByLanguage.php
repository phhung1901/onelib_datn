<?php

namespace App\Libs\CountriesHelper;

class CountriesByLanguage
{
    protected static ?self $instance = null;

    protected array $countries = [

    ];

    public function __construct()
    {
        //https://github.com/annexare/Countries/blob/master/dist/countries.csv
        $raw = file_get_contents(__DIR__ . "/countries.json");
        $this->countries = json_decode($raw, true);
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function search(string $language) {
        $instance = self::getInstance();
        $results = [];
        foreach ($instance->countries as $country_code => $data) {
            if (!$data['languages']) continue;
            if (in_array($language, $data['languages'])) {
                $results[] = $country_code;
            }
        }

        return $results;
    }
}
