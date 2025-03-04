```shell
 XDEBUG_SESSION=1 PHP_IDE_CONFIG="serverName=symfony" KERNEL_CLASS="\Common\Kernel" php bin/phpunit
 ```
GET https://localhost/
accept: application/json
Content-Type: application/json
Cookie: XDEBUG_SESSION=PHPSTORM
###

POST https://localhost/
accept: application/json
Content-Type: application/json
Cookie: XDEBUG_SESSION=PHPSTORM

{
"ssn": "123-45-6789",
"email": "user777@example.com",
"firstName": "firstName",
"lastName": "lastName",
"addressId": "ae52262c-a5d0-5a6f-a83d-3c355059f959",
"birthDate": "1999-12-31",
"ficoScore": 700,
"monthlyIncome": 2000,
"phone": "(800) 326-6148"
}

//[addresses
//  "b414d2b7-b8ae-5204-a8ea-a7e3e478c5c8",
//  "ae52262c-a5d0-5a6f-a83d-3c355059f959",
//  "94e66daa-adec-50db-a938-92e7ffa4b39c",
//  "24dbc024-f4fc-5b68-99d7-cf0400723f92",
//  "3281828d-30f4-5f01-96d1-42bb850be875"
//]

//{users
//  "c13382cc-8c01-57e3-8a32-8a760740178d",
//  "b3265079-ad11-5fa5-98b7-b594a1f4c1b4",
//  "60d0a50f-563a-5554-8bec-6b749854ce33",
//  "7506d681-3edc-5bd9-83be-9a133f944871",
//  "b77bc3c3-c79f-5f78-b7b2-98fcd04bfde0"
//}

//{products
//  "8505f75d-98ac-5c09-9cc5-5f82ee404cd2",
//  "3ca89402-7372-5cd9-bfea-c5c219d032da",
//  "9c4a4cad-2ac2-5000-8454-ad6283ad11d1",
//  "d7e8873f-df94-5d32-93f9-711843967e1c",
//  "402f2820-8314-599e-bc8d-96bbf62f4771"
//}