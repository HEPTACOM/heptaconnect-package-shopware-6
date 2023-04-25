# Use AdminAPI package expectations per request

Use package expectations with the Admin API, that are applicable for a single request:

## Portal

###### src/Receiver/ProductReceiver.php

Add package expectation to an entity create request.

```php
<?php

namespace Portal\Http;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\ErrorHandling\Exception\ExpectationFailedException;
use Heptacom\HeptaConnect\Dataset\Ecommerce\Product\Product;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreateActionInterface;
use Heptacom\HeptaConnect\Package\Shopware6\Http\AdminApi\Entity\Contract\EntityCreate\EntityCreatePayload;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;

class ProductReceiver extends ReceiverContract
{
    private EntityCreateActionInterface $create;

    public function __construct(EntityCreateActionInterface $create)
    {
        $this->create = $create;
    }

    protected function supports(): string
    {
        return Product::class;
    }

    protected function run(DatasetEntityContract $entity,ReceiveContextInterface $context): void
    {
        try {
            $payload = new EntityCreatePayload('product', [
                'states' => ['is-download'],
            ]);
            $payload = $payload->withExpectedPackage('shopware/core', '>=6.4.20');

            $this->create->create($payload);
        } catch (ExpectationFailedException $exception) {
            // we cannot make use of digital products in Shopware
        }
    }
}
```
