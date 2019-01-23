[![Test Coverage](https://codeclimate.com/github/jaroslavtyc/granam-exceptions-hierarchy/badges/coverage.svg)](https://codeclimate.com/github/jaroslavtyc/granam-exceptions-hierarchy/coverage)
[![License](https://poser.pugx.org/granam/exceptions-hierarchy/license)](https://packagist.org/packages/granam/exceptions-hierarchy)

Philosophy of exception hierarchy is kind of *you need to know*.

It is important to know what happened. And it happens...

The system fails. Something exceptionable occurs.

Take extra care to get as much description of a problem as possible.

The good way how to achieve that is keep exceptions hierarchy clean and clear.

 - follow the project structure.
 - make a **root exception marker as an interface**.
 - include this interface in **every** exception you produce.
    - by that, anyone can catch exceptions from your project in his project, by a single catch
 - learn the difference between logic and runtime exception
    - the base distinction is the logic exception can be detected at compile time (for easy example by IDE), the runtime exception can not - it can occurs only in some combination of data and environment
    - logic exception should occurs if definitely there is an mistake in use of the application
        - that exception tells you: you are using something bad, fix it 
    - runtime exception is everything else of course
        - that means: your application is not so robust as should be - fix it or ignore for eternity
    - is it so simple, with clean borders?
        - of course not
        - but like that you can catch all the logic exceptions into folder PersistentBugs and runtime ones into BulletproofFailures

####In short
*Runtime exception* should occurs if something is wrong from **outside**.

*Logic exception* should occurs if something is wrong from **inside**.

###Example of use of Logic and Runtime exception

You built an e-shop with an API.

Your frontend application sends to the API a request for new customer:

 - email: dontbotherme

That is not valid email for sure. The frontend check failed and now you are forced to handle that failure.

So lets throw some *Runtime* (like InvalidEmailFormat) exception somewhere inside your code.

Your API of course should catch such exception and returns 400 Bad Request (and descriptive error message).

In another case your application calculates price of an purchase, including discount coupon, volume discount, loyalty discount and... voala, the final price is negative!

That is fatal error for sure, originating inside your application. *Logic* exception (like FinalPriceZeroOrLesser) should be thrown.

The API of course catch that exception and turn it into response 500 Server error (and log that exception for asap fix!).
