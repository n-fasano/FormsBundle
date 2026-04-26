<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Tests\Unit\Attribute;

use Fasano\FormsBundle\Attribute\Form\Options;
use PHPUnit\Framework\TestCase;

class FormOptionsTest extends TestCase
{
    public function testOffsetExistsReturnsTrueForSetKey(): void
    {
        $opts = new Options(label: 'Title');

        self::assertTrue(isset($opts['label']));
    }

    public function testOffsetExistsReturnsFalseForMissingKey(): void
    {
        $opts = new Options();

        self::assertFalse(isset($opts['label']));
    }

    public function testOffsetGetReturnsValue(): void
    {
        $opts = new Options(label: 'Title');

        self::assertSame('Title', $opts['label']);
    }

    public function testOffsetSetUpdatesValue(): void
    {
        $opts = new Options(label: 'Old');
        $opts['label'] = 'New';

        self::assertSame('New', $opts['label']);
    }

    public function testOffsetUnsetRemovesKey(): void
    {
        $opts = new Options(label: 'Title');
        unset($opts['label']);

        self::assertFalse(isset($opts['label']));
    }
}
