<?php

namespace Fasano\FormsBundle\Tests\Unit\Configurator\Constraint;

use Fasano\FormsBundle\Configurator\Constraint\JsonConfigurator;
use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Json;

class JsonConfiguratorTest extends TestCase
{
    private JsonConfigurator $configurator;

    protected function setUp(): void
    {
        $this->configurator = new JsonConfigurator();
    }

    public function testConstraint(): void
    {
        self::assertSame(Json::class, $this->configurator->constraint());
    }

    public function testConfigure(): void
    {
        $config = new FieldConfig();

        $this->configurator->configure($config, new Json());

        self::assertSame(TextareaType::class, $config->type);
    }
}
