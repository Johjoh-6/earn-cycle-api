# Api for Earncycle

## Installation
update your .env file with the following
`DATABASE_URL="mysql://root:@127.0.0.1:3306/earncycle?serverVersion=8&charset=utf8mb4"`
or
`DATABASE_URL="postgresql://postgres:@127.0.0.1:5432/earncycle?serverVersion=14&charset=utf8"`

can be mysql, mariaDb or postgres.
then run

```bash
bash setup-db.sh
```
or just run script with your editor.

## Usage

This api is made with Api platform and Symfony 5.4
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
