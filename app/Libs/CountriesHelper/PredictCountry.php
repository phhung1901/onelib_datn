<?php

namespace App\Libs\CountriesHelper;

use App\Libs\UrlHelper;
use App\SiteAnalytic\Analyzers\ContentAnalyzer;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Exception\MalformedUriException;
use Vuh\CliEcho\CliEcho;

class PredictCountry
{
    protected string $start_url;
    protected string $domain;
    protected ?string $language;

    protected ?string $country_code;

    protected array $isp_blocked = ['CloudFlare', 'Amazon.com', 'Google LLC', 'Automattic Inc', "Weebly Inc", "Automattic Inc"];

    public function __construct(string $start_url, ?string $language = null)
    {
        $this->start_url = $start_url;
        $this->domain = UrlHelper::domain($start_url);
        $this->language = $language;
    }

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->country_code;
    }

    public function predict() {
        $country_code = $this->predictByTLD();
        if (!$country_code) {
            $country_code_by_location = $this->predictByLocation() ?? '';
            $countries_code_by_language = $this->predictByLanguage() ?? [];
            if (count($countries_code_by_language) == 1) {
                $country_code = $countries_code_by_language[0];
            } else {
                $counted = array_count_values([$country_code_by_location, ...$countries_code_by_language]);
                arsort($counted);
                foreach ($counted as $code => $count) {
                    if ($count > 1) $country_code = $code;
                    break;
                }
            }
        }
        $this->country_code = $country_code;
        return $this;
    }

    protected function predictByTLD() {
        $tld = UrlHelper::tld($this->domain, false);
        CliEcho::infonl("\t - TLD: $tld");
        if (!$tld) return null;
        return CountriesByTLD::get($tld);
    }

    protected function predictByLocation() {
        $ip = gethostbyname($this->domain);
        $ip_info = UrlHelper::ipInfo($ip);
        if (!$ip_info) return null;

        CliEcho::infonl("\t - IP: $ip [{$ip_info['country_code2']}] [{$ip_info['isp']}]");

        if (preg_match("/" . implode("|", $this->isp_blocked) . "/ui", $ip_info['isp'])) {
            return null;
        }
        return $ip_info['country_code2'];
    }

    protected function predictByLanguage() {
        if (!$this->language || in_array($this->language, [Languages::OTHER, Languages::UNKNOWN])) {
            try {
                $content_analyzer = (new ContentAnalyzer($this->start_url))
                    ->setMaxUrl(1)
                    ->analyze();
            } catch (MalformedUriException|GuzzleException|\Exception $exception) {
                CliEcho::infonl("\t - Language: x [{$exception->getMessage()}]");
                return null;
            }

            $this->language = $content_analyzer->language;
        }
        $language = $this->language;

        CliEcho::infonl("\t - Language: $language");
//        $phones = $content_analyzer->getPhones();

        return CountriesByLanguage::search($language);
    }

}