<?php

namespace Fasano\FormsBundle\Tests\Integration\Cases;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class WithFile
{
    public function __construct(
        #[Assert\File(mimeTypes: ['application/pdf', 'application/msword'], extensions: ['pdf', 'doc', 'docx'])]
        public string $document,

        #[Assert\Image]
        public UploadedFile $avatar,

        #[Assert\Image(mimeTypes: ['image/png'])]
        public ?UploadedFile $thumbnail,
    ) {}
}
