<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\UlidConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Ulid;

class UlidConfiguratorTest extends TestCase
{
    private UlidConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new UlidConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(Ulid::class, $this->configurator->constraint());
    }

    public function testPatternMatchesValidUlid(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Ulid());

        self::assertMatchesRegularExpression('/^' . $config->options['attr']['pattern'] . '$/', '01ARZ3NDEKTSV4RRFFQ69G5FAV');
    }

    public function testPatternRejectsTooShort(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Ulid());

        self::assertDoesNotMatchRegularExpression('/^' . $config->options['attr']['pattern'] . '$/', '01ARZ3NDEKTSV4RRFFQ69G5FA');
    }

    public function testPatternRejectsInvalidCharacters(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Ulid());

        // 'I', 'L', 'O', 'U' are excluded from Crockford base32
        self::assertDoesNotMatchRegularExpression('/^' . $config->options['attr']['pattern'] . '$/', '01ARZ3NDEKTSV4RRFFQ69G5FIL');
    }
}
