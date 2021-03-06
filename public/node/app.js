var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var mysql = require("mysql");
var resource = require('./resource.controller');


// First you need to create a connection to the db
var con = mysql.createConnection({
  host: "localhost",
  user: "root",
  password: "root",
  database: "OGame"
});

con.connect(function(err){
  if(err){
    console.log('Error connecting to Db');
    return;
  }
  console.log('Connection established');
});
/*
con.query('SELECT * FROM buildings',function(err,rows){
  if(err) throw err;

  console.log('Data received from Db:\n');
  console.log(rows);
});
con.end(function(err) {
  // The connection is terminated gracefully
  // Ensures all previously enqueued queries are still
  // before sending a COM_QUIT packet to the MySQL server.
  console.log('connection end');
});
*/
io.on('connection', function(socket){
    console.log('A user connected');
    resource.constructor(socket, con);
    socket.on('disconnect', function () {
        console.log('A user disconnected');
    });
});

http.listen(8000, function(){
  console.log('listening on *:8000');
});
