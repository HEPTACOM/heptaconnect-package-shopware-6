# 2023-04-13 - Public utility classes

## Context

Developing this package makes use of code extraction to reduce duplicate code.
This code is often not meant to be used somewhere else or exactly as is.

Usage of code, that is provided by this package, can be simplified by a facade.
These facades can reduce code of the consumer of this package.

In both cases code duplication is reduced but the former is to help the inner workings of the package and the latter is to reduce the entry level of the external usage of this package.
This is not easy to distinguish.
Both kinds of supporting code extractions are either used by code, that implements a contract or uses contracts.


## Decision

Classes in a support namespace are meant for internal usage.
Classes in a utility namespace are meant for external usage.
Support classes are not referenced in code examples to reduce contact of consumers to these classes.
All classes are made final so their code is not changed in an unexpected way.
If it is needed to change the code, provide a different implementation for the code, that is already meant to be replaced within the callstack.


## Consequences

### Pros

- Supporting code will not be changed unexpectedly
- Code flow can still be changed as stack contains interfaces


### Cons

- More complex package structure
