# 2023-03-28 - HEPTAconnect

## Context

[HEPTAconnect](https://heptaconnect.io) is a framework to build applications on a data flow between different APIs.
One API, that is commonly used is Shopware 6.
With that an API client was developed, that allows for low code usage.


## Decision

The API client from the Shopware 6 portal will be extracted into here for different project usage and more customization.
The package is still based on HEPTAconnect to make use of its supporting utilities.
To integrate well with HEPTAconnect projects the same [Architecture Decision Records](https://heptaconnect.io/reference/) are applied from HEPTAconnect core development.


## Consequences

### Pros

* This package integrates well into HEPTAconnect projects


### Cons

* This package might not be runnable well without HEPTAconnect
