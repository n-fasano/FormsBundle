<?php

namespace Fasano\FormsBundle\Tests\Unit\Toolbox;

use Fasano\FormsBundle\Toolbox\CodeWriter;
use PHPUnit\Framework\TestCase;

class CodeWriterTest extends TestCase
{
    public function testLineAddsIndentedContent(): void
    {
        $result = (new CodeWriter())->line('foo')->render();

        self::assertSame('foo', $result);
    }

    public function testIndentIncreasesLevel(): void
    {
        $result = (new CodeWriter())
            ->line('outer')
            ->indent(fn (CodeWriter $w) => $w->line('inner'))
            ->line('outer')
            ->render();

        self::assertSame("outer\n    inner\nouter", $result);
    }

    public function testIndentRestoresLevelAfterCallable(): void
    {
        $w = new CodeWriter();
        $w->indent(fn (CodeWriter $w) => $w->line('x'));
        $w->line('back');

        self::assertSame("    x\nback", $w->render());
    }

    public function testBlankAddsEmptyLine(): void
    {
        $result = (new CodeWriter())->line('a')->blank()->line('b')->render();

        self::assertSame("a\n\nb", $result);
    }

    public function testAppendStringInlinesContent(): void
    {
        $result = (new CodeWriter())->line('a')->append('b')->render();

        self::assertSame("a\nb", $result);
    }

    public function testAppendWriterMergesLines(): void
    {
        $other = (new CodeWriter())->line('x')->line('y');
        $result = (new CodeWriter())->line('a')->append($other)->render();

        self::assertSame("a\nx\ny", $result);
    }

    public function testAppendWithPositiveIndentPadsLines(): void
    {
        $other = (new CodeWriter())->line('inner');
        $result = (new CodeWriter())->append($other, indent: 1)->render();

        self::assertSame('    inner', $result);
    }

    public function testAppendPreservesBlankLinesFromOther(): void
    {
        $other = (new CodeWriter())->line('x')->blank()->line('y');
        $result = (new CodeWriter())->line('a')->append($other)->render();

        self::assertSame("a\nx\n\ny", $result);
    }

    public function testAppendWithNegativeIndentStripsLeadingIndent(): void
    {
        $other = (new CodeWriter())->indent(fn (CodeWriter $w) => $w->line('stripped'));
        $result = (new CodeWriter())->append($other, indent: -1)->render();

        self::assertSame('stripped', $result);
    }

    public function testToStringCallsRender(): void
    {
        $w = (new CodeWriter())->line('hello');

        self::assertSame('hello', (string) $w);
    }

    public function testFromStripsCommonIndent(): void
    {
        $code = "    line1\n    line2";
        $w = CodeWriter::from($code);

        self::assertSame("line1\nline2", $w->render());
    }

    public function testFromHandlesEmptyString(): void
    {
        $w = CodeWriter::from('');

        self::assertSame('', $w->render());
    }

    public function testFromHandlesBlankLines(): void
    {
        $code = "    a\n\n    b";
        $w = CodeWriter::from($code);

        self::assertSame("a\n\nb", $w->render());
    }
}
