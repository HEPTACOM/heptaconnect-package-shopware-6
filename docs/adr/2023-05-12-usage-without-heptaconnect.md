# 2023-05-12 - Usage without HEPTAconnect

## Context

This is a continuation of the [usage with HEPTAconnect ADR](./2023-03-28-heptaconnect.md).
This package has been found by a non-HEPTAconnect user, who tried to use it against a Shopware API.
The composer dependencies were more, than really were used because multiple HEPTAconnect portal and package utilities are built on top of additional vendors.
Most of it currently builds up on tagged services and PSR interfaces.


## Decision

As much nice-to-have as possible will be added to this package, that is possible to be used without `heptacom/heptaconnect-portal-base`.
This reduces most of the dependency chain, that is yet unused.
To reduce complexity some [utility classes](./2023-04-13-public-utility-classes.md) to factorize services, that would have been taken care of by HEPTAconnect, will be provided.


## Consequences

### Pros

* This package still integrates well into HEPTAconnect projects
* This package integrates well into non-HEPTAconnect projects


### Cons

* This package can not make use of basic features from `heptacom/heptaconnect-portal-base` without additional abstraction
