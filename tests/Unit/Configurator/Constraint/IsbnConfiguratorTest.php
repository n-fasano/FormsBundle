<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\IsbnConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Isbn;

class IsbnConfiguratorTest extends TestCase
{
    private IsbnConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new IsbnConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(Isbn::class, $this->configurator->constraint());
    }

    public function testConfigureIsbn10(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Isbn(type: 'isbn10'));

        self::assertMatchesRegularExpression('/^' . $config->options['attr']['pattern'] . '$/', '0306406152');
        self::assertDoesNotMatchRegularExpression('/^' . $config->options['attr']['pattern'] . '$/', '9780306406157');
    }

    public function testConfigureIsbn13(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Isbn(type: 'isbn13'));

        self::assertMatchesRegularExpression('/^' . $config->options['attr']['pattern'] . '$/', '9780306406157');
        self::assertDoesNotMatchRegularExpression('/^' . $config->options['attr']['pattern'] . '$/', '0306406152');
    }

    public function testConfigureWithNoTypeAcceptsBoth(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Isbn());

        self::assertMatchesRegularExpression('/^(?:' . $config->options['attr']['pattern'] . ')$/', '0306406152');
        self::assertMatchesRegularExpression('/^(?:' . $config->options['attr']['pattern'] . ')$/', '9780306406157');
    }
}
