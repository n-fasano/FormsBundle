<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Http;

use Fasano\FormsBundle\Http\FormValueResolver;
use Fasano\FormsBundle\Http\MapFormPayload;
use PHPUnit\Framework\TestCase;

class MapFormPayloadTest extends TestCase
{
    public function testFormDtoFqcnIsExposed(): void
    {
        $attr = new MapFormPayload(formDtoFqcn: 'App\\Dto\\Register');

        self::assertSame('App\\Dto\\Register', $attr->formDtoFqcn);
    }

    public function testResolverClassIsFormValueResolver(): void
    {
        $attr = new MapFormPayload(formDtoFqcn: 'App\\Dto\\Register');

        // MapFormPayload extends ValueResolver which stores the resolver class
        self::assertSame(FormValueResolver::class, $attr->resolver);
    }

    public function testDisabledDefaultIsFalse(): void
    {
        $attr = new MapFormPayload(formDtoFqcn: 'App\\Dto\\Register');

        self::assertFalse($attr->disabled);
    }

    public function testDisabledCanBeSetToTrue(): void
    {
        $attr = new MapFormPayload(formDtoFqcn: 'App\\Dto\\Register', disabled: true);

        self::assertTrue($attr->disabled);
    }
}
