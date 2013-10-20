phpMyAPI
--------

Rest api made easy for the rest of us.

phpMyAPI is a single file microscopic php framework that magicaly transforms your old school database into a hipster rest api json server.

On top of that you can extend it to provide any API you want.

for example : 

class ola {
    function get_quetal($a) {
        return "ola, quetal $a ?";
    }
}

# Usage
GET /ola/quetal/senior
RESPONSE "ola, quetal senior ?"






## List tables

List database tables :

    GET /

### Response

    ["users", "pages", "another_table"]




## List table records

List records from table 'users':

    GET /users

### Parameters

fields
: Comma separated list of fields to returns.

q
: JSON query filter

sort
: Sort field. 

order
: Sort order if `sort` param is provided. One of `asc` or `desc`.

page
: Page number to fetch

per_page
: number of items for each call

### Response

    [
      {"id": 1, "firstname": "John", "lastname": "Doh" },
      {"id": 2, "firstname": "Bob", "lastname": "Martin" }
    ]





## Get a single record

Get user whose 'id' is 2 :

    GET /users/2

### Parameters

key
: primary key (default: 'id')

### Response

    {"id": 2, "firstname": "Bob", "lastname": "Martin" }





## Create a record

Create new user "Hector Norman":

    POST /users

### Input

    {"firstname": "Hector", "lastname": "Norman" }

### Response

    {"id": 3, "firstname": "Hector", "lastname": "Norman" }





