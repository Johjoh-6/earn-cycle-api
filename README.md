# Api for Earncycle

## Installation
update your .env file with the following

`DATABASE_URL="mysql://root:@127.0.0.1:3306/earncycle?serverVersion=8&charset=utf8mb4"`
or

`DATABASE_URL="postgresql://postgres:@127.0.0.1:5432/earncycle?serverVersion=14&charset=utf8"`

can be mysql, mariaDb or postgres.

run this command in the directory of the project
`touch .env.local`  or create the file `.env.local`
in this file add the following
```
ADMIN_EMAIL=
ADMIN_PASSWORD=

# pwd for reload db
REFRESH_DB_PASSWORD=
```
with the email and password you want to use for the admin user.
And the password for the reload db command.

### Warning
Make sure the folder `public/data/` and `config/jwt/` exist.


then run
```bash
bash setup-db.sh
```
or just run script with your editor.

## Usage

This api is made with Api platform and Symfony 5.4 // PHP 8.0
so by default Api platform return to you with get a json ld format.
you need to specify with `.json` at the end for only get a json format.
like :
```
http://127.0.0.1:8000/api/categories?page=1&deleted=false
```
this is json ld format. 
```
http://127.0.0.1:8000/api/categories.json?page=1&deleted=false
```
this is json format.

## run the server
```symfony serve```
and go to `http://127.0.0.1:8000/api` 
for the admin part
```bash
cd my-admin
npm run start
```

