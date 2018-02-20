
# Authentication


## Token

SugarLog uses [LexikJWTAuthenticationBundle](https://github.com/lexik/LexikJWTAuthenticationBundle) as the Token Manager and general authentication. The `security.yml` file contains all the configuration. All routes prefixed with `/api` will need to pass the authentication guard. 

## How it works

The token is returned as a response at `user/login` and is sent for each subsequent request as `Authorization Bearer {token}` header. Lexik will automatically check the JWT is valid, not expired and allow pass through, or return a 403 response. 

## Symfony Kernel Event

Another layer is present before the Lexik events are even hit. A kernel event is excuted for each request. Controller classes that impliment `TokenAuthenticationController` will be "tagged" as requiring a pre-check of the JWT in the (sent as Authorization header) in the DB. If the JWT doesn't exist, a 403 is returned and access to the resource is denied. Checking for a JWT assigned to a user means we can log out users and effectively "blacklist" JWTs, as JWTS by nature contain no state, and aren't aware the user has logged out, without some form of persistance. 

Controllers that do not impliment the `TokenAuthenticationController` will not be subjected to this check. 

Controllers that do impliment `TokenAuthenticationController`, if passed, will proceed with Lexik's JWT decoding as we still require validation and token decoding to find the correct user. 

The logic for the `TokenAuthenticationController` is in it's EventListener in `User/Infrastructure/EventListener/` directory. 

## Errors

All errors are created by Lexik, which can be customized in by creating EventListeners. The only exception in regards to use Authentication, is `TokenAuthenticationSubscriber` will return DeniedAccess if the JWT does not exist in the `user` table. 
 
