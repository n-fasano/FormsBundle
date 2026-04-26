<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Field;

use Fasano\FormsBundle\Field\FieldConfig;
use PHPUnit\Framework\TestCase;

class FieldConfigTest extends TestCase
{
    public function testOverrideAppliesName(): void
    {
        $base = new FieldConfig(name: 'old');
        $base->override(new FieldConfig(name: 'new'));

        self::assertSame('new', $base->name);
    }

    public function testOverrideIgnoresNullName(): void
    {
        $base = new FieldConfig(name: 'kept');
        $base->override(new FieldConfig(name: null));

        self::assertSame('kept', $base->name);
    }

    public function testOverrideAppliesType(): void
    {
        $base = new FieldConfig(type: 'OldType');
        $base->override(new FieldConfig(type: 'NewType'));

        self::assertSame('NewType', $base->type);
    }

    public function testOverrideIgnoresNullType(): void
    {
        $base = new FieldConfig(type: 'Kept');
        $base->override(new FieldConfig(type: null));

        self::assertSame('Kept', $base->type);
    }

    public function testOverrideMergesOptions(): void
    {
        $base = new FieldConfig(options: ['required' => true, 'label' => 'Old']);
        $base->override(new FieldConfig(options: ['label' => 'New', 'attr' => []]));

        self::assertSame('New', $base->options['label']);
        self::assertTrue($base->options['required']);
    }

    public function testOverrideReturnsItself(): void
    {
        $base = new FieldConfig();
        $returned = $base->override(new FieldConfig());

        self::assertSame($base, $returned);
    }
}
