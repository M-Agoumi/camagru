# camagru
### School project where a user can register and take pictures edit them and share them.  
  
let's start by sitting up the project  
  
first edit the database config from the root of the project run  
`mv config/db.conf.example config/db.conf`  
  
after edit the `config/db.conf` with your database information  
  
if you have the database created you can skip the next command  
`bin/console migrate:create:database`  
  
now after we created our database, it's time to create our tables run  
`bin/console migrate:migrate`  
  
now your application is ready, just run the server  
`bin/console server:start`  
  
the server starts on port 8000, if it's already used you can specify another port  
`bin/console server:start -p 7999`  
