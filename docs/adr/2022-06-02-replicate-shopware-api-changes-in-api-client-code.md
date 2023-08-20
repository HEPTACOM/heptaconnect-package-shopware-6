# 2022-06-02 - Replicate Shopware API changes in API client code

## Context

HEPTAconnect integrations may include communication to different Shopware API versions.
Shopware allows themselves to make breaking changes at major version changes.
They also allow themselves to already provide alternatives to existing API calls.
API requests and responses have two distinctive parts:

* DAL entity names with their properties
* structures, that wrap around entity data and API call specific structs

Any Shopware plugin can introduce new entities and therefore unknown structures and also add new properties at any time.
With Shopware major version changes, it is also allowed to change property type and also remove them.
Any Shopware plugin can add new API calls.

Although a Shopware shop exposes an OpenAPI specification, this flexibility of the API is not always represented in that specification.
Generating API client code, that replicates the OpenAPI specification too closely is fragile, when changes occur:
New fields, changed types of fields and missing fields will break serialization and deserialization of request and response bodies.
This is good for code, that depends on these fields, but is annoying to maintain, when serialization of an entity breaks due to a conflict of a field, that is not important for the situation.

Utility code should provide valuable reduction of actual project code and speed up development.
Development can be sped up by providing a certain structure you can follow so you do not have to compare your loose payloads against an API documentation.
Valuable code reduction would be a pre-implementation of an API call or an automatically authenticated API call.

Eventually this ends up in a conflict:

* We want to have strictly typed code
    * guiding IDE suggestions
    * allow static code analysis for better development and testing
* We do not want to have strictly typed code
    * to increase code stability
    * reduce need of maintenance
    * follow dynamic structure of the API


## Decision

We provide as much utility code as possible, that is not dependant on specific API calls:

* Automatic authentication
* Interpreting errors from JSON responses
* Building non-JSON payloads
* Adding API-specific headers to requests

As entities are the most fragile part and can even come only in partially filled quality.
Therefore we do not provide any strictly typed code around entities and only use list and associative arrays.

Structures around entities rarely change.
These will be the parts of payload building and response parsing we can support.
As the number of API endpoints vary we do not provide "all actions known" API client.
If we supply support for an API call, it is built in a class for each API call.

When a follow-up API is provided by Shopware we try to integrate it into existing API call classes to reduce code, that needs to be maintained on an update.
If there are structural changes in either the request or response bodies, the new API call shall remain in the same support class and a new class, that follows the previous structure remains to enable old API calls.
These new services have their own versioning as Shopware does not provide this information.


## Consequences

### Pros

* Reduction of project code
* Migration of project code into library code is easier due to one service per API call
* API calls are authenticated automatically
* API calls behave similar between each other
* Preparing for Shopware updates is easier as PHP code will break so one can see the changes to look into without executing the code
* Using the same code against two different Shopware API versions is a maintainable effort
* API calls can be used similar to Shopware plugin code


### Cons

* A service for each API call is a lot of work
* Testing needs to be done for various Shopware versions
