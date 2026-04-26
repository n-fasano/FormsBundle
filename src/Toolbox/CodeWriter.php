<?php

declare(strict_types=1);

namespace Fasano\FormsBundle\Toolbox;

class CodeWriter
{
    protected string $indentBuffer;

    public function __construct(
        protected array $lines = [],
        protected int $level = 0,
        protected int $indentLength = 4,
        protected string $bufferChar = ' ',
    ) {
        $this->indentBuffer = str_repeat($bufferChar, $indentLength);
    }

    public static function from(
        string $code,
        int $indentLength = 4,
        string $bufferChar = ' ',
    ): CodeWriter {
        $writer = new CodeWriter(indentLength: $indentLength, bufferChar: $bufferChar);
        $lines = explode("\n", $code);

        $nonEmptyLines = array_filter($lines, fn (string $l): bool => trim($l) !== '');
        
        if (empty($nonEmptyLines)) {
            return $writer;
        }

        $minIndent = min(array_map(
            fn (string $l): int => \strlen($l) - \strlen(ltrim($l)),
            $nonEmptyLines,
        ));

        foreach ($lines as $line) {
            $writer->lines[] = trim($line) === '' ? '' : substr($line, $minIndent);
        }

        return $writer;
    }

    public function line(string $code): self
    {
        $this->lines[] = str_repeat($this->indentBuffer, $this->level) . $code;
        return $this;
    }

    public function indent(callable $fn): self
    {
        $this->level++;
        $fn($this);
        $this->level--;
        return $this;
    }

    public function blank(): self
    {
        $this->lines[] = '';
        return $this;
    }

    public function append(CodeWriter|string $other, int $indent = 0): self
    {
        if (!$other instanceof CodeWriter) {
            $other = CodeWriter::from($other, $this->indentLength, $this->bufferChar);
        }

        $delta = $this->level - $other->level + $indent;
        $pad = str_repeat($this->indentBuffer, abs($delta));

        foreach ($other->lines as $line) {
            if ($line === '') {
                $this->lines[] = '';
            } elseif ($delta >= 0) {
                $this->lines[] = $pad . $line;
            } else {
                $this->lines[] = substr($line, abs($delta) * 4);
            }
        }

        return $this;
    }

    public function render(): string
    {
        return implode("\n", $this->lines);
    }

    public function __toString(): string
    {
        return $this->render();
    }
}