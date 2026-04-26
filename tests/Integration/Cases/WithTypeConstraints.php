<?php

namespace Fasano\FormsBundle\Tests\Integration\Cases;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class WithTypeConstraints
{
    public function __construct(
        #[Assert\Country]
        public string $country,

        #[Assert\Currency]
        public string $currency,

        #[Assert\Language]
        public string $language,

        #[Assert\Locale]
        public string $locale,

        #[Assert\Timezone]
        public string $timezone,
    ) {}
}
