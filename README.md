# Server for my REST API

###### What I used: MySQL, PHP, JSON, XML, RedBeanPHP (ORM)

This is a server side of my REST API that I created for a university project. The whole project is a combination of a wishlist and an organization tool for managing gifts that we buy for others. It consists of a server connected to a MySQL database and a client side, which are connected through a REST API.

This is the repository of the server side. The database was created using an Object-Relational Mapping tool called RedBeanPHP. It created tables based on example objects. (It was necessary for the initial stage of the project. When the client was created, it provided an interface for adding entries to the database.) The server was responsible for querying the database and sending information to the client. It could also read some data from JSON / XML files and add it to the database (and the other way around).   
