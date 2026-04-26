<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Configurator\Constraint;

use Fasano\FormsBundle\Field\FieldConfig;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

class CardSchemeConfigurator implements ConstraintConfigurator
{
    // Derived from CardSchemeValidator::$schemes, stripped of PHP regex delimiters/anchors,
    // with capturing groups replaced by non-capturing groups.
    private const array SCHEME_PATTERNS = [
        'AMEX'          => ['3[47][0-9]{13}'],
        'CHINA_UNIONPAY'=> ['62[0-9]{14,17}'],
        'DINERS'        => ['3(?:0[0-5]|[68][0-9])[0-9]{11}'],
        'DISCOVER'      => ['6011[0-9]{12}', '64[4-9][0-9]{13}', '65[0-9]{14}'],
        'INSTAPAYMENT'  => ['63[7-9][0-9]{13}'],
        'JCB'           => ['(?:2131|1800|35[0-9]{3})[0-9]{11}'],
        'LASER'         => ['(?:6304|670[69]|6771)[0-9]{12,15}'],
        'MAESTRO'       => ['6759[0-9]{8,15}', '50[0-9]{10,17}', '5[6-9][0-9]{10,17}'],
        'MASTERCARD'    => ['5[1-5][0-9]{14}', '2(?:22[1-9][0-9]{12}|2[3-9][0-9]{13}|[3-6][0-9]{14}|7[0-1][0-9]{13}|720[0-9]{12})'],
        'MIR'           => ['220[0-4][0-9]{12,15}'],
        'UATP'          => ['1[0-9]{14}'],
        'VISA'          => ['4(?:[0-9]{12}|[0-9]{15}|[0-9]{18})'],
    ];

    private const string GENERIC_PATTERN = '[0-9]{12,19}';

    public function constraint(): string
    {
        return Constraints\CardScheme::class;
    }

    /** @param Constraints\CardScheme $constraint */
    public function configure(FieldConfig $config, Constraint $constraint): void
    {
        $patterns = [];

        foreach ((array) $constraint->schemes as $scheme) {
            foreach (self::SCHEME_PATTERNS[$scheme] ?? [] as $pattern) {
                $patterns[] = $pattern;
            }
        }

        $config->options['attr']['pattern'] = $patterns !== []
            ? implode('|', $patterns)
            : self::GENERIC_PATTERN;
    }
}
