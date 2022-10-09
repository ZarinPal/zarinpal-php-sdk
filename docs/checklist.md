# zarinpal implementation checklist

## SDK checklist

- [Internals](./checklist.md#internals)
    - [HTTP client](./checklist.md#http-client)
        - [HTTP headers](./checklist.md#http-handlers)
            - [ ] [formatted user agent](./checklist.md#formatted-user-agent)
            - [ ] [json accept header](./checklist.md#json-accept-header)
            - [ ] [json content type header](./checklist.md#json-content-type-header)
        - [ ] [timeouts](./checklist.md#timeouts)
        - [ ] [rate limiting and allow retry](./checklist.md#rate-limiting-and-allow-retry)
        - [API handlers](./checklist.md#api-handlers)
            - [ ] [REST handlers](./checklist.md#rest-handlers)
            - [ ] [GraphQL handlers](./checklist.md#graphql-handlers)
        - [exceptions](./checklist.md#exceptions)
            - [ ] [HTTP exceptions](./checklist.md#http-exceptions)
            - [ ] [validation exceptions](./checklist.md#validation-exceptions)
            - [ ] [API exceptions](./checklist.md#api-exceptions)
        - [analytics](./checklist.md#analytics)
            - [ ] [performance](./checklist.md#performance)
            - [ ] [error reports](./checklist.md#error-reports)

- [Services](./checklist.md#services)
    - [Payment Gateway](./checklist.md#payment-gateway)
        - [ ] [request new payment](./checklist.md#request-new-payment)
        - [ ] [payment start](./checklist.md#payment-start)
        - [ ] [verify payment](./checklist.md#verify-payment)
        - [ ] [unverified payment](./checklist.md#unverified-payment)

    - [Payment Refound](./checklist.md#payment-refound)
        - [ ] [request new payment refound](./checklist.md#request-new-payment-refound)
        - [ ] [retrieve payment refound by id](./checklist.md#retrieve-payment-refound-by-id)
        - [ ] [payment refound receipt link](./checklist.md#payment-refound-receipt-link)

    - [Invoice](./checklist.md#invoice)
        - [ ] [request new invoice](./checklist.md#request-new-invoice)
        - [ ] [invoice start payment (with zarinpal)](./checklist.md#invoice-start-payment)
        - [ ] [invoice start payment (payment gateway)](./checklist.md#invoice-start-payment)
        - [ ] [retrieve invoice (list, by id)](./checklist.md#retrieve-invoice)

    - [Payout](./checklist.md#payout)
        - [ ] [request new Payout](./checklist.md#request-new-Payout)
        - [ ] [deactivate Payout](./checklist.md#deactivate-Payout)
        - [ ] [retrieve Payout (list, by id)](./checklist.md#retrieve-Payout)

    - [Instant Payout](./checklist.md#instant-payout)
        - [ ] [request new instant payout](./checklist.md#request-new-instant-payout)
        - [ ] [retrieve instant payout (list, by id)](./checklist.md#retrieve-instant-payout)
        - [ ] [instant payout receipt link](./checklist.md#instant-payout-receipt-link)

    - [ ] [Oauth](./checklist.md#oauth)

    - [User](./checklist.md#user)
        - [ ] [retrieve user](./checklist.md#retrieve-user)

    - [Terminal](./checklist.md#terminal)
        - [ ] [retrieve Terminal (list, by id)](./checklist.md#retrieve-Terminal)
        - [ ] [add Terminal](./checklist.md#add-Terminal)
        - [ ] [edit Terminal](./checklist.md#edit-Terminal)

    - [Bank Account](./checklist.md#bank-account)
        - [ ] [retrieve bank account (list, by id)](./checklist.md#retrieve-bank-account)
        - [ ] [add bank account](./checklist.md#add-bank-account)
        - [ ] [edit bank account](./checklist.md#edit-bank-account)

    - [Payment Session](./checklist.md#payment-session)
        - [ ] [retrieve payment session (list, by id)](./checklist.md#retrieve-payment-session)

    - [Reconciliation](./checklist.md#reconciliation)
        - [ ] [retrieve reconciliation (list, by id)](./checklist.md#retrieve-reconciliation)

## [Internals](./checklist.md#internals)

### [HTTP client](./checklist.md#http-client)

#### [HTTP headers](./checklist.md#http-handlers)

##### [formatted user agent](./checklist.md#formatted-user-agent)

##### [json accept header](./checklist.md#json-accept-header)

##### [json content type header](./checklist.md#json-content-type-header)

#### [timeouts](./checklist.md#timeouts)

#### [rate limiting and allow retry](./checklist.md#rate-limiting-and-allow-retry)

#### [API handlers](./checklist.md#api-handlers)

##### [REST handlers](./checklist.md#rest-handlers)

##### [GraphQL handlers](./checklist.md#graphql-handlers)

#### [exceptions](./checklist.md#exceptions)

##### [HTTP exceptions](./checklist.md#http-exceptions)

##### [validation exceptions](./checklist.md#validation-exceptions)

##### [API exceptions](./checklist.md#api-exceptions)

#### [analytics](./checklist.md#analytics)

##### [performance](./checklist.md#performance)

##### [error reports](./checklist.md#error-reports)

## [Services](./checklist.md#services)

### [Payment Gateway](./checklist.md#payment-gateway)

#### [request new payment](./checklist.md#request-new-payment)

#### [payment start](./checklist.md#payment-start)

#### [verify payment](./checklist.md#verify-payment)

#### [unverified payment](./checklist.md#unverified-payment)

### [Payment Refound](./checklist.md#payment-refound)

#### [request new payment refound](./checklist.md#request-new-payment-refound)

#### [retrieve payment refound by id](./checklist.md#retrieve-payment-refound-by-id)

#### [payment refound receipt link](./checklist.md#payment-refound-receipt-link)

### [Invoice](./checklist.md#invoice)

#### [request new invoice](./checklist.md#request-new-invoice)

#### [invoice start payment (with zarinpal)](./checklist.md#invoice-start-payment)

#### [invoice start payment (payment gateway)](./checklist.md#invoice-start-payment)

#### [retrieve invoice (list, by id)](./checklist.md#retrieve-invoice)

### [Payout](./checklist.md#payout)

#### [request new Payout](./checklist.md#request-new-Payout)

#### [deactivate Payout](./checklist.md#deactivate-Payout)

#### [retrieve Payout (list, by id)](./checklist.md#retrieve-Payout)

### [Instant Payout](./checklist.md#instant-payout)

#### [request new instant payout](./checklist.md#request-new-instant-payout)

#### [retrieve instant payout (list, by id)](./checklist.md#retrieve-instant-payout)

#### [instant payout receipt link](./checklist.md#instant-payout-receipt-link)

### [Oauth](./checklist.md#oauth)

### [User](./checklist.md#user)

#### [retrieve user](./checklist.md#retrieve-user)

### [Terminal](./checklist.md#terminal)

#### [retrieve Terminal (list, by id)](./checklist.md#retrieve-Terminal)

#### [add Terminal](./checklist.md#add-Terminal)

#### [edit Terminal](./checklist.md#edit-Terminal)

### [Bank Account](./checklist.md#bank-account)

#### [retrieve bank account (list, by id)](./checklist.md#retrieve-bank-account)

#### [add bank account](./checklist.md#add-bank-account)

#### [edit bank account](./checklist.md#edit-bank-account)

### [Payment Session](./checklist.md#payment-session)

#### [retrieve payment session (list, by id)](./checklist.md#retrieve-payment-session)

### [Reconciliation](./checklist.md#reconciliation)

#### [retrieve reconciliation (list, by id)](./checklist.md#retrieve-reconciliation)
