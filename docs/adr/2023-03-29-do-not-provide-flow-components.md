# 2023-03-29 - Do not provide flow components

## Context

When a HEPTAconnect package provides flow components like receivers and emitters, they influence the behaviour on dependant projects.
Related code structures like packers and unpackers are affecting this as well.
When a package with flow components is used, updating the dependency onto it requires a critical view on its changes as a feature change could easily mean a break of the project using the package.
This increases the work to do on updating a package, which can lead to not updating the package or liking the usage of the package.


## Decision

Flow components work on datasets.
Without flow components there is no requirement on a specific dataset.
When this package makes less use of HEPTAconnect portal automations, a depending project will less likely break due to no changes on existing data flows and not introduce unexpected data flows.


## Consequences

### Pros

* Updating a dependency onto this package is less risky
* No additional dependency on HEPTAconnect datasets required


### Cons

* When updating a dependency onto this package, requires work to integrate new features into a HEPTAconnect portal
