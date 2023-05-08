<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Action\Contract\OrderDocumentCreate;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

final class OrderDocumentCreateResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private OrderDocumentCollection $data;

    /**
     * @var \Throwable[]
     */
    private array $errors;

    /**
     * @param \Throwable[] $errors
     */
    public function __construct(OrderDocumentCollection $data, array $errors)
    {
        $this->attachments = new AttachmentCollection();
        $this->data = $data;
        $this->errors = $errors;
    }

    public function getData(): OrderDocumentCollection
    {
        return $this->data;
    }

    /**
     * @return \Throwable[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
